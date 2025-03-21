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
        $user = User::findOrFail($id);

        return view('manage-users.edit', [
            'user' => $user,
        ]);
    }

    public function update($id)
    {
        $validatedData = $this->validateInput();

        $user = User::findOrFail($id);
        $user->update($validatedData);

        return redirect('/manage-users/' . $id)->with('success', 'User record updated successfully.');
    }

    public function destroy($id)
    {

        $user = User::findOrFail($id);

        if ($user->id === 1) {
            return redirect('/manage-users')->with('Error', 'You don\'t have permission to delete this.');
        }

        $user->delete();
        return redirect('/manage-users')->with('success', 'User deleted successfully.');
    }

    private function validateInput(): array
    {
        $validatedData = request()->validate([
            'username' => [
                'required',
                'regex:/^[a-zA-Z0-9._-]+$/', // Allows letters, numbers, dots, dashes, and underscores
                'min:1',
                'max:20',
                'unique:users,username,' . request()->route('user')
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
