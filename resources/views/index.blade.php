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
    // Global variables for last known locations and history
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

    let map;
    let markers = [];

    $(document).ready(function() {
        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
                center: { lat: 0, lng: 0 },
                zoom: 10
            });
        }

        function fetchGPSData() {
            $.ajax({
                url: '/gps-data',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    $('#gpsDataContainer').empty();
                    markers.forEach(marker => marker.setMap(null));
                    markers = [];

                    data.forEach(device => {
                        processDeviceData(device);
                    });

                    displayLocationHistory();
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching GPS data:', error);
                }
            });
        }

        function processDeviceData(device) {
            const isGoodSignal = isGpsStatusGood(device.gps_status);
            const gpsStatusClass = isGoodSignal ? 'status-ok' : 'status-no-signal';

            // Create the card for displaying the device's information
            const card = `
                <div class="card">
                    <div class="card-title">Device ID: ${device.gps_id || 'N/A'}</div>
                    <div class="tile"><strong>Latitude:</strong> ${device.latitude || 'N/A'}</div>
                    <div class="tile"><strong>Longitude:</strong> ${device.longitude || 'N/A'}</div>
                    <div class="tile"><strong>Speed:</strong> ${device.speed || 0} km/h</div>
                    <div class="tile"><strong>GPS Status:</strong> <span class="${gpsStatusClass}">${device.gps_status || 'No Signal'}</span></div>
                    <div class="tile"><strong>Timestamp:</strong> ${device.timestamp ? new Date(device.timestamp * 1000).toLocaleString() : 'N/A'}</div>
                </div>
            `;
            $('#gpsDataContainer').append(card);

            // Handle the location based on GPS signal status
            if (device.latitude && device.longitude) {
                addMarker(device);
                saveLastKnownLocation(device); // Save the location and get the address
            } else {
                displayLastKnownLocation(device);
            }
        }

        function isGpsStatusGood(status) {
            return status && (status.toUpperCase() === 'OK' || status.toUpperCase() === 'GOOD');
        }

     function addMarker(device) {
    const formattedDeviceName = device.gps_id || 'Unknown Device';

    // Local path to the car icon image.
    const carIconUrl = '/images/icons/car.png'; // Adjust this path based on where your icon is stored.

    const marker = new google.maps.Marker({
        position: { lat: parseFloat(device.latitude), lng: parseFloat(device.longitude) },
        map: map,
        title: formattedDeviceName,
        icon: {
            url: carIconUrl, // Set the custom car icon
            scaledSize: new google.maps.Size(32, 32), // Adjust the size as needed
        }
    });

    markers.push(marker);
    map.setCenter(marker.getPosition());
}


        async function saveLastKnownLocation(device) {
            lastKnownLocations[device.gps_id] = {
                latitude: device.latitude,
                longitude: device.longitude,
                gps_status: device.gps_status,
                timestamp: Date.now()
            };
            localStorage.setItem('lastKnownLocations', JSON.stringify(lastKnownLocations));

            try {
                const address = await getAddressFromCoordinates(device.latitude, device.longitude);
                // Display the address in the last known location text
                $('#lastLocationText').text(
                    `Last known location of ${device.gps_id} - Latitude: ${device.latitude}, Longitude: ${device.longitude}, Address: ${address}`
                );
                storeLocationHistory(device.gps_id, device.latitude, device.longitude, address);
            } catch (error) {
                console.error('Error fetching address:', error);
                $('#lastLocationText').text(
                    `Last known location of ${device.gps_id} - Latitude: ${device.latitude}, Longitude: ${device.longitude}, Address: Address not available`
                );
            }
        }

        function storeLocationHistory(gps_id, latitude, longitude, address) {
            const historyEntry = {
                latitude: latitude,
                longitude: longitude,
                address: address || 'Unknown Address',
                timestamp: Date.now()
            };

            if (!locationHistory[gps_id]) {
                locationHistory[gps_id] = [];
            }

            // Add the new entry to the beginning of the history array
            locationHistory[gps_id].unshift(historyEntry);

            // Update the localStorage with the new history
            localStorage.setItem('locationHistory', JSON.stringify(locationHistory));
        }

        function displayLastKnownLocation(device) {
    const lastLocation = lastKnownLocations[device.gps_id];
    
    if (lastLocation && lastLocation.latitude && lastLocation.longitude) {
        getAddressFromCoordinates(lastLocation.latitude, lastLocation.longitude)
            .then(address => {
                $('#lastLocationText').text(
                    `Last known location of ${device.gps_id} - Latitude: ${lastLocation.latitude}, Longitude: ${lastLocation.longitude}, Address: ${address}`
                );
                storeLocationHistory(device.gps_id, lastLocation.latitude, lastLocation.longitude, address);
            })
            .catch(() => {
                $('#lastLocationText').text(
                    `Last known location of ${device.gps_id} - Latitude: ${lastLocation.latitude}, Longitude: ${lastLocation.longitude}, Address: Address not available`
                );
            });
    } else {
        $('#lastLocationText').text(
            `No location data available for ${device.gps_id}.`
        );
    }
}


        function getAddressFromCoordinates(latitude, longitude) {
            return new Promise((resolve, reject) => {
                const geocoder = new google.maps.Geocoder();
                geocoder.geocode({ location: { lat: latitude, lng: longitude } }, (results, status) => {
                    if (status === google.maps.GeocoderStatus.OK && results[0]) {
                        resolve(results[0].formatted_address);
                    } else {
                        console.error('Geocoder failed:', status);
                        resolve('Address not available');
                    }
                });
            });
        }

        function displayLocationHistory() {
            let historyHTML = '<table><thead><tr><th>Device ID</th><th>Latitude</th><th>Longitude</th><th>Address</th><th>Timestamp</th></tr></thead><tbody>';

            for (const gps_id in locationHistory) {
                locationHistory[gps_id].forEach(entry => {
                    historyHTML += `
                        <tr>
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

        // Initialize the map and fetch data
        initMap();
        fetchGPSData();
        setInterval(fetchGPSData, 10000); // Refresh every 10 seconds
    });
</script>





<button id="clearHistoryButton" style="margin: 10px; padding: 10px; background-color: #ff4d4d; color: white; border: none; border-radius: 5px; cursor: pointer;">
    Clear History
</button>







</body>
</html>

@endsection
