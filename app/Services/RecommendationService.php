<?php

namespace App\Services;

use App\Models\CityClimate;
use App\Models\CropData;
use App\Models\CropRecommendation;
use App\Models\CropYield;
use App\Models\Plot;
use Phpml\Classification\NaiveBayes;

class RecommendationService
{
    public function recommendBySoilHealth($selectedPlot) {

        $plotSoilHealth = $selectedPlot->latestSoil;

        // check if no soil record at all
        if (!$plotSoilHealth) {
            return 'No soil record available';
        }

        // check if any of the soil health values are null
        if (collect([
                $plotSoilHealth->nitrogen,
                $plotSoilHealth->phosphorus,
                $plotSoilHealth->potassium,
                $plotSoilHealth->temperature,
                $plotSoilHealth->humidity,
                $plotSoilHealth->ph
            ])->contains(null)) {
            return 'Not enough soil health data';
        } else {
            // get the training data from the CropRecommendation table
            $cropRecommendations = CropRecommendation::all();

            // prepare the samples and labels arrays
            $samples = [];
            $labels = [];

            foreach ($cropRecommendations as $recommendation) {
                // add the soil healths as a sample
                $samples[] = [
                    $recommendation->nitrogen,
                    $recommendation->phosphorus,
                    $recommendation->potassium,
                    $recommendation->temperature,
                    $recommendation->humidity,
                    $recommendation->ph,
                ];

                // add the recommended crops as the label
                $labels[] = $recommendation->recommended_crop;
            }

            // train the naive bayes classifier
            $classifier = new Naivebayes();
            $classifier->train($samples, $labels);

            // predict the recommended crop
            $predictedCrop = $classifier->predict([
                $plotSoilHealth->nitrogen,
                $plotSoilHealth->phosphorus,
                $plotSoilHealth->potassium,
                $plotSoilHealth->temperature,
                $plotSoilHealth->humidity,
                $plotSoilHealth->ph,
            ]);

            return $this->getCropInformation($predictedCrop, $selectedPlot, true);

        }

    }

    public function recommendBySeason($selectedPlot) {

        $plotSoilType = $selectedPlot->soil_type;
        $city = $selectedPlot->city;
        $climate = CityClimate::where('municipality', $city)->firstOrFail()->climate;
        $climateColumn = 'climate_' . $climate;
        $currentMonth = now()->month;

        // fetch crops where the climate_X column contains the current month AND the soil type matches
        $cropNames = CropData::whereJsonContains($climateColumn, $currentMonth)
            ->whereJsonContains('soil_types', $plotSoilType)
            ->pluck('crop_name');

        // Transform each crop name into detailed crop information
        $cropsWithDetails = $cropNames->map(fn($crop) => $this->getCropInformation($crop, $selectedPlot, false));

        return $cropsWithDetails;
    }

    private function checkIdealSoil($cropData, $soilType) {

        // decode the soil_types JSON array from the CropData
        $idealSoilTypes = json_decode($cropData->soil_types, true);

        // check if the plot's soil type is in the list of ideal soil types
        if (in_array($soilType, $idealSoilTypes)) {
            return "Yes";
        }

        // format the ideal soil types as a readable sentence
        $bestSoils = implode(', ', $idealSoilTypes);

        return "No, it is best on $bestSoils";
    }

    private function soonestIdealMonth($idealMonths) {

        $currentMonth = now()->month;

        // find the soonest ideal planting month
        if (in_array($currentMonth, $idealMonths)) {
            $soonestMonth = "This month";
        } else {
            // find the closest upcoming month
            $filteredMonths = array_filter($idealMonths, fn($month) => $month > $currentMonth);

            // if there are no future months in the list, take the first available one in the next year
            $soonestMonth = !empty($filteredMonths)
                ? min($filteredMonths)
                : min($idealMonths); // wraps around to the first month if none are greater

            // convert numeric month to name
            $soonestMonth = date("F", mktime(0, 0, 0, $soonestMonth, 1));
        }

        return $soonestMonth;
    }

    private function calculateSeeds($cropData, $plotSize) {
        $min = $cropData->seeds_needed_min * $plotSize ;
        $max = $cropData->seeds_needed_max * $plotSize;
        $unit = $cropData->seeds_unit;

        if ($min < $max) {
            return $min . ' to ' . $max . ' ' . $unit;
        } else {
            return $min . ' ' . $unit;
        }
    }

    private function calculateDensity($cropData, $plotSize) {
        $min = $cropData->density_min * $plotSize ;
        $max = $cropData->density_max * $plotSize;

        if ($min < $max) {
            return $min . ' to ' . $max;
        } else {
            return $min;
        }
    }

    private function calculateYield($cropData, $plotSize) {
        $min = $cropData->yield_min * $plotSize ;
        $max = $cropData->yield_max * $plotSize;

        if ($min < $max) {
            return $min . ' to ' . $max . ' tons';
        } else {
            return $min . ' tons';
        }
    }

    private function calculateSpacingPlant($cropData) {
        $min = $cropData->spacing_plant_min;
        $max = $cropData->spacing_plant_max;

        if ($min < $max) {
            return $min . ' to ' . $max . ' cm';
        } else {
            return $min . ' cm';
        }
    }

    private function calculateSpacingRow($cropData) {
        $min = $cropData->spacing_row_min;
        $max = $cropData->spacing_row_max;

        if ($min < $max) {
            return $min . ' to ' . $max . ' cm';
        } else {
            return $min . ' cm';
        }
    }

    private function calculateMaturity($cropData) {
        $min = $cropData->maturity_min;
        $max = $cropData->maturity_max;
        $unit = $cropData->maturity_unit;

        if ($min < $max) {
            return $min . ' to ' . $max . ' ' . $unit;
        } else {
            return $min . ' ' . $unit;
        }
    }

    private function calculatePh($cropData, $selectedPlot) {
        $latestSoil = $selectedPlot->latestSoil;

        // check if there is no soil record at all
        if (!$latestSoil || is_null($latestSoil->ph)) {
            return ['message' => 'No soil record available', 'tag' => null];
        }

        $min = $cropData->req_ph_min;
        $max = $cropData->req_ph_max;

        if ($latestSoil->ph < $min) {
            return ['message' => 'pH must be increased to ' . $min . ' - ' . $max, 'tag' => 'increase_ph'];
        } elseif ($latestSoil->ph > $max) {
            return ['message' => 'pH must be decreased to ' . $min . ' - ' . $max, 'tag' => 'decrease_ph'];
        } else {
            return ['message' => 'pH is currently ideal', 'tag' => null];
        }
    }



    private function calculateNPK($cropData, $selectedPlot) {
        $latestSoil = $selectedPlot->latestSoil;

        // check if no soil record at all
        if (!$latestSoil) {
            return ['message' => 'No soil record available', 'prioritize' => []];
        }

        // check if any of the soil health values are null
        if (collect([
            $latestSoil->nitrogen,
            $latestSoil->phosphorus,
            $latestSoil->potassium,
        ])->contains(null)) {
            return ['message' => 'Not enough soil health data', 'prioritize' => []];
        }

        // user's soil nutrients
        $userN = $latestSoil->nitrogen;
        $userP = $latestSoil->phosphorus;
        $userK = $latestSoil->potassium;

        // ideal crop NPK ratios
        $cropN = $cropData->req_n;
        $cropP = $cropData->req_p;
        $cropK = $cropData->req_k;

        // normalize the user's soil NPK values
        $minSoilNPK = min($userN, $userP, $userK);
        $normalizedUserN = $userN / $minSoilNPK;
        $normalizedUserP = $userP / $minSoilNPK;
        $normalizedUserK = $userK / $minSoilNPK;

        // normalize the crop NPK ratios (smallest value becomes the base)
        $minCropNPK = min(array_filter([$cropN, $cropP, $cropK], fn($value) => $value > 0));
        $normalizedCropN = $cropN / $minCropNPK;
        $normalizedCropP = $cropP / $minCropNPK;
        $normalizedCropK = $cropK / $minCropNPK;

        $prioritize = [];

        if ($normalizedUserN < $normalizedCropN) {
            $prioritize[] = 'nitrogen';
        }
        if ($normalizedUserP < $normalizedCropP) {
            $prioritize[] = 'phosphorus';
        }
        if ($normalizedUserK < $normalizedCropK) {
            $prioritize[] = 'potassium';
        }

        if (empty($prioritize)) {
            $prioritize = ['nitrogen', 'phosphorus', 'potassium'];
            $message = 'Give a balanced amount of NPK';
        } else {
            $message = 'Focus more on ' . implode(' and ', array_map('ucfirst', $prioritize));
        }

        return ['message' => $message, 'prioritize' => $prioritize];
    }

    private function getFertilizer(array $tags, $plotSize) {

        if (empty($tags)) return 'No additional fertilization needed.';

        // Start query with fertilizers
        $query = \App\Models\FertilizerData::query();

        // Ensure all given tags are TRUE
        foreach ($tags as $tag) {
            $query->where($tag, true);
        }

        // Ensure all other boolean columns are FALSE (so we don’t get extra nutrients)
        $booleanColumns = ['nitrogen', 'phosphorus', 'potassium', 'increase_ph', 'decrease_ph'];
        foreach ($booleanColumns as $col) {
            if (!in_array($col, $tags)) {
                $query->where($col, false);
            }
        }

        // Fetch the first matching fertilizer
        $fertilizer = $query->first();

        if (!$fertilizer) return 'No suitable fertilizer found.';

        // Calculate required amount for the plot size
        $amount = ($fertilizer->per_hectare_min == $fertilizer->per_hectare_max)
            ? ($fertilizer->per_hectare_min * $plotSize) . " {$fertilizer->per_hectare_unit}"
            : ($fertilizer->per_hectare_min * $plotSize) . " to " . ($fertilizer->per_hectare_max * $plotSize) . " {$fertilizer->per_hectare_unit}";

        return "You need $amount of " . trim($fertilizer->fertilizer_name, '"');

    }



    public function getCropInformation($crop_name, $selectedPlot, $recommendedBySoilHealth = false) {
        // plot information
        $plotSize = $selectedPlot->hectare;
        $soilType = $selectedPlot->soil_type;
        $city = $selectedPlot->city;
        $climate = CityClimate::where('municipality', $city)->firstOrFail()->climate;
        $climateColumn = 'climate_' . $climate;

        // find the crop in CropData
        $cropData = CropData::where('crop_name', $crop_name)->first();
        $idealMonths = json_decode($cropData->$climateColumn, true);

        // Reasons for recommendations
        $reasons = [];
        if ($recommendedBySoilHealth) $reasons[] = 'nutrients';
        if (in_array(now()->month, $idealMonths)) $reasons[] = 'season';

        // Get pH and NPK recommendations
        $phRecommendation = $this->calculatePh($cropData, $selectedPlot);
        $npkRecommendation = $this->calculateNPK($cropData, $selectedPlot);

        if ($phRecommendation['message'] === 'pH is currently ideal') $reasons[] = 'ph level';
        if ($this->checkIdealSoil($cropData, $soilType) === "Yes") $reasons[] = 'soil type';

        // Get fertilizer recommendations
        $phFertilizer = $this->getFertilizer([$phRecommendation['tag']], $plotSize);
        $npkFertilizer = $this->getFertilizer($npkRecommendation['prioritize'], $plotSize);

        // Get actual yield and expected yield from other farms
        $currentYear = now()->year;
        $cropYields = CropYield::where('crop', $crop_name)
            ->whereYear('planting_date', $currentYear)
            ->get();
        $totalActualYield = $cropYields->whereNotNull('actual_yield')->sum('actual_yield');

        $totalExpectedMin = 0;
        $totalExpectedMax = 0;

        foreach($cropYields->whereNull('actual_yield') as $yield) {
            $plotHectare = Plot::where('id', $yield->plot_id)->value('hectare');
            $totalExpectedMin += $cropData->yield_min * $plotHectare;
            $totalExpectedMax += $cropData->yield_max * $plotHectare;
        }

        if ($totalExpectedMin === 0) {
            $totalExpected = 'None';
            $reasons[] = 'low competition';
        } else if ($totalExpectedMin === $totalExpectedMax) {
            $totalExpected = $totalExpectedMin . ' tons';
        } else {
            $totalExpected = $totalExpectedMin . ' - ' . $totalExpectedMax . ' tons';
        }

        return [
            'crop_name' => $cropData->crop_name,
            'other_name' => $cropData->other_name,
            'ideal_soil' => $this->checkIdealSoil($cropData, $soilType),
            'seeds_needed' => $this->calculateSeeds($cropData, $plotSize),
            'density' => $this->calculateDensity($cropData, $plotSize),
            'yield' => $this->calculateYield($cropData, $plotSize),
            'maturity' => $this->calculateMaturity($cropData),
            'spacing_plant' => $this->calculateSpacingPlant($cropData),
            'spacing_row' => $this->calculateSpacingRow($cropData),
            'ideal_soonest_month' => $this->soonestIdealMonth($idealMonths),
            'ph' => $phRecommendation['message'],
            'npk' => $npkRecommendation['message'],
            'reasons' => $reasons,
            'spacing_plant_min' => $cropData->spacing_plant_min,
            'spacing_plant_max' => $cropData->spacing_plant_max,
            'spacing_row_min' => $cropData->spacing_row_min,
            'spacing_row_max' => $cropData->spacing_row_max,
            'total_actual_yield' => $totalActualYield,
            'total_expected' => $totalExpected,
            'my_expected_max' => $cropData->yield_max * $plotSize,
            'ph_fertilizer' => $phFertilizer,
            'npk_fertilizer' => $npkFertilizer,
        ];
    }

}
