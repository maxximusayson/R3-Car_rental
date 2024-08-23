<?php

namespace App\Http\Controllers;

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
        return view('reservation.create', compact('car', 'user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $car_id)
{
    // Validate the request including the file uploads
    $request->validate([
        'start_date' => 'required|date|after_or_equal:today',
        'end_date' => 'required|date|after:start_date',
        'driver_license' => 'required|file|mimes:jpg,png,pdf|max:2048',
        'valid_id' => 'required|file|mimes:jpg,png,pdf|max:2048',
    ]);

    $car = Car::findOrFail($car_id);
    $user = auth()->user();

    $start = Carbon::parse($request->start_date);
    $end = Carbon::parse($request->end_date);

    // Handle the file uploads and store them in the public/uploads directory
    $driverLicensePath = $request->file('driver_license')->move(public_path('uploads/driver_license'), $request->file('driver_license')->getClientOriginalName());
    $validIdPath = $request->file('valid_id')->move(public_path('uploads/valid_id'), $request->file('valid_id')->getClientOriginalName());

    // Create the reservation
    $reservation = new Reservation();
    $reservation->user_id = $user->id;
    $reservation->car_id = $car->id;
    $reservation->start_date = $start;
    $reservation->end_date = $end;
    $reservation->days = $start->diffInDays($end) + 1; // Assuming you want to include the last day
    $reservation->price_per_day = $car->price_per_day;
    $reservation->total_price = $reservation->days * $reservation->price_per_day;
    $reservation->status = 'Pending';
    $reservation->payment_method = 'Cash';
    $reservation->payment_status = 'Pending';
    $reservation->driver_license = 'uploads/driver_license/' . $request->file('driver_license')->getClientOriginalName(); // Save the relative path
    $reservation->valid_id = 'uploads/valid_id/' . $request->file('valid_id')->getClientOriginalName(); // Save the relative path
    $reservation->save();

    // Track creation in the audit trail
    Audit::create([
        'user_id' => $user->id,
        'action' => 'Created reservation',
        'details' => 'Created a reservation for car ' . $car->model,
        'reservation_id' => $reservation->id,
    ]);

    // Store reservation details in session
    $request->session()->put('reservation', [
        'full_name' => $user->name,
        'email' => $user->email,
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'car_brand' => $car->brand,
        'car_model' => $car->model,
        'price_per_day' => $car->price_per_day,
        'payment_method' => 'Cash',
        'car_id' => $car->id,
        'driver_license' => 'uploads/driver_license/' . $request->file('driver_license')->getClientOriginalName(),
        'valid_id' => 'uploads/valid_id/' . $request->file('valid_id')->getClientOriginalName(),
    ]);

    return redirect()->route('reservation.thankyou');
}


    
    

    

    /**
     * Display the specified resource.
     */
    public function showReservation($id)
    {
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
    $reservation = \App\Models\Reservation::findOrFail($id);
    return view('reservation.show', compact('reservation'));
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
        unlink(public_path($reservation->driver_license));
    }
    if ($reservation->valid_id) {
        unlink(public_path($reservation->valid_id));
    }

    // Delete the reservation
    $reservation->delete();

    return redirect()->back()->with('success', 'Reservation deleted successfully.');
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


}
