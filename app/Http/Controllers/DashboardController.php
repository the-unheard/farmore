<?php

namespace App\Http\Controllers;

use App\Models\CropData;
use App\Models\CropYield;
use App\Models\Plot;
use App\Models\Soil;
use App\Models\User;
use App\Services\ChartHelperService;
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

        $chartHelperService = new chartHelperService();

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

        // Get crop yields and calculate performance
        $allCropYields = $chartHelperService->getCropYieldsWithPerformance($selectedPlot);

        $bestCropYields = $allCropYields->sortByDesc('performance')->take(4)->values();

        // latest crop yields for chart
        $latestCropYields = $allCropYields
            ->whereNotNull('actual_yield')
            ->whereNotNull('harvest_date')
            ->sortBy('harvest_date')
            ->take(15)
            ->values();

        return view('dashboard.index', [
            'plots' => $plots,
            'selectedPlot' => $selectedPlot,
            'nutrientAnalysis' => $nutrientAnalysis,
            'nutrientPredictions' => $nutrientPredictions,
            'bestCropYields' => $bestCropYields,
            'mostPlantedCrops' => $chartHelperService->mostPlantedCrops($selectedPlot),
            'latestSoils' => Soil::where('plot_id', $selectedPlot)
                ->orderBy('record_date', 'desc')->take(10)->get()->sortBy('record_date'),
            'latestCropYields' => $latestCropYields,
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
