<?php

namespace App\Http\Controllers;

use App\Models\CityClimate;
use App\Models\CropData;
use App\Models\CropYield;
use App\Models\Plot;
use App\Services\ChartHelperService;
use App\Services\CropRecommendationService;
use App\Services\RecommendationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use function Pest\Laravel\get;

class CropYieldController extends Controller
{
    public function index(Request $request)
    {

        $chartHelperService = new chartHelperService();

        // Get the current logged-in user
        $user = auth()->user();

        // Get the user's plots using Eloquent model relationship
        $plots = $user->plot;

        // Validate if user has plots
        if ($plots->isEmpty()) {
            return redirect()->route('plot.index')->with('warning', 'You must create a Plot first.');
        }

        // Checks for request parameter (/index?plot_id={id}), then fall back to the first plot in the database
        $selectedPlot = $request->input('plot_id', $plots->first()->id);

        // Gets crop yield record for table
        $yields = CropYield::where('plot_id', $selectedPlot)->orderBy('planting_date', 'desc')->paginate(10);

        // Get crop yields and calculate performance
        $allCropYields = $chartHelperService->getCropYieldsWithPerformance($selectedPlot);

        // best performing crops for small table
        $bestCropYields = $allCropYields->sortByDesc('performance')->take(8)->values();

        // latest crop yields for chart
        $latestCropYields = $allCropYields
            ->whereNotNull('actual_yield')
            ->whereNotNull('harvest_date')
            ->sortBy('harvest_date')
            ->take(15)
            ->values();

        // Get the top best pairs for crop rotation
        $cropRotationPairs = [];

        $sortedYields = $latestCropYields->values();

        for ($i = 0; $i < $sortedYields->count() - 1; $i++) {
            $cropA = $sortedYields[$i]['crop_name'];
            $cropB = $sortedYields[$i + 1]['crop_name'];

            $pair = collect([$cropA, $cropB])->sort()->values()->implode(' and ');

            $performanceA = $sortedYields[$i]['performance'] ?? 0;
            $performanceB = $sortedYields[$i + 1]['performance'] ?? 0;
            $averagePerformance = ($performanceA + $performanceB) / 2;

            if (!isset($cropRotationPairs[$pair])) {
                $cropRotationPairs[$pair] = [
                    'pair' => $pair,
                    'totalPerformance' => 0,
                    'count' => 0,
                ];
            }

            $cropRotationPairs[$pair]['totalPerformance'] += $averagePerformance;
            $cropRotationPairs[$pair]['count']++;
        }

        $cropRotationPairs = array_map(fn($data) => [
            'pair' => $data['pair'],
            'averagePerformance' => $data['totalPerformance'] / $data['count'],
        ], $cropRotationPairs);

        $bestRotationPairs = collect($cropRotationPairs)->sortByDesc('averagePerformance')->take(8)->values();

        return view('crop-yield.index', [
            'yields' => $yields,
            'plots' => $plots,
            'selectedPlot' => $selectedPlot,
            'latestCropYields' => $latestCropYields,
            'mostPlantedCrops' => $chartHelperService->mostPlantedCrops($selectedPlot),
            'bestCropYields' => $bestCropYields,
            'bestRotationPairs' => $bestRotationPairs,
        ]);
    }


    public function show(CropYield $crop_yield)
    {
        $this->authorizeOwner($crop_yield);
        return view('crop-yield.show', ['yield' => $crop_yield]);
    }

    public function create()
    {
        // get all the plots for the authenticated user to display it as dropdown
        $plots = auth()->user()->plot;
        $crops = CropData::get();

        return view('crop-yield.create', [
            'plots' => $plots,
            'crops' => $crops,
        ]);
    }

    public function store()
    {
        $validatedData = $this->validateInput();

        // find the plot by its ID and the user_id by the current user
        // retrieves the first matching record or fail (shows an error)
        $plot = Plot::where('id', $validatedData['plot_id'])->where('user_id', auth()->id())->firstOrFail();

        $plot->cropyield()->create($validatedData);
        return redirect('/crop-yield?plot_id='.$validatedData['plot_id'])->with('success', 'Crop Yield record created successfully.');
    }

    public function edit(CropYield $crop_yield)
    {
        $this->authorizeOwner($crop_yield);

        // get all the plots for the authenticated user to display it as dropdown
        $plots = auth()->user()->plot;
        $crops = CropData::get();

        return view('crop-yield.edit', [
            'yield' => $crop_yield,
            'plots' => $plots,
            'crops' => $crops,
        ]);
    }

    public function update(CropYield $crop_yield)
    {
        $this->authorizeOwner($crop_yield);
        $validatedData = $this->validateInput();
        $crop_yield->update($validatedData);
        return redirect('/crop-yield/' . $crop_yield->id)->with('success', 'Crop yield record updated successfully.');
    }

    public function destroy(CropYield $crop_yield)
    {
        $this->authorizeOwner($crop_yield);
        $crop_yield->delete();
        return redirect('/crop-yield?plot_id=' . $crop_yield->plot_id)->with('success', 'Crop Yield record deleted successfully.');
    }

    private function validateInput(): array
    {
        $validatedData = request()->validate([
            'plot_id' => ['required', 'integer'],
            'crop' => ['required'],
            'actual_yield' => ['sometimes', 'nullable', 'numeric'],
            'planting_date' => ['sometimes', 'nullable', 'date_format:m-d-Y'],
            'harvest_date' => ['sometimes', 'nullable', 'date_format:m-d-Y'],
        ]);

        if (!empty($validatedData['planting_date'])) {
            $validatedData['planting_date'] = Carbon::createFromFormat('m-d-Y', $validatedData['planting_date'])->format('Y-m-d');
        }

        if (!empty($validatedData['harvest_date'])) {
            $validatedData['harvest_date'] = Carbon::createFromFormat('m-d-Y', $validatedData['harvest_date'])->format('Y-m-d');
        }

        return $validatedData;
    }

    private function authorizeOwner(CropYield $crop_yield)
    {
        if ($crop_yield->plot->user_id !== auth()->id()) {
            return redirect('/crop-yield')->with('error', 'You do not have authority to access this record.');
        }
        return null;
    }

    public function getCropYieldEstimates(Request $request)
    {
        $request->validate([
            'crop_name' => ['required', 'string'],
            'plot_id' => ['required', 'integer'],
        ]);

        $plot = auth()->user()->plot()->findOrFail($request->plot_id);
        $recommendationService = new RecommendationService();
        $cropInformation = $recommendationService->getCropInformation($request->crop_name, $plot);

        return response()->json($cropInformation, 200);

    }


}
