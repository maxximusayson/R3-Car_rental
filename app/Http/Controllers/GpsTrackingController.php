<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http; // Import the Http facade

class GpsTrackingController extends Controller
{
    private $dataDir = 'gps_data/';
    private $timeoutDuration = 60; // 1 minute timeout duration
    private $proxyServerUrl = 'https://r3garagecarrental.online/gps.php'; // Define your proxy server URL

    /**
     * Display the GPS Tracking page.
     *
     * @return \Illuminate\View\View
     */
    public function track()
    {
        // Return the GPS tracking page
        return view('index');
    }

    /**
     * Store GPS data sent via POST request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
   

    /**
     * Serve the latest GPS data for all devices.
     *
     * @return \Illuminate\Http\JsonResponse
     */
   

    /**
     * Fetch GPS data from the proxy server.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchGpsFromProxy()
{
    Log::info('fetchGpsFromProxy method was called');

    $proxyServerUrl = 'https://r3garagerental.online/gps.php'; // Your proxy server URL

    // Attempt to get the response from the proxy server
    $response = @file_get_contents($proxyServerUrl);

    header('Content-Type: application/json');
    
    if ($response !== false) {
        Log::info('Successfully fetched data from proxy server.');
        return response()->json(json_decode($response), 200);
    } else {
        Log::error('Failed to fetch data from proxy server.');
        return response()->json([
            'gps_id' => 'N/A',
            'wifi_status' => 'No device found',
            'latitude' => 0.0,
            'longitude' => 0.0,
            'speed' => 0.0,
            'satellites' => 0,
            'gps_status' => 'No Signal'
        ], 500);
    }
}
    
}
