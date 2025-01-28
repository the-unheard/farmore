<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Soil extends Model
{
    use HasFactory;

    protected $guarded = [];

    // ensures record_date is treated as date and not a string
    protected $casts = [
        'record_date' => 'date'
    ];

    public function plot()
    {
        return $this->belongsTo(Plot::class);
    }

}
