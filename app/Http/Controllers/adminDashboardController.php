<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Car;
use App\Models\Reservation;
use App\Models\Notification; // Ensure this line is present
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class adminDashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $clients = User::where('role', 'client')->count();
        $admins = User::where('role', 'admin')->count();
        $cars = Car::all();

        $reservations = Reservation::paginate(8);

        // Fetch reservations data for the chart
        $reservationsData = $this->getReservationsDataForChart();

        // Fetch current users
        $currentUsers = User::where('role', 'client')->get();

        // Fetch history of bookings
        $bookingHistory = $this->getBookingHistory();

        // Fetch upcoming bookings
        $upcomingBookings = $this->getUpcomingBookings();

        // Fetch notifications
        $notifications = Notification::all(); // Fetch notifications

        return view('admin.adminDashboard', compact('clients', 'admins', 'cars', 'reservations', 'reservationsData', 'currentUsers', 'bookingHistory', 'upcomingBookings', 'notifications'));
    }

    private function getReservationsDataForChart()
    {
        $startDate = Carbon::now()->subYear();
        $endDate = Carbon::now();
        $reservationsData = Reservation::select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as total'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('total', 'month')
            ->toArray();

        $labels = [];
        $data = [];

        // Prepare labels and data for the chart
        for ($month = 1; $month <= 12; $month++) {
            $labels[] = Carbon::createFromDate(null, $month, 1)->format('F');
            $data[] = $reservationsData[$month] ?? 0;
        }

        return compact('labels', 'data');
    }

    private function getBookingHistory()
    {
        return Reservation::with('user', 'car')->orderBy('created_at', 'desc')->get();
    }

    private function getUpcomingBookings()
    {
        return Reservation::with('user', 'car')
            ->where('start_date', '>', Carbon::now())
            ->orderBy('start_date', 'asc')
            ->get();
    }

    public function auditTrail()
    {
        $audits = Audit::with('user')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.audit-trail', compact('audits'));
    }
    public function dashboard()
    {
        $notifications = auth()->user()->notifications;
        $upcomingBookings = \App\Models\Reservation::where('status', 'Upcoming')
                            ->orderBy('start_date', 'asc')
                            ->get();
    
        return view('dashboard', compact('notifications', 'upcomingBookings'));
    }
    
}
