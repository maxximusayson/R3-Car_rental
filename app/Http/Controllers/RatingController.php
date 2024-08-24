<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'car_id' => 'required|exists:cars,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Create a new rating
        $rating = Rating::create([
            'car_id' => $request->car_id,
            'user_id' => auth()->id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return response()->json($rating);
    }

    public function update(Request $request, $id)
    {
        $rating = Rating::findOrFail($id);
        $this->authorize('update', $rating); // Ensures the user owns the rating
    
        $rating->update($request->all());
    
        return response()->json($rating);
    }

    public function destroy($id)
{
    $rating = Rating::findOrFail($id);
    $this->authorize('delete', $rating); // Ensures the user owns the rating

    $rating->delete();

    return response()->json(['success' => true]);
}
}
