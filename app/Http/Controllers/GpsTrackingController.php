<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class GpsTrackingController extends Controller
{
    private $dataDir = 'gps_data/';
    private $timeoutDuration = 60; // 1 minute timeout duration
    private $proxyServerUrl = 'https://r3garagerental.online/gps.php'; // Define your proxy server URL

    /**
     * Display the GPS Tracking page (tracking1).
     *
     * @return \Illuminate\View\View
     */
    public function tracking1()
    {
        return view('index'); // Make sure 'tracking1.blade.php' exists in the 'resources/views' directory
    }
    public function tracking2()
    {
        return view('index2'); // Make sure 'tracking1.blade.php' exists in the 'resources/views' directory
    }

    /**
     * Display the GPS Tracking page (tracking2).
     *
     * @return \Illuminate\View\View
     */
   

    /**
     * Fetch GPS data from the proxy server.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchGpsFromProxy()
    {
        Log::info('fetchGpsFromProxy method was called');

        try {
            $response = Http::timeout($this->timeoutDuration)->get($this->proxyServerUrl);

            if ($response->successful()) {
                Log::info('Successfully fetched data from proxy server.');
                return response()->json($response->json(), 200);
            } else {
                Log::error('Failed to fetch data from proxy server. Server responded with status: ' . $response->status());
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
        } catch (\Exception $e) {
            Log::error('Error fetching data from proxy server: ' . $e->getMessage());
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
