<?php

namespace Database\Factories;

use App\Models\CropData;
use App\Models\Plot;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\cropyield>
 */
class CropYieldFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Get a random plot and its hectare size
        $plot = Plot::inRandomOrder()->first();
        $plotHectare = $plot->hectare;

        // Get a random crop and its expected yield range
        $crop = CropData::inRandomOrder()->first();
        $yieldMin = $crop->yield_min * $plotHectare;
        $yieldMax = $crop->yield_max * $plotHectare;

        return [
            'plot_id' => $plot->id,
            'crop' => $crop->crop_name,
            'actual_yield' => $this->faker->optional(0.95)->numberBetween($yieldMin, $yieldMax), // 5% chance to be NULL
            'planting_date' => $plantingDate = $this->faker->dateTimeBetween('2025-01-01', '2025-01-31'),
            'harvest_date' => $this->faker->dateTimeBetween($plantingDate, '2025-03-31'),
        ];
    }

}
