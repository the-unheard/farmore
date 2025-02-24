<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fertilizer_data', function (Blueprint $table) {
            $table->id();
            $table->string('fertilizer_name');
            $table->integer('per_hectare_min');
            $table->integer('per_hectare_max');
            $table->string('per_hectare_unit');
            $table->boolean('nitrogen')->default(false);
            $table->boolean('phosphorus')->default(false);
            $table->boolean('potassium')->default(false);
            $table->boolean('increase_ph')->default(false);
            $table->boolean('decrease_ph')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fertilizer_data');
    }
};
