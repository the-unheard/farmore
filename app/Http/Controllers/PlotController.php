<?php

namespace App\Http\Controllers;

use App\Models\CityClimate;
use App\Models\Plot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PlotController extends Controller
{
    public function index()
    {
        return view('plot.index', [
            'plots' => Plot::where('user_id', auth()->id())->orderBy('created_at', 'asc')->get()
        ]);
    }

    public function create()
    {
        return view('plot.create');
    }

    public function store()
    {
        $validatedData = $this->validateInput();

        try {
            $cityData = $this->getCityClimate($validatedData['latitude'], $validatedData['longitude']);
            $validatedData['city'] = $cityData['city'];
            $validatedData['climate'] = $cityData['climate'];
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        $validatedData['coordinates'] = json_encode($validatedData['coordinates']);

        Plot::create($validatedData);
        return redirect('/plot')->with('success', 'Plot created successfully!');
    }

    public function show(Plot $plot)
    {
        $this->authorizeOwner($plot);
        return view('plot.show', [
            'plot' => $plot,
            'apiKey' => config('services.mapbox.key')
        ]);
    }

    public function edit(Plot $plot)
    {
        $this->authorizeOwner($plot);
        return view('plot.edit', [
            'plot' => $plot,
            'apiKey' => config('services.mapbox.key')
        ]);
    }

    public function update(Plot $plot)
    {
        $this->authorizeOwner($plot);
        $validatedData = $this->validateInput();
        $plot->update($validatedData);
        return redirect('/plot/' . $plot->id);
    }

    public function destroy(Plot $plot)
    {
        $this->authorizeOwner($plot);
        $plot->delete();
        return redirect('/plot');
    }

    private function validateInput(): array
    {
        $data = request()->all();

        // Decode the coordinates field if it's a JSON string
        if (is_string($data['coordinates'])) {
            $data['coordinates'] = json_decode($data['coordinates'], true);
        }

        // Generate plot_token
        if (empty($data['plot_token'])) {
            do {
                $data['plot_token'] = Str::random(16);
            } while (\App\Models\Plot::where('plot_token', $data['plot_token'])->exists()); // Ensure uniqueness
        }

        // Now validate the $data array
        return validator($data, [
            'user_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:35'],
            'soil_type' => ['required', 'string'],
            'description' => ['nullable', 'string', 'max:150'],
            'longitude' => ['required', 'numeric'],
            'latitude' => ['required', 'numeric'],
            'hectare' => ['required', 'numeric'],
            'public' => ['required', 'boolean'],

            // Coordinates field validation
            'coordinates' => ['required', 'array'],
            'coordinates.*' => ['array', 'size:2'],
            'coordinates.*.*' => ['numeric'],

            // Ensure plot_token is validated
            'plot_token' => ['required', 'string', 'size:16', 'unique:plots,plot_token'],
        ])->validate(); // Use the validator with the $data array
    }

    private function authorizeOwner(Plot $plot)
    {
        if ($plot->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
    }

    private function getCityClimate($latitude, $longitude)
    {
        $apiKey = config('services.mapbox.key');

        $cityResponse = Http::get("https://api.mapbox.com/search/geocode/v6/reverse", [
            'latitude' => $latitude,
            'longitude' => $longitude,
            'access_token' => $apiKey,
        ]);

        if ($cityResponse->successful()) {
            $cityName = $cityResponse->json()['features'][0]['properties']['context']['place']['name'];
        } else {
            throw new \Exception('Failed to retrieve city data from Mapbox.');
        }

        $cityClimate = CityClimate::where('municipality', $cityName)->first();

        if (!$cityClimate) {
            throw new \Exception('City climate data not found.');
        }

        return [
            'city' => $cityName,
            'climate' => $cityClimate->climate,
        ];
    }

}
