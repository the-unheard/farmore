<?php

namespace Database\Seeders;

use App\Models\CropYield;
use App\Models\Plot;
use App\Models\Rating;
use App\Models\Soil;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Factories\RatingFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
//        User::factory(10)->create();
//        Plot::factory(100)->create();
//        Soil::factory(500)->create();
//        CropYield::factory(500)->create();
//        Rating::factory(1000)->create();
        $this->call([
            CropRecommendationSeeder::class,
            CityClimateSeeder::class,
            CropDataSeeder::class,
            UserSeeder::class,
            FertilizerDataSeeder::class,
        ]);
    }
}
