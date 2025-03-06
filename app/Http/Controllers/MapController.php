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

        // Fetch the latest soil record
        $latestSoil = $plot->latestSoil;

        // Prepare soil data
        $soilData = $latestSoil
            ? [
                'nitrogen' => $latestSoil->nitrogen ?? 'Not available',
                'phosphorus' => $latestSoil->phosphorus ?? 'Not available',
                'potassium' => $latestSoil->potassium ?? 'Not available',
                'humidity' => $latestSoil->humidity ?? 'Not available',
                'temperature' => $latestSoil->temperature ?? 'Not available',
                'ph' => $latestSoil->ph ?? 'Not available',
                'record_date' => $latestSoil->created_at->format('Y-m-d') ?? 'Not available'
            ]
            : [
                'nitrogen' => 'Not available',
                'phosphorus' => 'Not available',
                'potassium' => 'Not available',
                'humidity' => 'Not available',
                'temperature' => 'Not available',
                'ph' => 'Not available',
                'record_date' => 'Not available'
            ];

        // Fetch the latest crop yield record
        $latestYield = $plot->latestYield;

        // Fetch all crop yield records for this plot
        $allYields = $plot->cropyield()->orderBy('harvest_date', 'desc')->get()->map(function ($yield) use ($plot) {
            $cropData = CropData::where('crop_name', $yield->crop)->first();
            $hectare = $plot->hectare;

            // Calculate expected yield and performance
            $expectedMaxYield = $cropData ? $cropData->yield_max * $hectare : null;
            $performance = $expectedMaxYield > 0 ? ($yield->actual_yield / $expectedMaxYield) * 100 : null;

            return [
                'crop_name' => $yield->crop,
                'actual_yield' => $yield->actual_yield ?? 'Not available',
                'planting_date' => $yield->planting_date->format('Y-m-d'),
                'harvest_date' => $yield->harvest_date ? $yield->harvest_date->format('Y-m-d') : 'Not available',
                'performance' => $performance ? round($performance, 2) . '%' : 'Not available'
            ];
        });

        // Convert to Laravel collection and paginate manually
        $paginatedYields = \Illuminate\Pagination\Paginator::resolveCurrentPage('page');
        $perPage = 10;
        $pagedData = new \Illuminate\Pagination\LengthAwarePaginator(
            $allYields->slice(($paginatedYields - 1) * $perPage, $perPage)->values(),
            $allYields->count(),
            $perPage,
            $paginatedYields,
            ['path' => request()->url()]
        );

        return view('map.show', [
            'plot' => $plot,
            'username' => $plot->user->username,
            'ratingAvg' => $plot->rating()->avg('rating'),
            'soil' => $plot->soil()->paginate(10),
            'crop_yields' => $pagedData, // <-- Now a paginated collection
            'apiKey' => config('services.mapbox.key'),
            'latest_soil' => $soilData,
        ]);
    }

}
