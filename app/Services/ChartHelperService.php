<?php

namespace App\Services;

use App\Models\CityClimate;
use App\Models\CropData;
use App\Models\CropRecommendation;
use App\Models\CropYield;
use App\Models\Plot;
use Illuminate\Support\Facades\DB;
use Phpml\Classification\NaiveBayes;

class ChartHelperService
{
    public function mostPlantedCrops($plotId) {
        // Get total crop plantings
        $totalPlantings = CropYield::where('plot_id', $plotId)->count();

        // Get all crops sorted by planting frequency
        $crops = CropYield::where('plot_id', $plotId)
            ->select('crop', DB::raw('COUNT(*) as crop_count'))
            ->groupBy('crop')
            ->orderBy('crop_count', 'desc')
            ->get();

        // If there are fewer than 5 crops, return all of them
        if ($crops->count() <= 5) {
            return $crops;
        }

        // Find the threshold
        $threshold = $crops[5]->crop_count;

        // Separate crops into top and others
        $topCrops = $crops->filter(fn($crop) => $crop->crop_count > $threshold);
        $othersCount = $crops->filter(fn($crop) => $crop->crop_count <= $threshold)->sum('crop_count');

        // Add "Others" only if applicable
        if ($othersCount > 0) {
            $topCrops->push([
                'crop' => 'Others',
                'crop_count' => $othersCount,
            ]);
        }

        return $topCrops;
    }

    public function getCropYieldsWithPerformance($plotId) {
        return CropYield::where('plot_id', $plotId)
            ->orderBy('planting_date', 'desc')
            ->get()
            ->map(function ($yield) use ($plotId) {
                $cropData = CropData::where('crop_name', $yield->crop)->first();

                if (!$cropData) {
                    return null; // Skip if crop data is missing
                }

                $plotSize = Plot::where('id', $plotId)->value('hectare');

                $expectedMinYield = $cropData->yield_min * $plotSize;
                $expectedMaxYield = $cropData->yield_max * $plotSize;

                $performance = $expectedMaxYield > 0 ? ($yield->actual_yield / $expectedMaxYield) * 100 : 0;

                return [
                    'id' => $yield->id,
                    'crop_name' => $yield->crop,
                    'actual_yield' => $yield->actual_yield,
                    'expected_min' => $expectedMinYield,
                    'expected_max' => $expectedMaxYield,
                    'performance' => round($performance, 2),
                    'planting_date' => $yield->planting_date,
                    'harvest_date' => $yield->harvest_date,
                ];
            })->filter(); // Remove null values
    }



}
