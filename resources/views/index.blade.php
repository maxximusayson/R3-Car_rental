@extends('layouts.myapp1')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GPS Devices Data</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCDg7pLs7iesp74vQ-KSEjnFJW3BKhVq7k"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        @font-face {
            font-family: 'Roboto', sans-serif;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
            color: #333;
            display: flex;
            flex-direction: column;
            gap: 20px;
            overflow-x: hidden;
        }

        #mainContainer {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        #gpsDataContainer {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 20px;
            background: #ffffff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .card {
            background: #ffffff;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        .tile {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            margin: 8px 0;
            font-size: 1rem;
            padding: 5px 0;
            color: #555;
        }

        .tile i {
            color: #007bff;
            font-size: 1.2rem;
            margin-right: 5px;
        }

        .status-ok {
            color: green;
            font-weight: bold;
        }

        .status-no-signal {
            color: red;
            font-weight: bold;
        }

        #map {
            width: 100%;
            height: 400px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            background-color: #e9ecef;
        }

        /* Media query for larger screens */
        @media (min-width: 768px) {
            #mainContainer {
                flex-direction: row;
                gap: 20px;
            }

            #map {
                height: 800px; /* Restore larger map height for bigger screens */
            }
        }

        /* Media query for smaller screens */
        @media (max-width: 767px) {
            #gpsDataContainer {
                grid-template-columns: 1fr; /* Single column layout on small screens */
                padding: 15px;
            }

            #map {
                height: 300px; /* Reduce map height for smaller screens */
            }

            .card {
                padding: 10px;
            }

            .tile {
                font-size: 0.9rem; /* Slightly reduce text size on smaller screens */
            }

            .tile i {
                font-size: 1rem; /* Slightly reduce icon size */
            }
        }

        #locationHistoryContainer {
    border: 1px solid #e5e7eb; /* Light gray border */
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    text-align: left;
    padding: 12px;
}

th {
    background-color: #f3f4f6; /* Light gray background */
}

tr:nth-child(even) {
    background-color: #f9f9f9; /* Zebra striping */
}

tbody tr:hover {
    background-color: #f1f5f9; /* Light hover effect */
}

    </style>
</head>
<body>
    <!-- Welcome Message -->
    <div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 text-white p-6 rounded-lg shadow-lg mb-8">
        <h1 class="text-2xl font-bold">GPS Devices Data</h1>
        <p class="mt-2">Here's an overview of the current GPS devices and their statuses.</p>
    </div>

    <!-- Clock -->
    <div id="clock" class="text-white text-lg font-semibold absolute top-6 right-6 bg-gradient-to-r from-blue-500 to-indigo-600 py-2 px-4 rounded-lg shadow-lg">
        <span id="date"></span>, <span id="time"></span>
    </div>

    <!-- Main Container for GPS Data and Map -->
    <div id="mainContainer">
        <!-- GPS Data Container -->
        <div id="gpsDataContainer"></div>
        <!-- Map -->
        <div id="map"></div>
    </div>

<!-- Last Known Location Container -->
<div id="lastKnownLocation" class="bg-white p-4 rounded-lg shadow-md">
    <h2 class="text-xl font-bold mb-2">Last Known Location</h2>
    
    <div id="lastKnownLocationData" class="tile mb-2">
        <i class="fas fa-map-marker-alt text-blue-500"></i>
        <span id="lastLocationText" class="ml-2">Fetching last known location...</span>
    </div>
</div>

<!-- Location History Container -->
<div id="locationHistoryContainer" class="overflow-auto bg-white p-4 rounded-lg shadow-md" style="max-height: 300px;">
    <h3 class="text-xl font-bold mb-2">Location History:</h3>
    <table id="locationHistoryList" class="min-w-full">
        <thead>
            <tr>
                <th class="px-2 py-1">Device ID</th>
                <th class="px-2 py-1">Latitude</th>
                <th class="px-2 py-1">Longitude</th>
                <th class="px-2 py-1">Address</th>
                <th class="px-2 py-1">Timestamp</th>
            </tr>
        </thead>
        <tbody>
            <!-- Location history data will be dynamically added here -->
        </tbody>
    </table>
</div>





        
<script>
    // Global variable to store last known locations and history
    let lastKnownLocations = JSON.parse(localStorage.getItem('lastKnownLocations')) || {};
    let locationHistory = JSON.parse(localStorage.getItem('locationHistory')) || {};

    // Function to update the date and clock
    function updateClock() {
        const now = new Date();
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        const dateString = now.toLocaleDateString(undefined, options);
        const timeString = now.toLocaleTimeString();
        document.getElementById('date').textContent = dateString;
        document.getElementById('time').textContent = timeString;
    }

    updateClock();
    setInterval(updateClock, 1000);

    var map;
    var markers = [];

    $(document).ready(function() {
        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
                center: { lat: 0, lng: 0 },
                zoom: 10 // Adjust the zoom level here (lower number means more zoomed out)
            });
        }

        function fetchGPSData() {
            $.ajax({
                url: '/gps-data',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    console.log(data); // Log the full data response
                    $('#gpsDataContainer').empty();
                    markers.forEach(function(marker) {
                        marker.setMap(null);
                    });
                    markers = [];

                    $.each(data, function(index, device) {
                        console.log(device); // Log each device object

                        var gpsStatusClass = (device.gps_status && (device.gps_status.toUpperCase() === 'OK' || device.gps_status.toUpperCase() === 'GOOD')) ? 'status-ok' : 'status-no-signal';

                        var card = `
                            <div class="card">
                                <div class="card-title">Device ID: ${device.gps_id || 'N/A'}</div>
                                <div class="tile"><i class="fas fa-map-marker-alt"></i><strong>Latitude:</strong><span>${device.latitude || 0.0}</span></div>
                                <div class="tile"><i class="fas fa-map-marker-alt"></i><strong>Longitude:</strong><span>${device.longitude || 0.0}</span></div>
                                <div class="tile"><i class="fas fa-tachometer-alt"></i><strong>Speed:</strong><span>${device.speed || 0.0} km/h</span></div>
                                <div class="tile"><i class="fas fa-satellite"></i><strong>Satellites:</strong><span>${device.satellites || 0}</span></div>
                                <div class="tile"><i class="fas fa-signal"></i><strong>GPS Status:</strong> <span class="${gpsStatusClass}">${device.gps_status || 'No Signal'}</span></div>
                                <div class="tile"><i class="fas fa-clock"></i><strong>Timestamp:</strong><span>${device.timestamp ? new Date(device.timestamp * 1000).toLocaleString() : 'N/A'}</span></div>
                            </div>
                        `;

                        $('#gpsDataContainer').append(card);

                        if (device.latitude && device.longitude) {
                            var formattedDeviceName = device.gps_id ? device.gps_id.charAt(0).toUpperCase() + device.gps_id.slice(1).toLowerCase() : 'Unknown Device';
                            var iconUrl = (device.type === 'car') ? '/images/icons/car.png' : '/images/icons/car2.png';

                            var marker = new google.maps.Marker({
                                position: { lat: parseFloat(device.latitude), lng: parseFloat(device.longitude) },
                                map: map,
                                title: formattedDeviceName,
                                icon: {
                                    url: iconUrl,
                                    scaledSize: new google.maps.Size(50, 50)
                                }
                            });

                            var infoWindow = new google.maps.InfoWindow({
                                content: `
                                    <div style="font-family: 'Roboto', sans-serif; padding: 10px; border-radius: 5px; background-color: #ffffff; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);">
                                        <strong style="color: #007bff;">Device Name:</strong> <span style="font-size: 1.1rem;">${formattedDeviceName}</span>
                                        <br>
                                        <strong style="color: #007bff;">GPS Status:</strong> <span class="${gpsStatusClass}">${device.gps_status || 'No Signal'}</span>
                                        <br>
                                        <strong style="color: #007bff;">Latitude:</strong> <span>${device.latitude || 0.0}</span>
                                        <br>
                                        <strong style="color: #007bff;">Longitude:</strong> <span>${device.longitude || 0.0}</span>
                                    </div>
                                `
                            });

                            marker.addListener('click', function() {
                                infoWindow.open(map, marker);
                            });

                            markers.push(marker);
                            map.setCenter(marker.getPosition());

                            // Only store the last known location if the GPS status is not 'OK' or 'GOOD'
                            if (!(device.gps_status && (device.gps_status.toUpperCase() === 'OK' || device.gps_status.toUpperCase() === 'GOOD'))) {
                                lastKnownLocations[device.gps_id] = {
                                    latitude: device.latitude,
                                    longitude: device.longitude,
                                    gps_status: device.gps_status
                                };

                                localStorage.setItem('lastKnownLocations', JSON.stringify(lastKnownLocations));

                                // Fetch address and update the display and history
                                getAddressFromCoordinates(device.latitude, device.longitude).then(address => {
                                    $('#lastLocationText').text(`${formattedDeviceName} - Latitude: ${device.latitude}, Longitude: ${device.longitude}, Address: ${address}`);

                                    // Store the location history with address
                                    storeLocationHistory(device.gps_id, device.latitude, device.longitude, address);
                                });
                            } else {
                                // If the device is on and has a good signal, don't store its location
                                $('#lastLocationText').text(`${formattedDeviceName} is currently active and has a good GPS signal.`);
                            }
                        } else {
                            // Update last known location if the device is off or has no coordinates
                            if (lastKnownLocations[device.gps_id]) {
                                const lastLocation = lastKnownLocations[device.gps_id];
                                const lastLocationText = `${device.gps_id ? device.gps_id : 'Unknown Device'} - Latitude: ${lastLocation.latitude}, Longitude: ${lastLocation.longitude}`;

                                // Create a new entry for the location history
                                getAddressFromCoordinates(lastLocation.latitude, lastLocation.longitude).then(address => {
                                    $('#lastLocationText').text(`${lastLocationText}, Address: ${address}`);
                                    storeLocationHistory(device.gps_id, lastLocation.latitude, lastLocation.longitude, address);
                                });
                            } else {
                                // If the device is completely new and off, show a message
                                $('#lastLocationText').text(`${device.gps_id ? device.gps_id : 'Unknown Device'} is off and has no known locations.`);
                            }
                        }
                    });

                    // Restore and display location history
                    displayLocationHistory();
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching GPS data:', error);
                }
            });
        }

        // Function to store location history with address
        function storeLocationHistory(gps_id, latitude, longitude, address) {
            const historyEntry = {
                latitude: latitude,
                longitude: longitude,
                address: address || "Unknown Address", // Store address with fallback
                timestamp: Date.now()
            };

            if (!locationHistory[gps_id]) {
                locationHistory[gps_id] = [];
            }

            // Push to the front of the history array for latest first
            locationHistory[gps_id].unshift(historyEntry);
            localStorage.setItem('locationHistory', JSON.stringify(locationHistory));
        }

        // Function to get address from coordinates using Google Maps Geocoding API
        function getAddressFromCoordinates(latitude, longitude) {
            return new Promise((resolve, reject) => {
                const geocoder = new google.maps.Geocoder();
                geocoder.geocode({ 'location': { lat: latitude, lng: longitude } }, (results, status) => {
                    if (status === google.maps.GeocoderStatus.OK) {
                        if (results[0]) {
                            const address = results[0].formatted_address;
                            resolve(address || "Unknown Address");
                        } else {
                            resolve("No results found");
                        }
                    } else {
                        console.error('Geocoder failed due to: ' + status);
                        reject(status);
                    }
                });
            });
        }

        // Function to display location history in a well-structured format
        function displayLocationHistory() {
            $('#locationHistoryContainer').empty(); // Clear previous history display
            let historyHTML = '<table><thead><tr><th>Device ID</th><th>Latitude</th><th>Longitude</th><th>Address</th><th>Timestamp</th></tr></thead><tbody>';

            for (const gps_id in locationHistory) {
                const entries = locationHistory[gps_id];
                entries.forEach(entry => {
                    historyHTML += `<tr>
                        <td>${gps_id}</td>
                        <td>${entry.latitude}</td>
                        <td>${entry.longitude}</td>
                        <td>${entry.address}</td>
                        <td>${new Date(entry.timestamp).toLocaleString()}</td>
                    </tr>`;
                });
            }

            historyHTML += '</tbody></table>';
            $('#locationHistoryContainer').html(historyHTML);
        }

        initMap();
        fetchGPSData();
        setInterval(fetchGPSData, 10000); // Fetch data every 10 seconds
    });
</script>



<button id="clearHistoryButton" style="margin: 10px; padding: 10px; background-color: #ff4d4d; color: white; border: none; border-radius: 5px; cursor: pointer;">
    Clear History
</button>







</body>
</html>

@endsection
