<?php

namespace Database\Seeders;

use App\Models\CropYield;
use App\Models\Plot;
use App\Models\Rating;
use App\Models\Soil;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory(10)->create();
        Plot::factory(50)->create();
        Soil::factory(200)->create();
        CropYield::factory(200)->create();
        Rating::factory(400)->create();

        // run this as: php artisan db:seed --class=CustomSeeder
    }
}
