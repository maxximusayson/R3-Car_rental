<?php


// Directory to store the latest GPS data for each device
$dataDir = 'gps_data/';
$timeoutDuration = 60; // 1 minute timeout duration

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure the data directory exists
    if (!file_exists($dataDir)) {
        mkdir($dataDir, 0777, true);
    }

    // Receive data from GPS device
    $data = file_get_contents('php://input');
    $gpsDataArray = json_decode($data, true);

    // Check for gps_id in the data
    if (isset($gpsDataArray['gps_id'])) {
        $deviceId = $gpsDataArray['gps_id'];
        $dataFile = $dataDir . $deviceId . '.json';
        $gpsDataArray['timestamp'] = time(); // Add a timestamp
        file_put_contents($dataFile, json_encode($gpsDataArray));
        echo 'GPS Data received';
    } else {
        echo 'GPS ID missing';
    }
} else {
    // Serve the latest GPS data for all devices
    $allData = [];
    foreach (glob($dataDir . '*.json') as $filename) {
        $gpsData = json_decode(file_get_contents($filename), true);
        $currentTime = time();
        $dataAge = $currentTime - $gpsData['timestamp'];

        if ($dataAge <= $timeoutDuration) {
            $allData[] = $gpsData;
        }
    }
    header('Content-Type: application/json');
    echo json_encode($allData);
}
?>