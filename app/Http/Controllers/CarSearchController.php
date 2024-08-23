<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\Car;

class CarSearchController extends Controller
{
    public function search(Request $request)
    {
        $location = $request->input('location');
        $pickupDate = $request->input('pickup_date');
        $pickupTime = $request->input('pickup_time');
        $returnDate = $request->input('return_date');
        $returnTime = $request->input('return_time');

        // Find the branch based on location
        $branch = Branch::where('location', 'like', "%$location%")->first();

        if (!$branch) {
            return redirect()->back()->with('error', 'No branch found for the specified location.');
        }

        // Fetch cars available at the branch
        $cars = Car::where('branch_id', $branch->id)->get();

        // Pass the data to the view
        return view('cars', [
            'cars' => $cars,
            'pickupDate' => $pickupDate,
            'pickupTime' => $pickupTime,
            'returnDate' => $returnDate,
            'returnTime' => $returnTime,
        ]);
    }
}
