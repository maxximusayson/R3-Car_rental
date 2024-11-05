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
    public function showCars()
    {
        // Fetch the cars from the database (ensure you include the images if needed)
        $cars = Car::with('images')->get(); // Eager load images to avoid N+1 query issue

        // Pass the cars variable to the view
        return view('home', compact('cars'));
    }

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
            'images.*' => 'nullable|mimes:jpg,jpeg,png,gif|max:10240', // Validate images
            'video' => 'nullable|mimes:mp4,avi,mov|max:51200', // Validate video file
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
        $car->description = $request->description;
        $car->insurance_status = $request->insurance_status;
        $car->status = $request->status;
        $car->branch = $request->branch;
        $car->save();
    
        // Handle image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . $image->getClientOriginalName();
                $path = $image->move(public_path('images/cars'), $imageName);
                $car->images()->create(['image_path' => 'images/cars/' . $imageName]);
            }
        }
    
         // Handle video upload (optional)
    if ($request->hasFile('video')) {
        $video = $request->file('video');
        $videoName = time() . '_' . $video->getClientOriginalName();
        $video->move(public_path('videos/cars'), $videoName);
        $car->video_path = 'videos/cars/' . $videoName;
        $car->save();
    }
    
        return redirect()->route('cars.index')->with('success', 'Car details created successfully.');
    }
    
    
    /**
     * Display the specified resource.
     */
    public function show(Car $car)
{
    $car->load(['images', 'reservations', 'ratings.user']);
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
        'video' => 'nullable|mimes:mp4,avi,mov|max:51200', // Validate video file
    ]);

    // Update car details
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

    // Handle image uploads
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

   // Handle video upload
    if ($request->hasFile('video')) {
        // Delete the old video if it exists
        if ($car->video_path) {
            $oldVideoPath = public_path($car->video_path);
            if (file_exists($oldVideoPath)) {
                unlink($oldVideoPath);
            }
        }

        // Upload new video
        $video = $request->file('video');
        $videoName = time() . '_' . $video->getClientOriginalName();
        $video->move(public_path('videos/cars'), $videoName);
        $car->video_path = 'videos/cars/' . $videoName;
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
    
        // Calculate payment details
        $start = Carbon::parse($request->start_date);
        $end = Carbon::parse($request->end_date);
        $days = $start->diffInDays($end) + 1; // Including the last day
    
        $totalRent = $days * $car->price_per_day; // Total rent based on the number of days
        $dpPerDay = 1000; // Downpayment per day is fixed at 1k
        $totalDownpayment = $days * $dpPerDay; // Total downpayment (e.g., 2 days = 2,000 PHP)
        $remainingBalance = $totalRent - $totalDownpayment; // Remaining balance after downpayment
    
        // Create the reservation
        $reservation = new Reservation();
        $reservation->car_id = $request->car_id;
        $reservation->user_id = auth()->user()->id; // Ensure to assign the user making the reservation
        $reservation->start_date = $request->start_date;
        $reservation->end_date = $request->end_date;
        $reservation->total_price = $totalRent;
        $reservation->downpayment = $totalDownpayment;
        $reservation->remaining_balance = $remainingBalance;
        $reservation->save();
    
        // Pass payment details to the session for the thank you page
        $request->session()->put('reservation', [
            'car_brand' => $car->brand,
            'car_model' => $car->model,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_price' => $totalRent,
            'downpayment' => $totalDownpayment,
            'remaining_balance' => $remainingBalance,
        ]);

        
    
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