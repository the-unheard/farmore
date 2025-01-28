<?php

use App\Models\User;
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
        Schema::create('crop_recommendations', function (Blueprint $table) {
            $table->id();
            $table->float('nitrogen');
            $table->float('phosphorus');
            $table->float('potassium');
            $table->float('temperature');
            $table->float('humidity');
            $table->float('ph');
            $table->string('recommended_crop');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crop_recommendations');
    }
};
