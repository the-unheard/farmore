<?php

use App\Http\Controllers\CropRecommendationController;
use App\Http\Controllers\CropYieldController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\PlotController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\SoilController;
use App\Http\Controllers\WeatherController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

// dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth');

// plot, soil, crop yield
Route::resource('plot', PlotController::class)->middleware('auth');
Route::resource('soil', SoilController::class)->middleware('auth');
Route::resource('crop-yield', CropYieldController::class)->middleware('auth');
Route::get('/crop-yield-estimates', [CropYieldController::class, 'getCropYieldEstimates']);

// map
Route::get('/map', [MapController::class, 'index'])->middleware('auth')->middleware('auth');
Route::get('/map/{plot}', [MapController::class, 'show'])->middleware('auth')->middleware('auth');
Route::post('/map/{plot}/rate', [RatingController::class, 'store'])->middleware('auth')->middleware('auth');

// weather
Route::get('/weather', [WeatherController::class, 'index'])->middleware('auth')->middleware('auth');
Route::get('/weather-data', [WeatherController::class, 'getWeather'])->middleware('auth')->middleware('auth');

// authentication
Route::get('/auth/register', [RegisteredUserController::class, 'create']);
Route::post('/auth/register', [RegisteredUserController::class, 'store']);
Route::get('/auth/login', [SessionController::class, 'create'])->name('login');
Route::post('/auth/login', [SessionController::class, 'store']);
Route::post('/auth/logout', [SessionController::class, 'destroy']);

// crop recommendation
Route::resource('crop-recommendation', CropRecommendationController::class)->only(['index', 'store'])->middleware('auth');


// soil health - complete
//Route::get('/soil', [SoilController::class, 'index']);
//Route::get('/soil/create', [SoilController::class, 'create']);
//Route::post('/soil', [SoilController::class, 'store']);
//Route::get('/soil/{soil}', [SoilController::class, 'show']);
//Route::get('/soil/{soil}/edit', [SoilController::class, 'edit']);
//Route::patch('/soil/{soil}', [SoilController::class, 'update']);
//Route::delete('/soil/{soil}', [SoilController::class, 'destroy']);
