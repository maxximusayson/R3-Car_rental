<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\CarImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cars = Car::paginate(8); // Fetch paginated cars from the database
        return view('admin.cars', compact('cars'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.createCar');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'brand' => 'required',
            'model' => 'required',
            'engine' => 'required',
            'quantity' => 'required',
            'price_per_day' => 'required',
            'description' => 'nullable|string',
            'status' => 'required',
            'branch' => 'required|string',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10240' // Validate multiple images
        ]);

        $car = new Car;
        $car->brand = $request->brand;
        $car->model = $request->model;
        $car->engine = $request->engine;
        $car->quantity = $request->quantity;
        $car->price_per_day = $request->price_per_day;
        $car->description = $request->input('description');
        $car->status = $request->status;
        $car->branch = $request->branch;
        $car->save();

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = $request->brand . '-' . $request->model . '-' . $request->engine . '-' . Str::random(10) . '.' . $image->extension();
                $path = $image->storeAs('public/images/cars/' . $car->id, $imageName);
                
                $carImage = new CarImage;
                $carImage->car_id = $car->id;
                $carImage->image_path = '/' . $path;
                $carImage->save();
            }
        }

        return redirect()->route('cars.index')->with('success', 'Car details created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Car $car)
    {
        $car->load('images'); // Load associated images
        return view('car.details', compact('car'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Car $car)
    {
        return view('admin.updateCar', compact('car'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Car $car)
    {
        $request->validate([
            'brand' => 'required',
            'model' => 'required',
            'engine' => 'required',
            'quantity' => 'required',
            'price_per_day' => 'required',
            'status' => 'required',
            'branch' => 'required|string',
            'description' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240' // Validate multiple images
        ]);

        $car->brand = $request->brand;
        $car->model = $request->model;
        $car->engine = $request->engine;
        $car->quantity = $request->quantity;
        $car->price_per_day = $request->price_per_day;
        $car->description = $request->input('description');
        $car->status = $request->status;
        $car->branch = $request->branch;
        $car->save();

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = $request->brand . '-' . $request->model . '-' . $request->engine . '-' . Str::random(10) . '.' . $image->extension();
                $path = $image->storeAs('public/images/cars/' . $car->id, $imageName);

                $carImage = new CarImage;
                $carImage->car_id = $car->id;
                $carImage->image_path = '/' . $path;
                $carImage->save();
            }
        }

        return redirect()->route('cars.index')->with('success', 'Car details updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Car $car)
    {
        $car->load('images');
        foreach ($car->images as $image) {
            $filename = basename($image->image_path);
            Storage::disk('local')->delete('public/images/cars/' . $car->id . '/' . $filename);
            $image->delete();
        }
        $car->delete();

        return redirect()->route('cars.index')->with('success', 'Car deleted successfully.');
    }

    public function rate(Request $request, Car $car)
    {
        // Process form submission (e.g., save rating to database)
        $rating = $request->input('rating');

        // Redirect to the rating form with the car ID
        return redirect()->route('car.rating', ['car' => $car->id]);
    }

    public function showRatingForm(Car $car)
    {
        // Fetch car details from the database
        $car->load('ratings');

        // Pass the car details to the view
        return view('cars.rating', compact('car'));
    }
}
