<?php

namespace App\Http\Controllers;

use App\Models\Plot;
use App\Services\RecommendationService;
use Illuminate\Http\Request;

class CropRecommendationController extends Controller
{
    public function index(Request $request)
    {
        $recommendationService = new recommendationService();

        // get the current logged-in user
        $user = auth()->user();

        // get the user's plots using eloquent model relationship
        $plots = $user->plot;

        // validate if user has plots
        if ($plots->isEmpty()) {
            return redirect()->route('plot.index')->with('warning', 'You must create a Plot first.');
        }

        // checks for request parameter (/index?plot_id={id}), then fallback is first plot in database
        $selectedPlotId = $request->input('plot_id', $plots->first()->id);
        $selectedPlot = Plot::where('user_id', $user->id)->findOrFail($selectedPlotId);

        // Get latitude & longitude of the selected plot for weather API
        $latitude = $selectedPlot->latitude;
        $longitude = $selectedPlot->longitude;

        // crop recommendations
        $recommendationBySoilHealth = $recommendationService->recommendBySoilHealth($selectedPlot); // returns a single crop information (array)
        $recommendationBySeason = $recommendationService->recommendBySeason($selectedPlot)->toArray(); // returns an array of crops and their information (array of arrays)

        $finalRecommendations = [];

        // Add the first recommendation only if it's an array
        if (is_array($recommendationBySoilHealth)) {
            $finalRecommendations[] = $recommendationBySoilHealth;
            $soilHealthCropName = $recommendationBySoilHealth['crop_name']; // store the crop name for checking
        } else {
            $soilHealthCropName = null;
        }

        // add seasonal crops only if they are not already in the final recommendations
        if (is_array($recommendationBySeason)) {
            foreach ($recommendationBySeason as $crop) {
                if (is_array($crop) && $crop['crop_name'] !== $soilHealthCropName) {
                    $finalRecommendations[] = $crop;
                }
            }
        }

        return view('crop-recommendation.index', [
            'plots' => $plots, // for displaying plot names on the dropdown
            'selectedPlotId' => $selectedPlotId, // for the Add New crop yield record button
            'finalRecommendations' => $finalRecommendations,
            'plotCoordinates' => $selectedPlot->coordinates,
            'plotHectare' => $selectedPlot->hectare,
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]);
    }

}
