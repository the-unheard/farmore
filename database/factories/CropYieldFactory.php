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
        return [
            'plot_id' => Plot::inRandomOrder()->first()->id,
            'crop' => CropData::inRandomOrder()->first()->crop_name,
            'actual_yield' => $this->faker->optional(0.25)->numberBetween(100, 1000),
            'planting_date' => $plantingDate = $this->faker->dateTimeBetween('2025-01-01', '2025-01-31'),
            'harvest_date' => $this->faker->dateTimeBetween($plantingDate, '2025-03-31'),
        ];
    }
}
