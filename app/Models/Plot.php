<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plot extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function soil()
    {
        return $this->hasMany(Soil::class);
    }

    public function latestSoil()
    {
        return $this->hasOne(Soil::class)->latestOfMany();
    }

    public function latestYield()
    {
        return $this->hasOne(CropYield::class)->latestOfMany();
    }

    public function cropyield()
    {
        return $this->hasMany(CropYield::class);
    }

    public function rating()
    {
        return $this->hasMany(Rating::class);
    }
}
