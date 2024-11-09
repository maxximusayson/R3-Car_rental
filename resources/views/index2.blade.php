@extends('layouts.myapp1')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GPS Device Data - GPS02</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCDg7pLs7iesp74vQ-KSEjnFJW3BKhVq7k"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        #clock {
            position: absolute;
            top: 6px;
            right: 6px;
            background: linear-gradient(to right, #007BFF, #6f42c1);
            color: white;
            padding: 8px 15px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        #mainContainer {
            padding: 20px;
            margin-top: 50px;
        }
        #gpsDataContainer {
            margin-bottom: 20px;
        }
        .card {
            background-color: #fff;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .status-ok {
            color: green;
        }
        .status-no-signal {
            color: red;
        }
    </style>
</head>
<body>
    <div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 text-white p-6 rounded-lg shadow-lg mb-8">
        <h1 class="text-2xl font-bold">GPS Device Data - GPS02</h1>
        <p class="mt-2">Overview of GPS02 and its status.</p>
    </div>

    <div id="clock" class="text-white text-lg font-semibold absolute top-6 right-6 bg-gradient-to-r from-blue-500 to-indigo-600 py-2 px-4 rounded-lg shadow-lg">
        <span id="date"></span>, <span id="time"></span>
    </div>

    <div id="mainContainer">
        <div id="gpsDataContainer"></div>
        <div id="map" style="width: 100%; height: 400px;"></div>
    </div>

    <div id="lastKnownLocation" class="bg-white p-4 rounded-lg shadow-md">
        <h2 class="text-xl font-bold mb-2">Last Known Location</h2>
        <div id="lastKnownLocationData" class="tile mb-2">
            <i class="fas fa-map-marker-alt text-blue-500"></i>
            <span id="lastLocationText" class="ml-2">Fetching last known location...</span>
        </div>
    </div>

    <div id="locationHistoryContainer" class="overflow-auto bg-white p-4 rounded-lg shadow-md" style="max-height: 300px;">
        <h3 class="text-xl font-bold mb-2">Location History:</h3>
        <table id="locationHistoryList" class="min-w-full">
            <thead>
                <tr>
                    <th>Device ID</th>
                    <th>Address</th>
                    <th>Timestamp</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <script>
        let map;
        let marker;
        let lastKnownLocations = JSON.parse(localStorage.getItem('lastKnownLocations')) || {};
        let locationHistory = JSON.parse(localStorage.getItem('locationHistory')) || {};

        function updateClock() {
            const now = new Date();
            document.getElementById('date').textContent = now.toLocaleDateString();
            document.getElementById('time').textContent = now.toLocaleTimeString();
        }

        updateClock();
        setInterval(updateClock, 1000);

        $(document).ready(function() {
            // Initialize Google Map
            function initMap() {
                map = new google.maps.Map(document.getElementById('map'), {
                    center: { lat: 0, lng: 0 },
                    zoom: 10
                });
            }


              // Function to animate marker smoothly
function animateMarker(newLat, newLng) {
    const currentPos = marker.getPosition();
    const targetPos = { lat: newLat, lng: newLng };

    let step = 0;
    const totalSteps = 100;  // The number of steps to reach the destination
    const intervalTime = 10; // Interval time in milliseconds

    function move() {
        if (step <= totalSteps) {
            // Calculate the new position using linear interpolation (lerp)
            const lat = currentPos.lat() + (targetPos.lat - currentPos.lat()) * (step / totalSteps);
            const lng = currentPos.lng() + (targetPos.lng - currentPos.lng()) * (step / totalSteps);

            marker.setPosition(new google.maps.LatLng(lat, lng)); // Update marker position
            step++;

            // Keep calling the move function until the target position is reached
            setTimeout(move, intervalTime);
        } else {
            marker.setPosition(targetPos);  // Ensure the marker ends exactly at the target position
        }
    }

    // Start moving the marker
    move();
}

            // Fetch GPS data
            function fetchGPSData() {
                $.ajax({
                    url: '/gps/fetch', // Update this to match the route defined in web.php
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        console.log("Data fetched:", data);
                        let deviceFound = false;
                        $('#gpsDataContainer').empty();
                        data.forEach(device => {
                            if (device.gps_id === 'GPS02' && device.latitude && device.longitude) {  // Filter for GPS02
                                deviceFound = true;
                                processDeviceData(device);
                            }
                        });

                        if (!deviceFound) {
                            // Device might be off, show last known location
                            displayLastKnownLocation();
                            displayLocationHistory();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching GPS data:', error);
                    }
                });
            }

            // Process GPS device data
            function processDeviceData(device) {
                const isGoodSignal = device.gps_status && (device.gps_status.toUpperCase() === 'OK' || device.gps_status.toUpperCase() === 'GOOD');
                const gpsStatusClass = isGoodSignal ? 'status-ok' : 'status-no-signal';

                const card = `
                    <div class="card">
                        <div class="card-title">Device ID: ${device.gps_id || 'N/A'}</div>
                        <div class="tile"><strong>Speed:</strong> ${device.speed || 0} km/h</div>
                        <div class="tile"><strong>GPS Status:</strong> <span class="${gpsStatusClass}">${device.gps_status || 'No Signal'}</span></div>
                        <div class="tile"><strong>Timestamp:</strong> ${device.timestamp ? new Date(device.timestamp * 1000).toLocaleString() : 'N/A'}</div>
                    </div>
                `;
                $('#gpsDataContainer').append(card);

                if (device.latitude && device.longitude) {
                    addMarker(device);
                    saveLastKnownLocation(device);
                }
            }

            // Add a marker for the device location
            function addMarker(device) {
                const carIconUrl = '/images/icons/blue-pin.png'; // Customize icon for GPS02

                marker = new google.maps.Marker({
                    position: { lat: parseFloat(device.latitude), lng: parseFloat(device.longitude) },
                    map: map,
                    title: device.gps_id,
                    icon: {
                        url: carIconUrl,
                        scaledSize: new google.maps.Size(32, 32),
                    }
                });
                map.setCenter(marker.getPosition());
            }

            // Save last known location with address in localStorage
            async function saveLastKnownLocation(device) {
                const address = await getAddressFromCoordinates(device.latitude, device.longitude);
                lastKnownLocations[device.gps_id] = {
                    latitude: device.latitude,
                    longitude: device.longitude,
                    gps_status: device.gps_status,
                    address: address,
                    timestamp: Date.now()
                };

                localStorage.setItem('lastKnownLocations', JSON.stringify(lastKnownLocations));
                $('#lastLocationText').text(`Last known location - Address: ${address}`);
                storeLocationHistory(device.gps_id, device.latitude, device.longitude, address);
            }

            // Store location history
            function storeLocationHistory(gps_id, latitude, longitude, address) {
                if (!locationHistory[gps_id]) {
                    locationHistory[gps_id] = [];
                }
                locationHistory[gps_id].unshift({
                    latitude: latitude,
                    longitude: longitude,
                    address: address || 'Unknown Address',
                    timestamp: Date.now()
                });
                localStorage.setItem('locationHistory', JSON.stringify(locationHistory));
                displayLocationHistory();  // Update the table after storing the history
            }

            // Display location history in the table
            function displayLocationHistory() {
                let historyHTML = '';
                if (locationHistory['GPS02']) {
                    locationHistory['GPS02'].forEach(entry => {
                        const formattedDate = new Date(entry.timestamp).toLocaleDateString('en-US', { 
                            year: 'numeric', 
                            month: 'long', 
                            day: 'numeric' 
                        });

                        const formattedTime = new Date(entry.timestamp).toLocaleTimeString('en-US', {
                            hour: '2-digit',
                            minute: '2-digit',
                            second: '2-digit',
                            hour12: true
                        });

                        const formattedDateTime = `${formattedDate}, ${formattedTime}`;

                        historyHTML += `
                            <tr>
                                <td>GPS02</td>
                                <td>${entry.address}</td>
                                <td>${formattedDateTime}</td>
                            </tr>
                        `;
                    });
                }
                $('#locationHistoryList tbody').html(historyHTML);
            }

            // Get address from latitude and longitude using Google Geocoding API
            function getAddressFromCoordinates(latitude, longitude) {
                return new Promise((resolve, reject) => {
                    const geocoder = new google.maps.Geocoder();
                    geocoder.geocode({ location: { lat: latitude, lng: longitude } }, (results, status) => {
                        if (status === google.maps.GeocoderStatus.OK && results[0]) {
                            resolve(results[0].formatted_address);
                        } else {
                            resolve('Address not available');
                        }
                    });
                });
            }

            // Display last known location when device data is unavailable
            function displayLastKnownLocation() {
                const lastLocation = lastKnownLocations['GPS02'];
                if (lastLocation) {
                    $('#lastLocationText').text(`Last known location - Address: ${lastLocation.address || 'Address not available'}`);
                    const position = {
                        lat: parseFloat(lastLocation.latitude),
                        lng: parseFloat(lastLocation.longitude)
                    };
                    if (!marker) {
                        marker = new google.maps.Marker({
                            position: position,
                            map: map,
                            title: 'GPS02',
                        });
                    } else {
                        marker.setPosition(position);
                    }
                    map.setCenter(position);
                }
            }

            // Initialize map and start data fetching
            initMap();
            fetchGPSData();
            setInterval(fetchGPSData, 10000); // Fetch GPS data every 10 seconds
        });
    </script>
</body>
</html>
@endsection
