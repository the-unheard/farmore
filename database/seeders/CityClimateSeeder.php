<?php

namespace Database\Seeders;

use App\Models\CityClimate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CityClimateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file = storage_path('app/csv/city_climates.csv');
        $data = array_map('str_getcsv', file($file));

        unset($data[0]);

        foreach ($data as $row) {
            CityClimate::create([
                'municipality' => trim($row[0], '"'),
                'province' => trim($row[1], '"'),
                'climate' => $row[2],
            ]);
        }
    }
}
