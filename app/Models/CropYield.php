<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CropYield extends Model
{
    use HasFactory;

    protected $table = 'crop_yields';

    protected $guarded = [];

    // ensures record_date is treated as date and not a string
    protected $casts = [
        'planting_date' => 'date',
        'harvest_date' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plot()
    {
        return $this->belongsTo(Plot::class);
    }
}
