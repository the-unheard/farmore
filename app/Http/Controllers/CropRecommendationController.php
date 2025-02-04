<?php

namespace App\Http\Controllers;

use App\Models\CropData;
use App\Models\CropRecommendation;
use App\Models\Plot;
use App\Services\CropRecommendationService;
use App\Services\RecommendationService;
use Illuminate\Http\Request;
use Phpml\Classification\KNearestNeighbors;
use Phpml\Classification\NaiveBayes;
use Phpml\Math\Distance\Euclidean;

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

        //$cropWithPoints = $this->getCropPoints($selectedPlot, $plotSoilHealth);

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
            //'cropWithPoints' => $cropWithPoints,
            'finalRecommendations' => $finalRecommendations
        ]);
    }

    public function store()
    {
        // validate
        $validatedData = request()->validate([
            'user_id' => ['required', 'integer'],
            'nitrogen' => ['required', 'numeric'],
            'phosphorus' => ['required', 'numeric'],
            'potassium' => ['required', 'numeric'],
            'temperature' => ['required', 'numeric'],
            'humidity' => ['required', 'numeric'],
            'ph' => ['required', 'numeric'],
            'rainfall' => ['required', 'numeric']
        ]);

        // use the user's input data to predict the recommended crop
        $inputData = [
            $validatedData['nitrogen'],
            $validatedData['phosphorus'],
            $validatedData['potassium'],
            $validatedData['temperature'],
            $validatedData['humidity'],
            $validatedData['ph'],
            $validatedData['rainfall']
        ];

        // get the training data from the CropRecommendation table
        $cropRecommendations = CropRecommendation::all();

        // prepare the samples and labels arrays
        $samples = [];
        $labels = [];

        foreach ($cropRecommendations as $recommendation) {
            // add the soil healths as a sample
            $samples[] = [
                $recommendation->nitrogen,
                $recommendation->phosphorus,
                $recommendation->potassium,
                $recommendation->temperature,
                $recommendation->humidity,
                $recommendation->ph,
                $recommendation->rainfall,
            ];

            // add the recommended crops as the label
            $labels[] = $recommendation->recommended_crop;
        }

        $predictedWithKNN = $this->predictWithKNN($samples, $labels, $inputData);
        $predictedWithEuclidean = $this->predictWithEuclidean($samples, $labels, $inputData);
        $predictWithNaiveBayes = $this->predictWithNaiveBayes($samples, $labels, $inputData);

        return view('crop-recommendation.result',[
            'best' => $predictWithNaiveBayes,
            'knn' => $predictedWithKNN,
            'others' => $predictedWithEuclidean
        ]);

    }

    private function predictWithNaiveBayes($samples, $labels, $inputData) {
        $classifier = new Naivebayes();
        $classifier->train($samples, $labels);
        return $classifier->predict($inputData);
    }

    private function predictWithKNN($samples, $labels, $inputData) {
        $classifier = new KNearestNeighbors();
        $classifier->train($samples, $labels);
        return $classifier->predict($inputData);
    }

    private function predictWithEuclidean($samples, $labels, $inputData){

        $distanceMetric = new Euclidean();
        $distances = [];

        foreach ($samples as $index => $neighbor) {
            // calculate distance between inputData and neighbor sample
            $distance = $distanceMetric->distance($inputData, $neighbor);
            $distances[$index] = ['distance' => $distance, 'crop' => $labels[$index]];
        }

        // sort distances by nearest
        usort($distances, function ($a, $b) {
            return $a['distance'] <=> $b['distance'];
        });

        // get first 10 unique nearest
        $top10UniqueCrops = [];
        foreach ($distances as $distance) {
            if (!in_array($distance['crop'], $top10UniqueCrops)) {
                $top10UniqueCrops[] = $distance['crop'];
            }
            if (count($top10UniqueCrops) == 10) {
                break;
            }
        }

        return $top10UniqueCrops;
    }

    private function getCropPoints($selectedPlot, $plotSoilHealth) {

        $cropRecommendationService = new CropRecommendationService();
        $allCrops = CropData::all();

        foreach ($allCrops as $crop) {

            $points = [];

            $points['idealSoil'] = $cropRecommendationService->isIdealSoil($crop, $selectedPlot);
            $points['idealMonth'] = $cropRecommendationService->isIdealMonth($crop, $selectedPlot);

            if($plotSoilHealth) {
                $points['idealN'] = $cropRecommendationService->isIdealN($crop, $plotSoilHealth);
                $points['idealP'] = $cropRecommendationService->isIdealP($crop, $plotSoilHealth);
                $points['idealK'] = $cropRecommendationService->isIdealK($crop, $plotSoilHealth);
                $points['idealPh'] = $cropRecommendationService->isIdealPh($crop, $plotSoilHealth);
            } else {
                $points['idealN'] = false;
                $points['idealP'] = false;
                $points['idealK'] = false;
                $points['idealPh'] = false;
            }

            $totalPoints = count(array_filter($points));
            $crop->points = $totalPoints;
        }

        return $allCrops;

    }


}
