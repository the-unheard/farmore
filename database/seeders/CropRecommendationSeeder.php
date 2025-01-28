<?php

namespace Database\Seeders;

use App\Models\CropRecommendation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CropRecommendationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file = storage_path('app/csv/crop_recommendation.csv');
        $data = array_map('str_getcsv', file($file));

        unset($data[0]);

        foreach ($data as $row) {
            CropRecommendation::create([
                'nitrogen' => $row[0],
                'phosphorus' => $row[1],
                'potassium' => $row[2],
                'temperature' => $row[3],
                'humidity' => $row[4],
                'ph' => $row[5],
                'recommended_crop' => trim($row[6], '"'),
            ]);
        }
    }
}
