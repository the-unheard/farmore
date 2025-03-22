<?php

namespace App\Http\Controllers;

use App\Models\Plot;
use App\Models\User;

class ManagePlotController extends Controller
{

    public function index()
    {
        $plots = $this->getPlotDetails(Plot::query()) // Use the reusable function
            ->orderBy('plots.id')
            ->paginate(10);

        return view('manage-plots.index', compact('plots'));
    }

    public function show($id)
    {
        $plot = $this->getPlotDetails(Plot::where('plots.id', $id))->first();

        if (!$plot) {
            return redirect()->route('manage-plots.index')->with('error', 'Plot not found.');
        }

        return view('manage-plots.show', ['plot' => $plot]);
    }

    public function edit($id)
    {
        $plot = Plot::findOrFail($id);

        return view('manage-plots.edit', [
            'plot' => $plot,
        ]);
    }

    public function update($id)
    {
        $validatedData = $this->validateInput();

        $plot = Plot::findOrFail($id);
        $plot->update($validatedData);

        return redirect('/manage-plots/' . $id)->with('success', 'Plot record updated successfully.');
    }

    public function destroy($id)
    {

        $plot = Plot::findOrFail($id);
        $plot->delete();

        return redirect('/manage-plots')->with('success', 'Plot deleted successfully.');
    }

    private function validateInput(): array
    {
        $validatedData = request()->validate([
            'name' => [
                'required',
                'regex:/^[a-zA-Z0-9._\-\s]+$/', // Allows letters, numbers, dots, dashes, underscores and space
                'min:1',
                'max:30',
            ],
        ]);

        return $validatedData;
    }

    private function getPlotDetails($query)
    {
        return $query->select('plots.id', 'plots.name', 'plots.city', 'plots.hectare', 'plots.public', 'users.username')
            ->leftJoin('users', 'plots.user_id', '=', 'users.id') // Get username from users
            ->leftJoin('ratings', 'plots.id', '=', 'ratings.plot_id') // Join ratings
            ->selectRaw('ROUND(COALESCE(AVG(ratings.rating), 0), 2) as average_rating, COUNT(ratings.id) as rating_count')
            ->selectRaw('CASE WHEN plots.public = 1 THEN "Yes" ELSE "No" END as is_public')
            ->groupBy('plots.id', 'plots.name', 'plots.city', 'plots.hectare', 'plots.public', 'users.username');
    }


}
