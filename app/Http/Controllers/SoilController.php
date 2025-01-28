<?php

namespace App\Http\Controllers;

use App\Models\Plot;
use App\Services\NutrientAnalysisService;
use Illuminate\Http\Request;
use App\Models\Soil;
use Carbon\Carbon;
use Phpml\Regression\LeastSquares;

class SoilController extends Controller
{

    public function index(Request $request)
    {
        // instantiate Nutrient Analysis Service
        $nutrientAnalysisService  = new NutrientAnalysisService();

        // get the current logged-in user
        $user = auth()->user();

        // get the user's plots using eloquent model relationship
        $plots = $user->plot;

        // validate if user has plots
        if ($plots->isEmpty()) {
            return redirect()->route('plot.index')->with('warning', 'You must create a Plot first.');
        }

        // checks for request parameter (/index?plot_id={id}), then fall back is first plot in database
        $selectedPlot = $request->input('plot_id', $plots->first()->id);

        // gets soil record for table and chart
        $soils = Soil::where('plot_id', $selectedPlot)->orderBy('record_date', 'desc')->paginate(10);
        $latestSoils = Soil::where('plot_id', $selectedPlot)->orderBy('record_date', 'desc')->take(10)->get()->sortBy('record_date');
        $soilData = Soil::where('plot_id', $selectedPlot)->get();

        // levels
        $nMinRecord = $this->getMinimum($soilData, 'nitrogen');
        $nMaxRecord = $this->getMaximum($soilData, 'nitrogen');
        $nAvgRecord = round($soilData->avg('nitrogen'), 2);
        $pMinRecord = $this->getMinimum($soilData, 'phosphorus');
        $pMaxRecord = $this->getMaximum($soilData, 'phosphorus');
        $pAvgRecord = round($soilData->avg('phosphorus'), 2);
        $kMinRecord = $this->getMinimum($soilData, 'potassium');
        $kMaxRecord = $this->getMaximum($soilData, 'potassium');
        $kAvgRecord = round($soilData->avg('potassium'), 2);
        $tMinRecord = $this->getMinimum($soilData, 'temperature');
        $tMaxRecord = $this->getMaximum($soilData, 'temperature');
        $tAvgRecord = round($soilData->avg('temperature'), 2);
        $hMinRecord = $this->getMinimum($soilData, 'humidity');
        $hMaxRecord = $this->getMaximum($soilData, 'humidity');
        $hAvgRecord = round($soilData->avg('humidity'), 2);
        $phMinRecord = $this->getMinimum($soilData, 'ph');
        $phMaxRecord = $this->getMaximum($soilData, 'ph');
        $phAvgRecord = round($soilData->avg('ph'), 2);

        // rate of change / trend analysis
        $nitrogenRateOfChange = $this->processRateOfChange('nitrogen', $selectedPlot);
        $phosphorusRateOfChange = $this->processRateOfChange('phosphorus', $selectedPlot);
        $potassiumRateOfChange = $this->processRateOfChange('potassium', $selectedPlot);
        $temperatureRateOfChange = $this->processRateOfChange('temperature', $selectedPlot);
        $humidityRateOfChange = $this->processRateOfChange('humidity', $selectedPlot);
        $phRateOfChange = $this->processRateOfChange('ph', $selectedPlot);

        // predict
        $predictedNitrogen = $nutrientAnalysisService->predictNutrientLevel('nitrogen', $selectedPlot);
        $predictedPhosphorus = $nutrientAnalysisService->predictNutrientLevel('phosphorus', $selectedPlot);
        $predictedPotassium = $nutrientAnalysisService->predictNutrientLevel('potassium', $selectedPlot);
        $predictedTemperature = $nutrientAnalysisService->predictNutrientLevel('temperature', $selectedPlot);
        $predictedHumidity = $nutrientAnalysisService->predictNutrientLevel('humidity', $selectedPlot);
        $predictedPh = $nutrientAnalysisService->predictNutrientLevel('ph', $selectedPlot);

        return view('soil.index', compact(
            'soils',
            'plots',
            'selectedPlot',
            'latestSoils',
            'nMinRecord', 'nMaxRecord', 'nAvgRecord',
            'pMinRecord', 'pMaxRecord', 'pAvgRecord',
            'kMinRecord', 'kMaxRecord', 'kAvgRecord',
            'tMinRecord', 'tMaxRecord', 'tAvgRecord',
            'hMinRecord', 'hMaxRecord', 'hAvgRecord',
            'phMinRecord', 'phMaxRecord', 'phAvgRecord',
            'nitrogenRateOfChange',
            'phosphorusRateOfChange',
            'potassiumRateOfChange',
            'temperatureRateOfChange',
            'humidityRateOfChange',
            'phRateOfChange',
            'predictedNitrogen', 'predictedPhosphorus', 'predictedPotassium',
            'predictedTemperature', 'predictedHumidity', 'predictedPh'
        ));

    }

    public function show(Soil $soil)
    {
        $this->authorizeOwner($soil);
        return view('soil.show', ['soil' => $soil]);
    }

    public function create()
    {
        // get all the plots for the authenticated user to display it as dropdown
        $plots = auth()->user()->plot;
        return view('soil.create',['plots' => $plots]);
    }

    public function store()
    {
        // check the user input
        $validatedData = $this->validateInput();

        // find the plot by its ID and the user_id by the current user
        // retrieves the first matching record or fail (shows an error)
        $plot = Plot::where('id', $validatedData['plot_id'])->where('user_id', auth()->id())->firstOrFail();

        $plot->soil()->create($validatedData);
        return redirect('/soil?plot_id='.$validatedData['plot_id'])->with('success', 'Soil health record created successfully.');
    }

    public function edit(Soil $soil)
    {
        $this->authorizeOwner($soil);

        // gets all the plots of the current user
        $plots = auth()->user()->plot;

        return view('soil.edit', [
            'soil' => $soil,
            'plots' => $plots // for plot dropdown
        ]);
    }

    public function update(Soil $soil)
    {
        $this->authorizeOwner($soil);
        $validatedData = $this->validateInput();
        $soil->update($validatedData);
        return redirect('/soil/' . $soil->id)->with('success', 'Soil health record updated successfully.');
    }

    public function destroy(Soil $soil)
    {
        $this->authorizeOwner($soil);
        $soil->delete();

        return redirect('/soil?plot_id=' . $soil->plot_id )->with('success', 'Soil health record deleted successfully.');
    }

    private function validateInput(): array
    {
        $validatedData = request()->validate([
            'plot_id' => ['required', 'exists:plots,id'], // check that plot_id exists in the plots table
            'nitrogen' => ['required', 'numeric', 'min:1', 'max:2000'],
            'phosphorus' => ['required', 'numeric', 'min:1', 'max:2000'],
            'potassium' => ['required', 'numeric', 'min:1', 'max:2000'],
            'temperature' => ['sometimes', 'nullable', 'numeric', 'min:-10', 'max:60'],
            'humidity' => ['sometimes', 'nullable', 'numeric', 'min:1', 'max:100'],
            'ph' => ['sometimes', 'nullable', 'numeric', 'min:1', 'max:14'],
            'record_date' => ['required', 'date_format:m-d-Y'],
        ]);

        // parse the date before storing
        $validatedData['record_date'] = Carbon::createFromFormat('m-d-Y', $validatedData['record_date'])->format('Y-m-d');
        return $validatedData;
    }

    // checks if the owner of the plot where the soil record belongs to is the current logged-in user
    private function authorizeOwner(Soil $soil)
    {
        if ($soil->plot->user_id !== auth()->id()) {
            return redirect('/soil')->with('error', 'You do not have authority to access this record.');
        }
        return null;
    }

    // get minimum
    private function getMinimum($soilData, $field)
    {
        return $soilData->filter(function ($item) use ($field) {
            return !is_null($item->$field) && $item->$field !== '';
        })->sortBy($field)->first();
    }

    // get maximum
    private function getMaximum($soilData, $field)
    {
        return $soilData->filter(function ($item) use ($field) {
            return !is_null($item->$field) && $item->$field !== '';
        })->sortByDesc($field)->first();
    }


    // process rate of change
    private function processRateOfChange($nutrientColumn, $selectedPlot)
    {
        $nutrientData = Soil::where('plot_id', $selectedPlot)
            ->whereNotNull($nutrientColumn) // Ensure the nutrient column is not null
            ->where($nutrientColumn, '!=', '') // Ensure the nutrient column is not empty
            ->orderBy('record_date', 'desc')
            ->take(6)->pluck($nutrientColumn, 'record_date')->reverse();
        return collect($this->calculateRateOfChange($nutrientData))->reverse();
    }

    // rate of change
    private function calculateRateOfChange($data)
    {
        $rateOfChange = [];
        $previousValue = null;
        $previousDate = null;

        foreach ($data as $date => $value) {
            if ($previousValue !== null) {
                $percentageChange = (($value - $previousValue) / $previousValue) * 100;
                $daysDifference = Carbon::parse($previousDate)->diffInDays($date);

                $rateOfChange[] = [
                    'date' => $date,
                    'value' => $value,
                    'change' => $percentageChange,
                    'daysDifference' => $daysDifference,
                ];
            }
            $previousValue = $value;
            $previousDate = $date;
        }
        return $rateOfChange;
    }

}
