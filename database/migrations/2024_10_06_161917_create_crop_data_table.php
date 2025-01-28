<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('crop_data', function (Blueprint $table) {
            $table->id();
            $table->string('crop_name');
            $table->string('other_name');
            $table->float('req_n');
            $table->float('req_p');
            $table->float('req_k');
            $table->float('req_ph_min');
            $table->float('req_ph_max');
            $table->float('seeds_needed_min');
            $table->float('seeds_needed_max');
            $table->string('seeds_unit');
            $table->json('soil_types');
            $table->float('density_min');
            $table->float('density_max');
            $table->float('yield_min');
            $table->float('yield_max');
            $table->float('maturity_min');
            $table->float('maturity_max');
            $table->string('maturity_unit');
            $table->string('maturity_type');
            $table->float('spacing_plant_min');
            $table->float('spacing_plant_max');
            $table->float('spacing_row_min');
            $table->float('spacing_row_max')->nullable();
            $table->json('climate_1');
            $table->json('climate_2');
            $table->json('climate_3');
            $table->json('climate_4');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crop_data');
    }
};
