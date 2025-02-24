<?php

namespace Database\Factories;

use App\Models\Plot;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\soil>
 */
class SoilFactory extends Factory
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
            'nitrogen' => $this->faker->numberBetween(1, 140),
            'phosphorus' => $this->faker->numberBetween(1, 145),
            'potassium' => $this->faker->numberBetween(1, 205),
            'temperature' => $this->faker->randomFloat(2,1, 50),
            'humidity' => $this->faker->randomFloat(2,10, 100),
            'ph' => $this->faker->randomFloat(2,1, 10),
            'record_date' => $this->faker->dateTimeBetween('2023-01-01', '2024-09-31')
        ];
    }
}
