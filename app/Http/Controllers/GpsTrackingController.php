<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GpsTrackingController extends Controller
{
    /**
     * Display the GPS Tracking page.
     *
     * @return \Illuminate\View\View
     */
    public function track()
    {
        // You can pass any data to the view here if needed
        return view('gps_tracking.track');
    }
}
