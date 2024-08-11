<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\BookingCreated;
use App\Notifications\NewReservationNotification;
use Carbon\Carbon;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reservations = Reservation::with('user', 'car')->paginate(10);
        $currentUsers = User::all();
        $currentBookings = Reservation::where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->select('id', 'user_id', 'start_date', 'end_date')
            ->get();
        
        // Fetch all reservations
        $reservations = Reservation::all();
        
        // Define an empty array to store calendar events
        $calendarEvents = [];
    
        // Loop through each reservation and create a calendar event
        foreach ($reservations as $reservation) {
            $calendarEvents[] = [
                'title' => $reservation->user->name . ' - ' . $reservation->car->brand . ' ' . $reservation->car->model,
                'start' => $reservation->start_date,
                'end' => $reservation->end_date,
            ];
        }
    
        // Fetch notifications for the authenticated user
        $notifications = auth()->user()->notifications;
    
        return view('reservation.index', compact('reservations', 'currentUsers', 'currentBookings', 'calendarEvents', 'notifications'));
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
        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
        ]);
    
        $car = Car::find($car_id);
        $user = auth()->user(); // Correctly get the authenticated user
    
        $start = Carbon::parse($request->start_date);
        $end = Carbon::parse($request->end_date);
    
        $reservation = new Reservation();
        $reservation->user_id = $user->id; // Set user_id directly
        $reservation->car_id = $car->id; // Set car_id directly
        $reservation->start_date = $start;
        $reservation->end_date = $end;
        $reservation->days = $start->diffInDays($end);
        $reservation->price_per_day = $car->price_per_day;
        $reservation->total_price = $reservation->days * $reservation->price_per_day;
        $reservation->status = 'Pending';
        $reservation->payment_method = 'Cash'; // Only set 'Cash'
        $reservation->payment_status = 'Pending';
        $reservation->save();
    
        $reservation->driver_license = $user->driver_license;
        $reservation->proof_of_billing = $user->proof_of_billing;
        $reservation->save(); // Save changes after setting additional fields
    
        $car->status = 'Reserved';
        $car->save();
    
        // Store the reservation details in the session
        $request->session()->put('reservation', [
            'full_name' => $user->name,
            'email' => $user->email,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'car_brand' => $car->brand,
            'car_model' => $car->model,
            'price_per_day' => $car->price_per_day,
            'payment_method' => 'Cash', // Assuming the payment method is 'Cash'
            'car_id' => $car->id, // Include car_id
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
}
