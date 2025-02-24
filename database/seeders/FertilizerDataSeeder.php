<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FertilizerData;
use Illuminate\Support\Facades\File;

class FertilizerDataSeeder extends Seeder
{
    public function run(): void
    {
        $file = storage_path('app/csv/fertilizer_data.csv');
        $data = array_map('str_getcsv', file($file));

        unset($data[0]);

        foreach ($data as $row) {
            FertilizerData::create([
                'fertilizer_name' => $row[0] ?? null,
                'per_hectare_min' => (int) $row[1] ?? 0,
                'per_hectare_max' => (int) $row[2] ?? 0,
                'per_hectare_unit' => $row[3] ?? 'kg',
                'nitrogen' => filter_var($row[4], FILTER_VALIDATE_BOOLEAN),
                'phosphorus' => filter_var($row[5], FILTER_VALIDATE_BOOLEAN),
                'potassium' => filter_var($row[6], FILTER_VALIDATE_BOOLEAN),
                'increase_ph' => filter_var($row[7], FILTER_VALIDATE_BOOLEAN),
                'decrease_ph' => filter_var($row[8], FILTER_VALIDATE_BOOLEAN),
            ]);
        }

    }
}
