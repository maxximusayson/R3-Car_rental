<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\CarImage;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Carbon\Carbon; // Import Carbon
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cars = Car::with('images')->get(); // Fetch all cars with images
        $currentDate = Carbon::now();

        // Get all reservations and prepare availability data
        $reservations = Reservation::where('end_date', '>=', $currentDate)
            ->where('start_date', '<=', $currentDate)
            ->get();

        $availability = [];

        foreach ($cars as $car) {
            $reservedDates = [];
            $availableDates = [];

            foreach ($reservations as $reservation) {
                if ($reservation->car_id == $car->id) {
                    $start = Carbon::parse($reservation->start_date);
                    $end = Carbon::parse($reservation->end_date);

                    while ($start->lte($end)) {
                        $reservedDates[] = $start->toDateString();
                        $start->addDay();
                    }
                }
            }

            // Generate available dates for the car
            $availableDates = $this->getAvailableDates($car, $reservedDates);

            $availability[$car->id] = [
                'available_dates' => $availableDates,
                'reserved_dates' => $reservedDates,
            ];
        }

        return view('admin.cars', compact('cars', 'availability'));
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
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'engine' => 'required|string|max:255',
            'quantity' => 'required|integer',
            'price_per_day' => 'required|numeric',
            'stars' => 'required|integer|min:1|max:5',
            'images.*' => 'nullable|mimes:jpg,jpeg,png,gif|max:10240',
            'videos.*' => 'nullable|mimes:mp4|max:51200', // Validate MP4 video files
            'description' => 'nullable|string',
            'insurance_status' => 'required|string',
            'status' => 'required|string',
            'branch' => 'required|string',
        ]);

        $car = new Car();
        $car->brand = $request->brand;
        $car->model = $request->model;
        $car->engine = $request->engine;
        $car->quantity = $request->quantity;
        $car->price_per_day = $request->price_per_day;
        $car->stars = $request->stars;
        $car->description = $request->description;
        $car->insurance_status = $request->insurance_status;
        $car->status = $request->status;
        $car->branch = $request->branch;
        $car->save();

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('images', 'public');
                $car->images()->create(['path' => $path]);
            }
        }

       // Handle Video Upload
    if ($request->hasFile('video')) {
        $video = $request->file('video');
        $videoPath = $video->store('images', 'public');
        $car->video_path = $videoPath;
    }

        return redirect()->route('cars.index')->with('success', 'Car details created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Car $car)
    {
        $car->load('images', 'reservations');
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
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'engine' => 'required|string|max:255',
            'quantity' => 'required|integer',
            'price_per_day' => 'required|numeric',
            'status' => 'required|string',
            'branch' => 'required|string',
            'description' => 'nullable|string',
            'images.*' => 'nullable|mimes:jpeg,png,jpg,gif|max:10240', // Validate multiple images
            'video' => 'nullable|mimes:mp4|max:51200', // Validate video file
        ]);

        $car->update([
            'brand' => $request->brand,
            'model' => $request->model,
            'engine' => $request->engine,
            'quantity' => $request->quantity,
            'price_per_day' => $request->price_per_day,
            'description' => $request->input('description'),
            'status' => $request->status,
            'branch' => $request->branch,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = $request->brand . '-' . $request->model . '-' . $request->engine . '-' . Str::random(10) . '.' . $image->extension();
                $path = $image->storeAs('images/cars', $imageName, 'public');

                CarImage::create([
                    'car_id' => $car->id,
                    'image_path' => $path,
                ]);
            }
        }

        if ($request->hasFile('video')) {
            // Delete old video
            if ($car->video_path) {
                Storage::disk('public')->delete($car->video_path);
            }

            $video = $request->file('video');
            $videoName = $request->brand . '-' . $request->model . '-' . Str::random(10) . '.' . $video->extension();
            $path = $video->storeAs('videos/cars', $videoName, 'public');

            $car->video_path = $path;
            $car->save();
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
            Storage::disk('public')->delete('images/cars/' . $filename);
            $image->delete();
        }
        $car->delete();

        return redirect()->route('cars.index')->with('success', 'Car deleted successfully.');
    }

    /**
     * Reserve a car.
     */
    public function reserve(Request $request)
    {
        $request->validate([
            'car_id' => 'required|exists:cars,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $car = Car::find($request->car_id);

        // Check for existing reservations
        $existingReservation = $car->reservations()
            ->where('end_date', '>=', $request->start_date)
            ->where('start_date', '<=', $request->end_date)
            ->exists();

        if ($existingReservation) {
            return back()->withErrors(['The car is already reserved for these dates.']);
        }

        $reservation = new Reservation();
        $reservation->car_id = $request->car_id;
        $reservation->start_date = $request->start_date;
        $reservation->end_date = $request->end_date;
        $reservation->save();

        return redirect()->route('reservations.index')->with('success', 'Reservation created successfully.');
    }

    /**
     * Show the rating form for a car.
     */
    public function showRatingForm(Car $car)
    {
        $car->load('ratings');
        return view('cars.rating', compact('car'));
    }

    /**
     * Process the rating form submission.
     */
    public function rate(Request $request, Car $car)
    {
        // Process form submission (e.g., save rating to database)
        $rating = $request->input('rating');

        // Redirect to the rating form with the car ID
        return redirect()->route('car.rating', ['car' => $car->id]);
    }

    private function getAvailableDates(Car $car, array $reservedDates)
    {
        $availableDates = [];
        $today = Carbon::now();

        // Example: Generate dates for the next 30 days
        for ($i = 0; $i < 30; $i++) {
            $date = $today->copy()->addDays($i)->toDateString();

            if (!in_array($date, $reservedDates)) {
                $availableDates[] = $date;
            }
        }

        return $availableDates;
    }
}


    // public function searchCars(Request $request)
    // {
    //     // Retrieve inputs
    //     $location = $request->input('location');
    //     $pickupDate = $request->input('pickup_date');
    //     $pickupTime = $request->input('pickup_time');
    //     $returnDate = $request->input('return_date');
    //     $returnTime = $request->input('return_time');

    //     // Debugging output
    //     Log::info('Search Cars Input:', [
    //         'location' => $location,
    //         'pickupDate' => $pickupDate,
    //         'pickupTime' => $pickupTime,
    //         'returnDate' => $returnDate,
    //         'returnTime' => $returnTime,
    //     ]);

    //     // Map location to branch
    //     $branch = $this->getBranchByLocation($location);

    //     // Convert pickup and return dates to DateTime objects for accurate comparison
    //     try {
    //         $pickupDateTime = new \DateTime("{$pickupDate} {$pickupTime}");
    //         $returnDateTime = new \DateTime("{$returnDate} {$returnTime}");
    //     } catch (\Exception $e) {
    //         return redirect()->back()->with('error', 'Invalid date format.');
    //     }

    //     try {
    //         // Perform search logic based on location, date, and time
    //         $cars = Car::where('branch', $branch)
    //                     ->where('available_from', '<=', $pickupDateTime->format('Y-m-d H:i:s'))
    //                     ->where('available_until', '>=', $returnDateTime->format('Y-m-d H:i:s'))
    //                     ->get();
    //     } catch (\Exception $e) {
    //         Log::error('Error querying cars:', ['exception' => $e->getMessage()]);
    //         return redirect()->back()->with('error', 'An error occurred while searching for cars.');
    //     }

    //     return view('cars', [
    //         'cars' => $cars,
    //         'pickupDate' => $pickupDateTime->format('Y-m-d'),
    //         'pickupTime' => $pickupTime,
    //         'returnDate' => $returnDateTime->format('Y-m-d'),
    //         'returnTime' => $returnTime,
    //     ]);
    // }

    // private function getBranchByLocation($location)
    // {
    //     // Map location to branch
    //     $branches = [
    //         'Pasay' => 'Marikina',
    //         'Isabela' => 'Isabela',
    //     ];

    //     return $branches[$location] ?? 'Default Branch';
    // }


