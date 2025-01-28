<?php

namespace App\Services;

use App\Models\CityClimate;
use App\Models\CropRecommendation;
use Phpml\Classification\NaiveBayes;

class CropRecommendationService
{
    /**
     * Get recommended crops for multiple soil records at once
     *
     * @param array $soilRecords
     * @return array
     */
    public function getRecommendedCrops(array $soilRecords): array
    {
        // get the training data from the CropRecommendation table
        $cropRecommendations = CropRecommendation::all();

        // prepare the samples and labels arrays
        $samples = [];
        $labels = [];

        foreach ($cropRecommendations as $recommendation) {
            // add the soil healths as a samples
            $samples[] = [
                $recommendation->nitrogen,
                $recommendation->phosphorus,
                $recommendation->potassium,
                $recommendation->temperature,
                $recommendation->humidity,
                $recommendation->ph
            ];

            // add the recommended crops as the label
            $labels[] = $recommendation->recommended_crop;
        }

        // Prepare the input data array (all soil records)
        $inputData = [];
        foreach ($soilRecords as $soil) {
            $inputData[] = [
                $soil['nitrogen'],
                $soil['phosphorus'],
                $soil['potassium'],
                $soil['temperature'],
                $soil['humidity'],
                $soil['ph']
            ];
        }

        // Run prediction for all input samples in one go
        return $this->predictWithNaiveBayes($samples, $labels, $inputData);
    }

    private function predictWithNaiveBayes($samples, $labels, $inputData)
    {
        // Initialize the Naive Bayes classifier
        $classifier = new NaiveBayes();

        // Train the classifier with the samples and labels
        $classifier->train($samples, $labels);

        // Predict the recommended crops for the array of input data
        $predictions = $classifier->predict($inputData);

        // Ensure the predictions are returned as an array
        if (!is_array($predictions)) {
            return [$predictions];  // Wrap single prediction in an array
        }

        return $predictions;
    }

    // ideal soil boolean
    public function isIdealSoil($crop, $selectedPlot): bool
    {

        $plotSoilType = $selectedPlot->soil_type;
        $idealSoilType = json_decode($crop->soil_types, true);
        return in_array($plotSoilType, $idealSoilType);

    }

    // ideal month boolean
    public function isIdealMonth($crop, $selectedPlot): bool {

        $city = $selectedPlot->city;
        $climate = CityClimate::where('municipality', $city)->firstOrFail()->climate;
        $climateColumn = 'climate_' . $climate;
        $allowedMonths = json_decode($crop->$climateColumn, true);
        $currentMonth = now()->month;
        return in_array($currentMonth, $allowedMonths);

    }

    // ideal nitrogen boolean
    public function isIdealN($crop, $plotSoilHealth): bool {

        $normalizedNPK = $this->normalizedNPK($crop, $plotSoilHealth);
        return $normalizedNPK['normalizedUserN'] >= $normalizedNPK['normalizedCropN'];

    }

    // ideal phosphorus boolean
    public function isIdealP($crop, $plotSoilHealth): bool {

        $normalizedNPK = $this->normalizedNPK($crop, $plotSoilHealth);
        return $normalizedNPK['normalizedUserP'] >= $normalizedNPK['normalizedCropP'];

    }

    // ideal potassium boolean
    public function isIdealK($crop, $plotSoilHealth): bool {

        $normalizedNPK = $this->normalizedNPK($crop, $plotSoilHealth);
        return $normalizedNPK['normalizedUserK'] >= $normalizedNPK['normalizedCropK'];

    }

    // ideal ph
    public function isIdealPh($crop, $plotSoilHealth): bool {

        return $plotSoilHealth->ph >= $crop->req_ph_min && $plotSoilHealth->ph <= $crop->req_ph_max;

    }

    public function normalizedNPK($crop, $plotSoilHealth) {

        $soil = $plotSoilHealth;

        // user's soil nutrients
        $userN = $soil->nitrogen;
        $userP = $soil->phosphorus;
        $userK = $soil->potassium;

        // ideal crop NPK ratios
        $cropN = $crop->req_n;
        $cropP = $crop->req_p;
        $cropK = $crop->req_k;

        // normalize the crop NPK ratios (smallest value becomes the base)
        $minCropNPK = min(array_filter([$cropN, $cropP, $cropK], fn($value) => $value > 0));
        $normalizedCropN = $cropN / $minCropNPK;
        $normalizedCropP = $cropP / $minCropNPK;
        $normalizedCropK = $cropK / $minCropNPK;

        // normalize the user's soil NPK values
        $minSoilNPK = min($userN, $userP, $userK);
        $normalizedUserN = $userN / $minSoilNPK;
        $normalizedUserP = $userP / $minSoilNPK;
        $normalizedUserK = $userK / $minSoilNPK;

        return [
            'normalizedCropN' => $normalizedCropN,
            'normalizedCropP' => $normalizedCropP,
            'normalizedCropK' => $normalizedCropK,
            'normalizedUserN' => $normalizedUserN,
            'normalizedUserP' => $normalizedUserP,
            'normalizedUserK' => $normalizedUserK,
        ];

    }

}
