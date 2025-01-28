<?php

namespace App\Http\Controllers;

use App\Models\Plot;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function store(Request $request, Plot $plot)
    {
        $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5']
        ]);;

        $existingRating = Rating::where('user_id', auth()->id())
            ->where('plot_id', $plot->id)
            ->first();

        if ($existingRating) {
            $existingRating->update([
                'rating' => $request->input('rating')
            ]);
        } else {
            Rating::create([
                'user_id' => auth()->id(),
                'plot_id' => $plot->id,
                'rating' => $request->input('rating')
            ]);
        }

        return redirect()->back()->with('success', $request->input('rating') . '-star rating successfully given');
    }
}
