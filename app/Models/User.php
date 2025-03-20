<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Log;


class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function rating()
    {
        return $this->hasMany(Rating::class);
    }

    public function plot()
    {
        return $this->hasMany(Plot::class);
    }

    public function scopeHasPublicPlot($query)
    {
        return $query->whereHas('plot', function ($query) {
            $query->where('public', 1);
        });
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($user) {

            // delete all plots (ratings it received, its crop yield record, soil record, then the plot itself)
            $user->plot()->each(function ($plot) {
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

                try {
                    $plot->delete();
                    Log::info("Deleted plot ID {$plot->id}");
                } catch (\Exception $e) {
                    Log::error("Failed to delete plot ID {$plot->id}: " . $e->getMessage());
                }
            });


            // delete ratings given by this user
            $user->rating()->delete();
        });
    }

}
