<?php

namespace App\Http\Controllers;

use App\Models\CropYield;
use App\Models\Plot;
use App\Models\Soil;
use App\Models\User;
use App\Services\NutrientAnalysisService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    private NutrientAnalysisService $nutrientAnalysisService;

    public function __construct(NutrientAnalysisService $nutrientAnalysisService)
    {
        $this->nutrientAnalysisService = $nutrientAnalysisService;
    }

    public function index (Request $request)
    {

        $user = auth()->user();
        $plots = $user->plot;

        if ($plots->isEmpty()) {
            return redirect()->route('plot.index')->with('warning', 'You must create a Plot first.');
        }

        $selectedPlot = $request->input('plot_id', $plots->first()->id);
        $mostRecentSoilHealth = Soil::where('plot_id', $selectedPlot)->orderBy('record_date', 'desc')->first();
        $mostRecentCropPlanted = CropYield::where('plot_id', $selectedPlot)->orderBy('planted_date', 'desc')->first();
        $nutrientAnalysis = $this->nutrientAnalysisService->getAllNutrientAnalysis($mostRecentSoilHealth, $mostRecentCropPlanted);
        $nutrientPredictions = $this->nutrientAnalysisService->predictAllNutrientLevel($selectedPlot);


        return view('dashboard.index', [
            'plots' => $plots,
            'selectedPlot' => $selectedPlot,
            'nutrientAnalysis' => $nutrientAnalysis,
            'nutrientPredictions' => $nutrientPredictions,
            'bestCropYields' => CropYield::where('plot_id', $selectedPlot)
                ->orderBy('actual_yield', 'desc')->take(4)->get(),
            'mostPlantedCrops' => CropYield::where('plot_id', $selectedPlot)
                ->select('crop', DB::raw('COUNT(*) as crop_count'))->groupBy('crop')
                ->orderBy('crop_count', 'desc')->take(5)->get(),
            'latestSoils' => Soil::where('plot_id', $selectedPlot)
                ->orderBy('record_date', 'desc')->take(10)->get()->sortBy('record_date'),
            'latestCropYields' => CropYield::where('plot_id', $selectedPlot)
                ->whereNotNull('harvest_date')
                ->orderBy('harvest_date', 'desc')->take(10)->get()->sortBy('harvest_date'),
            'totalUsers' => User::all()->count(),
            'totalPlots' => Plot::all()->count(),
            'totalPublicPlots' => Plot::where('public', 1)->count(),
            'topContributors' => User::hasPublicPlot()->withCount('plot')
                ->orderBy('plot_count', 'desc')->take(3)->get(),
            'topRatedPins' => Plot::withAvg('rating', 'rating')
                ->orderBy('rating_avg_rating', 'desc') ->take(7) ->get(),
            'recentPins' => Plot::where('public', 1)
                ->with('user')->orderBy('created_at', 'desc')->paginate(8)
        ]);
    }

}
