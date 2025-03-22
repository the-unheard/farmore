<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

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
        return $this->hasOne(Soil::class)->latestOfMany('record_date');
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

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($plot) {
            try {
                $plot->rating()->delete();
                Log::info("Deleted rating for plot ID {$plot->id}");
            } catch (\Exception $e) {
                Log::error("Failed to delete rating for plot ID {$plot->id}: " . $e->getMessage());
            }

            try {
                $plot->cropyield()->delete();
                Log::info("Deleted crop yield for plot ID {$plot->id}");
            } catch (\Exception $e) {
                Log::error("Failed to delete crop yield for plot ID {$plot->id}: " . $e->getMessage());
            }

            try {
                $plot->soil()->delete();
                Log::info("Deleted soil record for plot ID {$plot->id}");
            } catch (\Exception $e) {
                Log::error("Failed to delete soil record for plot ID {$plot->id}: " . $e->getMessage());
            }
        });
    }

}
