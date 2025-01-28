<?php

namespace App\Http\Controllers;

use App\Models\Plot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{

    public function index(Request $request)
    {
        // get the current logged-in user
        $user = auth()->user();

        // get the user's plots using eloquent model relationship
        $plots = $user->plot;

        // validate if user has plots
        if ($plots->isEmpty()) {
            return redirect()->route('plot.index')->with('warning', 'You must create a Plot first.');
        }

        // checks for request parameter (/index?plot_id={id}), then fall back is first plot in database
        $selectedPlotId = $request->input('plot_id', $plots->first()->id);
        $selectedPlot = Plot::where('id', $selectedPlotId)->firstOrFail();

        return view('weather.index', [
            'plots' => $plots,
            'longitude' => $selectedPlot->longitude,
            'latitude' => $selectedPlot->latitude,
            'request' => $request,
        ]);
    }

    public function getWeather(Request $request)
    {

        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $apiKey = config('services.openweather.key');

        $weatherResponse = Http::get("https://api.openweathermap.org/data/2.5/weather", [
            'lat' => $latitude,
            'lon' => $longitude,
            'appid' => $apiKey,
        ]);

        $airPollutionResponse = Http::get("http://api.openweathermap.org/data/2.5/air_pollution", [
            'lat' => $latitude,
            'lon' => $longitude,
            'appid' => $apiKey,
        ]);

        $forecastResponse = Http::get("http://api.openweathermap.org/data/2.5/forecast", [
            'lat' => $latitude,
            'lon' => $longitude,
            'appid' => $apiKey,
        ]);

        // Check if both requests are successful
        if ($weatherResponse->successful() && $airPollutionResponse->successful()) {
            return response()->json([
                'weather' => $weatherResponse->json(),
                'air_pollution' => $airPollutionResponse->json(),
                'forecast' => $forecastResponse->json(),
            ]);
        } else {
            return response()->json(['error' => 'Failed to retrieve weather data.'], 500);
        }
    }


}
