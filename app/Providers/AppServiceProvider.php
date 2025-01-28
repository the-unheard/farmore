<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::if('authId', function ($userId) {
           return auth()->id() === $userId;
        });

        Blade::if('authNotId', function ($userId) {
            return auth()->id() !== $userId;
        });
    }
}
