<?php

namespace Database\Factories;

use App\Models\Plot;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rating>
 */
class RatingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        do {
            $plot = Plot::inRandomOrder()->first();
            $userId = User::inRandomOrder()->first()->id;
        } while ($plot->user_id == $userId || Rating::where('plot_id', $plot->id)->where('user_id', $userId)->exists());

        return [
            'user_id' => $userId, // Generated user_id
            'plot_id' => $plot->id, // Ensured map_id that doesn't belong to the same user_id
            'rating' => $this->faker->numberBetween(1, 5), // Random rating between 1 and 5
        ];
    }
}
