<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CalculateGpsDistance extends Command
{
    protected $signature = 'gps:calculate-distance {filepath}';
    protected $description = 'Calculate the total distance from a GPS log file';

    public function handle()
    {
        $filepath = $this->argument('filepath');

        if (!file_exists($filepath)) {
            $this->error("File not found: $filepath");
            return;
        }

        $gps_log = file($filepath, FILE_IGNORE_NEW_LINES);
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

        $this->info("Total Distance: " . $total_kilometers . " km");
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

        $a = sin($dlat / 2) * sin($dlat / 2) +
            cos($lat1_rad) * cos($lat2_rad) *
            sin($dlon / 2) * sin($dlon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earth_radius * $c;
    }
}
