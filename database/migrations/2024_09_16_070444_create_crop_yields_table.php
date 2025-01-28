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
        Schema::create('crop_yields', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Plot::class)->constrained()->cascadeOnDelete();
            $table->string('crop');
            $table->float('actual_yield')->nullable();
            $table->date('planting_date')->nullable();
            $table->date('harvest_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crop_yields');
    }
};
