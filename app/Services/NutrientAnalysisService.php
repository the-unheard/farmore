<?php

namespace App\Services;

use App\Models\CropData;
use App\Models\Soil;
use Carbon\Carbon;
use Phpml\Regression\LeastSquares;

class NutrientAnalysisService
{
    // returns NPKpH value and Too Low/Good/High message
    public function getAllNutrientAnalysis($mostRecentSoilHealth, $mostRecentCropPlanted) {
        return [
            'nitrogen' => optional($mostRecentSoilHealth)->nitrogen ?? 'No Data',
            'phosphorus' => optional($mostRecentSoilHealth)->phosphorus ?? 'No Data',
            'potassium' => optional($mostRecentSoilHealth)->potassium ?? 'No Data',
            'ph' => optional($mostRecentSoilHealth)->ph ?? 'No Data',
            'nitrogenMessage' => $this->nitrogenAnalysis(optional($mostRecentSoilHealth)->nitrogen ?? 'No Data'),
            'phosphorusMessage' => $this->phosphorusAnalysis(optional($mostRecentSoilHealth)->phosphorus ?? 'No Data'),
            'potassiumMessage' => $this->potassiumAnalysis(optional($mostRecentSoilHealth)->potassium ?? 'No Data'),
            'phMessage' => $this->phAnalysis(optional($mostRecentSoilHealth)->ph ?? 'No Data', $mostRecentCropPlanted),
        ];
    }

    public function nitrogenAnalysis($nitrogen): string
    {
        return $this->analyzeNutrient($nitrogen, 10, 50);
    }

    public function phosphorusAnalysis($phosphorus): string
    {
        return $this->analyzeNutrient($phosphorus, 15, 35);
    }

    public function potassiumAnalysis($potassium): string
    {
        return $this->analyzeNutrient($potassium, 60, 160);
    }

    public function phAnalysis($ph, $crop): string
    {
        $ideal_ph_min = 6;
        $ideal_ph_max = 7.5;

        if (!is_array($crop) || !array_key_exists('crop', $crop) || is_null($crop['crop'])) {
            $ph_min = $ideal_ph_min;
            $ph_max = $ideal_ph_max;
        } else {
            $cropData = CropData::where('crop_name', $crop['crop'])->first();

            $ph_min = optional($cropData)->req_ph_min ?? $ideal_ph_min;
            $ph_max = optional($cropData)->req_ph_max ?? $ideal_ph_max;
        }

        return $this->analyzeNutrient($ph, $ph_min, $ph_max);
    }

    public function analyzeNutrient($nutrient, $min, $max): string {
        if ($nutrient === 'No Data') {
            return '';
        }

        if ($nutrient < $min) {
            return 'Too Low';
        } elseif ($nutrient > $max) {
            return 'Too High';
        }

        return 'Good';
    }

    // predict all nutrients
    public function predictAllNutrientLevel($selectedPlot): array
    {
        return [
            'nitrogen' => $this->predictNutrientLevel('nitrogen', $selectedPlot),
            'phosphorus' => $this->predictNutrientLevel('phosphorus', $selectedPlot),
            'potassium' => $this->predictNutrientLevel('potassium', $selectedPlot),
            'temperature' => $this->predictNutrientLevel('temperature', $selectedPlot),
            'humidity' => $this->predictNutrientLevel('humidity', $selectedPlot),
            'ph' => $this->predictNutrientLevel('ph', $selectedPlot),
        ];
    }

    // predict each nutrient
    public function predictNutrientLevel($nutrientColumn, $selectedPlot): ?float
    {
        // Fetch the most recent 5 days of records (or less) for the selected plot,
        // ensuring only the latest record per day is included, ordered by record date.
        $recentData = Soil::where('plot_id', $selectedPlot)
            ->whereNotNull($nutrientColumn)
            ->whereRaw('record_date IN (SELECT MAX(record_date) FROM soils WHERE plot_id = ? GROUP BY DATE(record_date))', [$selectedPlot])
            ->orderBy('record_date', 'desc')
            ->take(5)
            ->get(['record_date', $nutrientColumn]);


        // If there are less than 5 records, we can't perform a regression
        if ($recentData->count() < 5) {
            return null;
        }

        // Prepare the samples and targets for regression
        $samples = [];
        $targets = [];
        $initialDate = Carbon::parse($recentData->last()->record_date); // Oldest record

        foreach ($recentData as $data) {
            $date = Carbon::parse($data->record_date);
            $daysSinceInitial = $initialDate->diffInDays($date);  // Days difference
            $samples[] = [$daysSinceInitial];  // X-axis: days since the oldest record
            $targets[] = $data->{$nutrientColumn};  // Y-axis: nutrient level
        }

        // Use simple linear regression to train and predict
        $regression = new LeastSquares();
        $regression->train($samples, $targets);

        // Predict the next value (assume we are predicting 1 day after the most recent record)
        $today = Carbon::now();
        $nextMonth = $initialDate->diffInDays($today) + 30; // days since initial date + one month

        $predictedValue = $regression->predict([$nextMonth]);
        $predictedValue = max($predictedValue, 0);

        return round($predictedValue, 2);  // Return the predicted value rounded to 2 decimals
    }

}
