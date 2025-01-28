<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CropRecommendation extends Model
{
    use HasFactory;

    protected $table = 'crop_recommendations';

    protected $guarded = [];
}
