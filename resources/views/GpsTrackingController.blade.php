<?php

$proxyServerUrl = 'https://r3garagecarrental.online/gps.php'; // Replace with your proxy server URL


$response = file_get_contents($proxyServerUrl);
if ($response !== false) {
    header('Content-Type: application/json');
    echo $response;
} else {
    header('Content-Type: application/json');
    echo json_encode([
        'gps_id' => 'N/A',
        'wifi_status' => 'No device found',
        'latitude' => 0.0,
        'longitude' => 0.0,
        'speed' => 0.0,
        'satellites' => 0,
        'gps_status' => 'No Signal'
    ]);

    error_log("Failed to fetch data from proxy server.");
}


?>