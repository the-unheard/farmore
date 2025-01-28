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
        Schema::create('soils', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Plot::class)->constrained()->cascadeOnDelete();
            $table->float('nitrogen');
            $table->float('phosphorus');
            $table->float('potassium');
            $table->float('temperature')->nullable();
            $table->float('humidity')->nullable();
            $table->float('ph')->nullable();
            $table->date('record_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soils');
    }
};
