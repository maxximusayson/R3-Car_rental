<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GpsTrackingController extends Controller
{
    private $dataDir = 'gps_data/';
    private $timeoutDuration = 60; // 1 minute timeout duration
    private $proxyServerUrl = 'https://r3garagecarrental.online/gps.php'; // Define your proxy server URL

    /**
     * Display the GPS Tracking main page (index).
     *
     * @return \Illuminate\View\View
     */
    public function tracking1()
    {
        // Loads the main index view for GPS tracking located at resources/views/index.blade.php
        return view('index');
    }

    /**
     * Method to load GPS tracking2 view (index2).
     *
     * @return \Illuminate\View\View
     */
    public function tracking2()
    {
        // This will load the index2 view located in resources/views/index2.blade.php
        return view('index2');
    }

    /**
     * Fetch GPS data from the proxy server.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchGpsFromProxy()
    {
        Log::info('fetchGpsFromProxy method was called');

        $proxyServerUrl = 'http:/r3garagerental.online/gps.php'; 
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
