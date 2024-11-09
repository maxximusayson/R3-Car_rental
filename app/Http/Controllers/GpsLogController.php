<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class GpsLogController extends Controller
{
    public function calculateTotalDistance()
    {
        $filepath = public_path('gps_logs/GPS02_LOG_VIOS_NOVEMBER_07.json');

        if (!File::exists($filepath)) {
            return response()->json(['error' => 'GPS log file not found'], 404);
        }

        $gps_log = File::lines($filepath);
        $coordinates = [];

        foreach ($gps_log as $line) {
            $data = json_decode($line, true);
            if ($data && isset($data['latitude'], $data['longitude'])) {
                $coordinates[] = [
                    'latitude' => $data['latitude'],
                    'longitude' => $data['longitude']
                ];
            }
        }

        $total_kilometers = 0;
        for ($i = 0; $i < count($coordinates) - 1; $i++) {
            $lat1 = $coordinates[$i]['latitude'];
            $lon1 = $coordinates[$i]['longitude'];
            $lat2 = $coordinates[$i + 1]['latitude'];
            $lon2 = $coordinates[$i + 1]['longitude'];
            $distance = $this->haversineDistance($lat1, $lon1, $lat2, $lon2);
            $total_kilometers += $distance;
        }

        return response()->json([
            'total_distance_km' => $total_kilometers
        ]);
    }

    private function haversineDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earth_radius = 6371;
        $lat1_rad = deg2rad($lat1);
        $lon1_rad = deg2rad($lon1);
        $lat2_rad = deg2rad($lat2);
        $lon2_rad = deg2rad($lon2);

        $dlat = $lat2_rad - $lat1_rad;
        $dlon = $lon2_rad - $lon1_rad;

        $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1_rad) * cos($lat2_rad) * sin($dlon / 2) * sin($dlon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earth_radius * $c;
    }
}
