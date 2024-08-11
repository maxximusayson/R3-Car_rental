<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

class RateController extends Controller
{
    public function rate(Request $request, Car $car)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'rating' => 'required|integer|min:1|max:5', // Assuming rating is an integer between 1 and 5
        ]);

        // Save the rating to the car
        $car->ratings()->create([
            'user_id' => auth()->id(), // Assuming you have a user ID associated with the rating
            'rating' => $validatedData['rating'],
        ]);

        // Redirect back or return a response as needed
        return redirect()->back()->with('success', 'Car rated successfully!');
    }

}
