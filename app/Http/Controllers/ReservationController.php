<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Models\Audit;
use App\Models\Car;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\BookingCreated;
use App\Notifications\NewReservationNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;


class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $cars = Car::with('images')->get();
    
    // Prepare availability data
    $availability = [];
    foreach ($cars as $car) {
        $availability[$car->id] = $this->getAvailableDates($car);
    }

    // Fetch reservations
    $reservations = Reservation::with('user', 'car')->paginate(10);
    $currentUsers = User::all();
    $currentBookings = Reservation::where('start_date', '<=', now())
        ->where('end_date', '>=', now())
        ->select('id', 'user_id', 'start_date', 'end_date')
        ->get();

    // Define an empty array to store calendar events
    $calendarEvents = [];
    foreach ($reservations as $reservation) {
        $calendarEvents[] = [
            'title' => $reservation->user->name . ' - ' . $reservation->car->brand . ' ' . $reservation->car->model,
            'start' => $reservation->start_date,
            'end' => $reservation->end_date,
        ];
    }

    // Fetch notifications for the authenticated user
    $notifications = auth()->user() ? auth()->user()->notifications : [];

    return view('reservation.index', compact('cars', 'availability', 'reservations', 'currentUsers', 'currentBookings', 'calendarEvents', 'notifications'));
}


    /**
     * Show the form for creating a new resource.
     */
    public function create($car_id)
    {
        $user = auth()->user();
        $car = Car::find($car_id);
    
        // Fetch all booked dates for this car
        $bookedDates = Reservation::where('car_id', $car_id)
            ->pluck('start_date', 'end_date')
            ->flatMap(function($startDate, $endDate) {
                $period = \Carbon\CarbonPeriod::create($startDate, $endDate);
                return $period->toArray();
            })->map(function($date) {
                return $date->format('Y-m-d');
            })->toArray();
    
        return view('reservation.create', compact('car', 'user', 'bookedDates'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $car_id)
    {
        // Validate the request including the file uploads and the time fields
        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i', // Validate start time
            'end_date' => 'required|date|after:start_date',
            'end_time' => 'required|date_format:H:i', // Validate end time
            'driver_license' => 'required|file|mimes:jpg,png,pdf|max:2048',
            'valid_id' => 'required|file|mimes:jpg,png,pdf|max:2048',
            'cash_amount' => 'nullable|numeric|min:0', // Validate cash amount
            'payment_method' => 'required|in:GCash,PayPal,Cash', // Validate the payment method

        ]);
    
        // Fetch the car and user data
        $car = Car::findOrFail($car_id);
        $user = auth()->user();
    
        // Parse the start and end dates and times
        $start = Carbon::parse($request->start_date . ' ' . $request->start_time);
        $end = Carbon::parse($request->end_date . ' ' . $request->end_time);
    
        // Calculate total price based on the rental period
        $totalDays = $start->diffInDays($end) + 1; // Include the last day
        $pricePerDay = $car->price_per_day; // Daily rental price
        $totalPrice = $totalDays * $pricePerDay; // Total price for the rental
    
        // Calculate down payment
        $downpaymentPerDay = 1000; // Fixed down payment per day
        $totalDownpayment = $totalDays * $downpaymentPerDay; // Total down payment for the rental
    
        // Get the amount paid by the user
        $cashPaid = $request->input('cash_amount', 0); // Default to 0 if not provided
    
        // Calculate the remaining balance
        $remainingBalance = $totalPrice - $totalDownpayment - $cashPaid; // Remaining balance calculation
    
        // Store the driver's license in public/uploads/docu/driver_license
        $driverLicensePath = $request->file('driver_license')->move(public_path('uploads/docu/driver_license'), $request->file('driver_license')->getClientOriginalName());
    
        // Store the valid ID in public/uploads/docu/valid_id
        $validIdPath = $request->file('valid_id')->move(public_path('uploads/docu/valid_id'), $request->file('valid_id')->getClientOriginalName());
    
        // Create the reservation
        $reservation = new Reservation();
        $reservation->user_id = $user->id;
        $reservation->car_id = $car->id;
        $reservation->start_date = $start;
        $reservation->end_date = $end;
        $reservation->start_time = $request->start_time; // Save start time
        $reservation->end_time = $request->end_time; // Save end time
        $reservation->days = $totalDays;
        $reservation->price_per_day = $pricePerDay;
        $reservation->total_price = $totalPrice;
        $reservation->amount_paid = $cashPaid;
        $reservation->remaining_balance = max(0, $remainingBalance); // Ensure remaining balance is not negative
        $reservation->status = 'Pending';
        $reservation->payment_method = 'Cash';
        $reservation->payment_status = ($remainingBalance > 0) ? 'Partial' : 'Paid';
        $reservation->driver_license = 'uploads/docu/driver_license/' . $request->file('driver_license')->getClientOriginalName();
        $reservation->valid_id = 'uploads/docu/valid_id/' . $request->file('valid_id')->getClientOriginalName();
        $reservation->expiration_time = now()->addHours(24); // Store expiration time
        $reservation->save();
    
        // Store reservation details in session
        $request->session()->put('reservation', [
            'id' => $reservation->id, // Ensure the ID is included
            'full_name' => $user->name,
            'email' => $user->email,
            'start_date' => $request->start_date,
            'start_time' => $request->start_time,
            'end_date' => $request->end_date,
            'end_time' => $request->end_time,
            'car_brand' => $car->brand,
            'car_model' => $car->model,
            'price_per_day' => $car->price_per_day,
            'total_price' => $totalPrice, // Add the total price for clarity
            'total_downpayment' => $totalDownpayment, // Store the total down payment
            'payment_method' => 'Cash',
            'amount_paid' => $cashPaid,
            'remaining_balance' => max(0, $remainingBalance), // Store remaining balance correctly
            'car_id' => $car->id,
            'driver_license' => 'uploads/docu/driver_license/' . $request->file('driver_license')->getClientOriginalName(),
            'valid_id' => 'uploads/docu/valid_id/' . $request->file('valid_id')->getClientOriginalName(),
        ]);
    
        // Redirect to the thank-you page
        return redirect()->route('reservation.thankyou');
    }
    /**
     * Display the specified resource.
     */
    public function showReservation($id)
    {
        $reservations = Reservation::with('car')->get(); // Adjust the query to your needs

        $reservation = Reservation::findOrFail($id);
        return view('edit-payment-method', compact('reservation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reservation $reservation)
    {
        //
    }

    // Edit and Update Payment status
    public function editPayment(Reservation $reservation)
    {
        return view('admin.updatePayment', compact('reservation'));
    }

    public function updatePayment(Reservation $reservation, Request $request)
    {
        $reservation->payment_status = $request->payment_status;
        $reservation->save();
        return redirect()->route('adminDashboard');
    }

    // Edit and Update Reservation Status
    public function editStatus(Reservation $reservation)
    {
        return view('admin.updateStatus', compact('reservation'));
    }

    public function updateStatus(Reservation $reservation, Request $request)
    {
        $reservation->status = $request->status;
        $car = $reservation->car;
        if ($request->status == 'Ended' || $request->status == 'Canceled') {
            $car->status = 'Available';
            $car->save();
        }
        $reservation->save();
    
        // Track update in the audit trail
        Audit::create([
            'user_id' => auth()->user()->id,
            'action' => 'Updated reservation status',
            'details' => 'Updated reservation status to ' . $request->status,
            'reservation_id' => $reservation->id,
        ]);
    
        return redirect()->route('adminDashboard');
    }

    public function editPaymentMode(Reservation $reservation)
    {
        $paymentModes = ['GCash', 'Cash']; // Payment mode options
        return view('edit_payment_mode', compact('reservation', 'paymentModes'));
    }

    public function updatePaymentMode(Request $request, Reservation $reservation)
    {
        // Validate the incoming request
        $request->validate([
            'payment_mode' => 'required|in:GCash,Cash', // Ensure payment mode is either GCash or Cash
        ]);

        // Update the reservation's payment mode
        $reservation->payment_method = $request->input('payment_mode');
        $reservation->save();

        // Redirect back to the reservation edit page or any other appropriate page
        return redirect()->back()->with('success', 'Payment mode updated successfully.');
    }

    public function cancelReservation($id)
    {
        $reservation = Reservation::findOrFail($id);

        $reservation->status = 'Canceled';
        $reservation->save();

        return redirect()->back()->with('success', 'Reservation canceled successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reservation $reservation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyReservation(Reservation $reservation)
    {
        // Delete the reservation
        $reservation->delete();
        
        // Redirect back with a success message
        return redirect()->back()->with('success', 'Reservation deleted successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroyUser(User $user)
    {
        // Perform deletion logic here
        $user->delete();

        // Redirect or return a response as needed
        return redirect()->back()->with('success', 'User deleted successfully.');
    }

    public function showPayments()
    {
        return view('reservation.payments');
    }

    public function showPaymentOptions(Request $request)
    {
        $paymentMethod = $request->query('method');

        if ($paymentMethod === 'gcash') {
            return redirect()->to('https://paymongo.com/gcash');
        } elseif ($paymentMethod === 'cash') {
            // Render the view for cash payment
            return view('reservation.payments', ['paymentMethod' => 'cash']);
        }

        // Handle invalid or no payment method
        return redirect()->route('cars.index')->withErrors('Invalid payment method.');
    }

    public function thankyou(Request $request)
    {
        $reservation = $request->session()->get('reservation');
        return view('thankyou', compact('reservation'));
    }
    public function show($id)
{
    // Fetch the reservation based on the given ID
    $reservation = Reservation::findOrFail($id);

    // Retrieve the remaining balance from the reservation
    $totalPaid = $reservation->payments()->sum('amount_paid');
    $remainingBalance = $reservation->total_price - $totalPaid;

    // Pass the reservation and remaining balance to the view
    return view('reservation.show', compact('reservation', 'remainingBalance'));
}

protected function getAvailableDates($car)
{
    // Fetch all reservations for the car
    $reservations = Reservation::where('car_id', $car->id)
        ->where('end_date', '>=', now())
        ->get();

    $availableDates = [];
    
    // Get all dates between today and 30 days from now
    $startDate = Carbon::today();
    $endDate = Carbon::today()->addDays(30);

    while ($startDate->lte($endDate)) {
        $isAvailable = true;

        foreach ($reservations as $reservation) {
            if ($startDate->between($reservation->start_date, $reservation->end_date)) {
                $isAvailable = false;
                break;
            }
        }

        if ($isAvailable) {
            $availableDates[] = $startDate->format('Y-m-d');
        }

        $startDate->addDay();
    }

    return $availableDates;
}
public function destroy($id)
{
    $reservation = Reservation::findOrFail($id);

    // Track deletion in the audit trail before deletion
    Audit::create([
        'user_id' => auth()->user()->id,
        'action' => 'Deleted reservation',
        'details' => 'Deleted reservation for car ' . $reservation->car->model,
        'reservation_id' => $reservation->id,
    ]);

    // Delete the uploaded files if necessary
    if ($reservation->driver_license) {
        $driverLicensePath = public_path($reservation->driver_license);
        if (file_exists($driverLicensePath)) {
            unlink($driverLicensePath);
        } else {
            Log::error('Driver license file not found: ' . $driverLicensePath);
        }
    }

    if ($reservation->valid_id) {
        $validIdPath = public_path($reservation->valid_id);
        if (file_exists($validIdPath)) {
            unlink($validIdPath);
        } else {
            Log::error('Valid ID file not found: ' . $validIdPath);
        }
    }

    // Delete the reservation
    $reservation->delete();

    return redirect()->route('reservations.index')->with('success', 'Reservation rejected and deleted successfully.');
}



// Method to export notifications and bookings to CSV
public function exportNotifications()
{
    $notifications = auth()->user()->notifications;
    $upcomingBookings = Reservation::with('user', 'car')
        ->where('start_date', '>', now())
        ->get();

    $filename = 'notifications_and_bookings.csv';
    $handle = fopen($filename, 'w');
    fputcsv($handle, ['Type', 'Message', 'User', 'Car', 'Start Date', 'End Date', 'Created At']);

    foreach ($notifications as $notification) {
        fputcsv($handle, [
            'Notification',
            $notification->data['feedback'] ?? 'No message available',
            '',
            '',
            '',
            '',
            $notification->created_at->format('Y-m-d H:i:s'),
        ]);
    }

    foreach ($upcomingBookings as $booking) {
        fputcsv($handle, [
            'Booking',
            'Upcoming booking for ' . $booking->user->name . ': ' . $booking->car->model,
            $booking->user->name,
            $booking->car->brand . ' ' . $booking->car->model,
            $booking->start_date->format('Y-m-d H:i:s'),
            $booking->end_date->format('Y-m-d H:i:s'),
            $booking->created_at->format('Y-m-d H:i:s'),
        ]);
    }

    fclose($handle);

    return Response::download($filename)->deleteFileAfterSend(true);
}

// Method to import notifications and bookings from CSV
public function importNotifications(Request $request)
{
    $file = $request->file('csv_file');

    if ($file) {
        $path = $file->getRealPath();
        $data = array_map('str_getcsv', file($path));

        foreach ($data as $index => $row) {
            if ($index === 0) {
                continue; // Skip header row
            }

            if ($row[0] === 'Notification') {
                auth()->user()->notifications()->create([
                    'data' => ['feedback' => $row[1]],
                    'created_at' => $row[6],
                ]);
            }

            if ($row[0] === 'Booking') {
                $user = User::where('name', $row[2])->first();
                $car = Car::where('model', $row[3])->first();
                if ($user && $car) {
                    Reservation::create([
                        'user_id' => $user->id,
                        'car_id' => $car->id,
                        'start_date' => $row[4],
                        'end_date' => $row[5],
                        'created_at' => $row[6],
                        'status' => 'Upcoming',
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'CSV file imported successfully.');
    }

    return redirect()->back()->with('error', 'Please upload a valid CSV file.');
}

public function approve($id)
{
    $reservation = Reservation::findOrFail($id);
    $reservation->status = 'Confirmed';
    $reservation->save();

    // Create an alert for the user
    Alert::create([
        'user_id' => $reservation->user_id,
        'message' => "Your reservation for " . $reservation->car->brand . " " . $reservation->car->model . " has been approved.",
    ]);

    return redirect()->back()->with('success', 'Reservation approved successfully.');
}

public function reject($id)
{
    $reservation = Reservation::findOrFail($id);
    $reservation->status = 'Rejected';
    $reservation->save();

    return redirect()->back()->with('success', 'Reservation rejected successfully.');
}



}
