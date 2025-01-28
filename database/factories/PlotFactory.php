<?php

namespace Database\Factories;

use App\Models\CityClimate;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Plot>
 */
class PlotFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Generate the base longitude and latitude
        $longitude = $this->faker->randomFloat(6, 120.770954, 121.093162);
        $latitude = $this->faker->randomFloat(6, 14.100072, 14.323060);

        // List of soil types
        $soilTypes = [
            'Sand',
            'Loamy sand',
            'Sandy loam',
            'Sandy clay loam',
            'Loam',
            'Silt loam',
            'Silt',
            'Silt clay loam',
            'Clay',
            'Clay loam',
            'Sandy clay',
            'Silty clay'
        ];

        return [
            'user_id' => User::inRandomOrder()->first()->id, // Get a random existing user ID
            'name' => ucfirst($this->faker->word) . ' Farm', // Random name for the farm
            'description' => $this->faker->sentence, // Random description
            'soil_type' => $this->faker->randomElement($soilTypes),
            'longitude' => $longitude,
            'latitude' => $latitude,
            'coordinates' => json_encode([
                [$longitude + 0.0005, $latitude + 0.0005], // First long/lat
                [$longitude - 0.0005, $latitude + 0.0005], // Second long/lat
                [$longitude - 0.0005, $latitude - 0.0005], // Third long/lat
                [$longitude + 0.0005, $latitude - 0.0005], // Fourth long/lat
                [$longitude + 0.0005, $latitude + 0.0005]  // Fifth long/lat (same as first)
            ]),
            'city' => CityClimate::inRandomOrder()->first()->municipality,
            'hectare' => $this->faker->numberBetween(1, 5),
            'public' => 1,
        ];
    }

}
