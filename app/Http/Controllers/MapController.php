<?php

namespace App\Http\Controllers;

use App\Models\CropData;
use App\Models\Plot;
use Carbon\Carbon;
use App\Services\CropRecommendationService;
use Illuminate\Http\Request;

class MapController extends Controller
{

    public function index(Request $request)
    {
        $cropNames = CropData::all();

        // Start by getting all public plots and join with the ratings table
        $query = Plot::where('public', 1)
            ->leftJoin('ratings', 'plots.id', '=', 'ratings.plot_id')
            ->with('user')
            ->select('plots.*', \DB::raw('COALESCE(AVG(ratings.rating), 0) as rating_avg')) // Handle NULL ratings with COALESCE
            ->groupBy('plots.id'); // Group by plot ID to aggregate ratings

        // Filter by crops if selected
        if ($request->has('crops')) {
            $crops = $request->input('crops');
            $cropsArray = explode(',', $crops); // Convert comma-separated string to array

            // Filter only plots that have the selected crops in their crop yield
            $query->whereHas('cropyield', function ($query) use ($cropsArray) {
                $query->whereIn('crop', $cropsArray);
            });
        }

        // Filter by rating if selected
        if ($request->has('rating')) {
            $rating = (int) $request->input('rating');
            $query->having('rating_avg', '>=', $rating);
        }

        // Get the filtered plots
        $plots = $query->get();

        // If the request is AJAX, return JSON data (for filtering)
        if ($request->ajax()) {
            return response()->json(['plots' => $plots]);
        }

        // returns all public plots with soil data to the view
        return view('map.index', [
            'pins' => $plots,
            'crops' => $cropNames,
            'apiKey' => config('services.mapbox.key')
        ]);
    }

    public function show(Plot $plot)
    {
        // Ensure the plot is public (public = 1)
        if ($plot->public !== 1) {
            abort(404); // If the plot is not public, return a 404 page
        }

        return view('map.show', [
            'plot' => $plot,
            'username' => $plot->user->username,
            'ratingAvg' => $plot->rating()->avg('rating'),
            'soil' => $plot->soil()->paginate(10),
            'crop_yield' => $plot->cropyield()->paginate(10),
            'apiKey' => config('services.mapbox.key')
        ]);
    }

}
