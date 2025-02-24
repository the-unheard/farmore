<?php

namespace App\Http\Controllers;

use App\Models\CityClimate;
use App\Models\CropData;
use App\Models\CropYield;
use App\Models\Plot;
use App\Services\CropRecommendationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use function Pest\Laravel\get;

class CropYieldController extends Controller
{
    public function index(Request $request)
    {
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

        // Gets crop yield record for table and chart
        $yields = CropYield::where('plot_id', $selectedPlot)->orderBy('planting_date', 'desc')->paginate(10);
        $latestCropYields = CropYield::where('plot_id', $selectedPlot)
            ->whereNotNull('yield')->whereNotNull('harvest_date')
            ->orderBy('harvest_date', 'desc')
            ->take(15)->get()->sortBy('harvest_date');

        // Get the best crop yields
        $bestCropYields = CropYield::where('plot_id', $selectedPlot)
            ->get()
            ->map(function ($yield) use ($selectedPlot) {
                // Get expected yield range from CropData
                $cropData = CropData::where('crop_name', $yield->crop)->first();

                // Convert expected yield range for this plot's size
                $expectedMinYield = $cropData->yield_min * Plot::where('id', $selectedPlot)->value('hectare');
                $expectedMaxYield = $cropData->yield_max * Plot::where('id', $selectedPlot)->value('hectare');

                // Performance percentage (how well it performed)
                if ($expectedMaxYield > 0) {
                    $performance = ($yield->actual_yield / $expectedMaxYield) * 100;
                } else {
                    $performance = 0; // Avoid division by zero
                }

                return [
                    'id' => $yield->id,
                    'crop_name' => $yield->crop,
                    'actual_yield' => $yield->actual_yield,
                    'expected_min' => $expectedMinYield,
                    'expected_max' => $expectedMaxYield,
                    'performance' => round($performance, 2) // % of expected max yield
                ];
            })
            ->sortByDesc('performance') // Sort by best performance
            ->take(8) // Keep top 8 performers
            ->values(); // Reset keys for clean output

        // Get the top best pairs for crop rotation
        $cropRotationPairs = [];

        // Get sorted crop yields with valid yield data
        $sortedYields = $latestCropYields->filter(fn($yield) => !is_null($yield->actual_yield));
        $sortedYields = $sortedYields->values();

        // Iterate over consecutive crops to form pairs
        for ($i = 0; $i < $sortedYields->count() - 1; $i++) {
            $cropA = $sortedYields[$i]->crop;
            $cropB = $sortedYields[$i + 1]->crop;

            // Normalize the pair (A → B and B → A are the same)
            $pair = collect([$cropA, $cropB])->sort()->values()->implode(' and ');

            // Compute performance for the pair
            $performanceA = $bestCropYields->firstWhere('crop_name', $cropA)['performance'] ?? 0;
            $performanceB = $bestCropYields->firstWhere('crop_name', $cropB)['performance'] ?? 0;
            $averagePerformance = ($performanceA + $performanceB) / 2;

            // Convert collection to an array before modification
            if (!isset($cropRotationPairs[$pair])) {
                $cropRotationPairs[$pair] = [
                    'pair' => $pair,
                    'totalPerformance' => 0,
                    'count' => 0,
                ];
            }

            // Update the pair's data
            $cropRotationPairs[$pair]['totalPerformance'] += $averagePerformance;
            $cropRotationPairs[$pair]['count']++;
        }

        // Calculate the average performance for each pair
        $cropRotationPairs = array_map(fn($data) => [
            'pair' => $data['pair'],
            'averagePerformance' => $data['totalPerformance'] / $data['count'],
        ], $cropRotationPairs);

        // Get the top best-performing crop rotation pairs
        $bestRotationPairs = collect($cropRotationPairs)->sortByDesc('averagePerformance')->take(8)->values();

        return view('crop-yield.index', [
            'yields' => $yields,
            'plots' => $plots,
            'selectedPlot' => $selectedPlot,
            'latestCropYields' => $latestCropYields,
            'mostPlantedCrops' => CropYield::where('plot_id', $selectedPlot)
                ->select('crop', DB::raw('COUNT(*) as crop_count'))->groupBy('crop')
                ->orderBy('crop_count', 'desc')->take(5)->get(),
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

        $crop = CropData::where('crop_name', $request->crop_name)->firstOrFail();
        $plot = auth()->user()->plot()->findOrFail($request->plot_id);
        $idealSoil = $this->getIdealSoil($plot, $crop);
        $idealMonth = $this->getIdealMonth($plot, $crop);
        $fertilizerAdvice = $this->getNPK($plot, $crop);
        $phAdvice = $this->getPH($plot, $crop);

        return response()->json([
            'ideal_soil' => $idealSoil,
            'ideal_month' => $idealMonth,
            'seeds_min' => $crop->seeds_needed_min,
            'seeds_max' => $crop->seeds_needed_max,
            'seeds_unit' => $crop->seeds_unit,
            'density_min' => $crop->density_min,
            'density_max' => $crop->density_max,
            'spacing_plant_min' => $crop->spacing_plant_min,
            'spacing_plant_max' => $crop->spacing_plant_max,
            'spacing_row_min' => $crop->spacing_row_min,
            'spacing_row_max' => $crop->spacing_row_max,
            'fertilizer_advice' => $fertilizerAdvice,
            'ph_advice' => $phAdvice,
            'maturity_min' => $crop->maturity_min,
            'maturity_max' => $crop->maturity_max,
            'maturity_unit' => $crop->maturity_unit,
            'maturity_type' => $crop->maturity_type,
            'produce_min' => $crop->yield_min,
            'produce_max' => $crop->yield_max,
            'hectare' => $plot->hectare,
        ]);
    }

    private function getIdealSoil($plot, $crop)
    {
        $plotSoilType = $plot->soil_type;

        $idealSoilType = json_decode($crop->soil_types, true);

        if(in_array($plotSoilType, $idealSoilType)){
            return 'Yes, it\'s ideal on your ' . $plotSoilType . ' soil';
        } else {
            if (count($idealSoilType) > 1) {
                $last = array_pop($idealSoilType);
                $formattedSoilTypes = implode(', ', $idealSoilType) . ' and ' . $last;
            } else {
                $formattedSoilTypes = $idealSoilType[0];
            }

            return 'No, it\'s better on ' . $formattedSoilTypes;
        }
    }

    private function getIdealMonth($plot, $crop)
    {
        $city = $plot->city;
        $climate = CityClimate::where('municipality', $city)->firstOrFail()->climate;
        $climateColumn = 'climate_' . $climate;
        $allowedMonths = json_decode($crop->$climateColumn, true);

        sort($allowedMonths);
        $currentMonth = now()->month;
        $idealMonth = null;
        foreach ($allowedMonths as $month) {
            if($month === $currentMonth) {
                return 'this month';
            }
            else if ($month > $currentMonth) {
                $idealMonth = $month;
                break;
            }
        }
        if (!$idealMonth) {
            $idealMonth = $allowedMonths[0];
        }
        return 'on ' . Carbon::createFromFormat('m', $idealMonth)->format('F');
    }

    private function getNPK($plot, $crop)
    {
        $cropRecommendationService = new CropRecommendationService();

        // get the latest soil record for the plot
        $soil = $plot->latestSoil;

        if (!$soil) {
            return 'No soil record found';
        }

        $normalizedNPK = $cropRecommendationService->normalizedNPK($crop, $soil);

        $prioritize = [];

        if ($normalizedNPK['normalizedUserN'] < $normalizedNPK['normalizedCropN']) {
            $prioritize[] = 'Nitrogen';
        }
        if ($normalizedNPK['normalizedUserP'] < $normalizedNPK['normalizedCropP']) {
            $prioritize[] = 'Phosphorus';
        }
        if ($normalizedNPK['normalizedUserK'] < $normalizedNPK['normalizedCropK']) {
            $prioritize[] = 'Potassium';
        }

        if (!empty($prioritize)) {
            return 'Focus more on ' . implode(' and ', $prioritize);
        } else {
            return 'Give a balanced amount of NPK';
        }

    }

    private function getPH($plot, $crop) {

        $soil = $plot->latestSoil;
        if (!$soil) {
            return 'No soil record found';
        }

        $userPh = $soil->ph;
        $cropPhMin = $crop->req_ph_min;
        $cropPhMax = $crop->req_ph_max;

        if (!$userPh) {
            return 'No recent pH record';
        } elseif ($userPh) {
            if ($userPh > $cropPhMax) {
                return 'pH must be decreased to ' . $cropPhMin . ' - ' . $cropPhMax . ' pH';
            } elseif ($userPh < $cropPhMin) {
                return 'pH must be increased to ' . $cropPhMin . ' - ' . $cropPhMax . ' pH';
            } else {
                return 'pH is currently ideal';
            }
        } else {
            return 'No record';
        }
    }

}
