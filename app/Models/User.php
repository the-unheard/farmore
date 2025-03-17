<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

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
            // delete all plots first
            $user->plots()->each(function ($plot) {
                $plot->rating()->delete();
                $plot->cropyield()->delete();
                $plot->soil()->delete();
                $plot->delete();
            });

            $user->rating()->delete();
        });
    }

}
