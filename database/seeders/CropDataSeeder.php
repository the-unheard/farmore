<?php

namespace Database\Seeders;

use App\Models\CropData;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CropDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file = storage_path('app/csv/crop_data.csv');
        $data = array_map('str_getcsv', file($file));

        unset($data[0]);

        foreach ($data as $row) {
            CropData::create([
                'crop_name' => trim($row[0], '"'),
                'other_name' => trim($row[1], '"'),
                'req_n' => $row[2],
                'req_p' => $row[3],
                'req_k' => $row[4],
                'req_ph_min' => $row[5],
                'req_ph_max' => $row[6],
                'seeds_needed_min' => $row[7],
                'seeds_needed_max' => $row[8],
                'seeds_unit' => trim($row[9], '"'),
                'soil_types' => $row[10],
                'density_min' => $row[11],
                'density_max' => $row[12],
                'yield_min' => $row[13],
                'yield_max' => $row[14],
                'maturity_min' => $row[15],
                'maturity_max' => $row[16],
                'maturity_unit' => $row[17],
                'maturity_type' => $row[18],
                'spacing_plant_min' => $row[19],
                'spacing_plant_max' => $row[20],
                'spacing_row_min' => $row[21],
                'spacing_row_max' => $row[22],
                'climate_1' => $row[23],
                'climate_2' => $row[24],
                'climate_3' => $row[25],
                'climate_4' => $row[26],
            ]);
        }
    }
}
