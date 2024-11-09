<?php

// Path to the file containing the GPS log (replace with your actual file path)
$filepath = 'GPS02_LOG_VIOS_NOVEMBER_07.json';

// Read the file and get the contents
$gps_log = file($filepath, FILE_IGNORE_NEW_LINES);

// Initialize an array to store coordinates
$coordinates = [];

// Parse the log entries and extract the coordinates
foreach ($gps_log as $line) {
    $data = json_decode($line, true);
    if ($data && isset($data['latitude'], $data['longitude'])) {
        $coordinates[] = [
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude']
        ];
    }
}

// Calculate the total distance using the Haversine formula
$total_kilometers = 0;
for ($i = 0; $i < count($coordinates) - 1; $i++) {
    $lat1 = $coordinates[$i]['latitude'];
    $lon1 = $coordinates[$i]['longitude'];
    $lat2 = $coordinates[$i + 1]['latitude'];
    $lon2 = $coordinates[$i + 1]['longitude'];

    // Calculate the distance between two points
    $distance = haversine_distance($lat1, $lon1, $lat2, $lon2);
    $total_kilometers += $distance;
}

// Output the total distance
echo "Total Distance: " . $total_kilometers . " km";

// Haversine formula to calculate the distance between two GPS coordinates
function haversine_distance($lat1, $lon1, $lat2, $lon2) {
    $earth_radius = 6371; // Earth radius in kilometers

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

    return $earth_radius * $c; // Return distance in kilometers
}

?>
