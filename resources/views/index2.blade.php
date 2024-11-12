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
        /* Global Styling */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f5f7fa;
            color: #333;
            margin: 0;
            padding: 0;
        }

        /* Header Styling */
        .header {
            background: linear-gradient(to right, #4b6cb7, #182848);
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 10px;
            margin: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            font-size: 28px;
            font-weight: bold;
        }

        .header p {
            margin-top: 10px;
        }

        /* Clock Styling */
        #clock {
            position: absolute;
            top: 20px;
            right: 20px;
            background: #4b6cb7;
            color: white;
            padding: 10px 15px;
            border-radius: 8px;
            font-weight: 600;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Main Container */
        #mainContainer {
            display: flex;
            gap: 20px;
            margin: 20px;
        }

        /* Legend Styling */
        #legend {
            flex: 1;
            min-width: 250px;
            background-color: #ffffff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        #legend h3 {
            font-size: 18px;
            color: #4b6cb7;
            margin-bottom: 10px;
            text-align: center;
        }

        .legend-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            padding: 8px;
            border-radius: 6px;
            transition: background-color 0.3s ease;
        }

        .legend-item:hover {
            background-color: #eef3fc;
        }

        .legend-icon {
            width: 60px;
            height: 50px;
            margin-right: 12px;
        }

        /* Card Styling */
        .card {
            flex: 2;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Map Container */
        #map {
            width: 100%;
            height: 400px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        /* Last Known Location Styling */
        #lastKnownLocation {
            flex: 1;
            background-color: #ffffff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* GPS Data Container */
        #gpsDataContainer {
            flex: 1;
            background-color: #ffffff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        /* Location History Styling */
        #locationHistoryContainer {
            flex: 2;
            background-color: #ffffff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            max-height: 300px;
            margin-top: 20px;
        }

        #locationHistoryList {
            width: 100%;
            border-collapse: collapse;
        }

        #locationHistoryList th, #locationHistoryList td {
            padding: 8px 12px;
            text-align: left;
            border-bottom: 1px solid #f1f1f1;
        }

        #locationHistoryList th {
            background-color: #f7f7f7;
            color: #333;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            #mainContainer {
                flex-direction: column;
                padding: 15px;
            }

            #clock {
                font-size: 14px;
                padding: 8px 12px;
            }
        }
        .card {
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
    padding: 20px;
}

.card-header h3 {
    font-size: 18px;
    margin-bottom: 10px;
    color: #333;
}

.card-body {
    display: flex;
    flex-direction: column;
}

.tile {
    margin-bottom: 10px;
    font-size: 16px;
    color: #555;
}

.tile strong {
    font-weight: bold;
}

.value {
    font-size: 18px;
    color: #333;
}

.status-icon {
    font-weight: bold;
    font-size: 16px;
    margin-left: 5px;
}

.status-ok {
    color: #28a745;
}

.status-no-signal {
    color: #dc3545;
}

.timestamp {
    font-size: 16px;
    color: #333;
}

.street-name {
    font-size: 16px;
    color: #555;
}

.divider {
    border: 0;
    border-top: 1px solid #eee;
    margin-top: 20px;
}

.fas {
    margin-right: 5px;
}
 /* Button Group Styling */
 .button-group {
        display: flex;
        gap: 15px;
        justify-content: center;
        margin: 20px 0;
    }

    /* Action Button Styling */
    .action-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 12px 18px;
        border-radius: 8px;
        font-weight: bold;
        color: #fff;
        border: none;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.2s;
        font-size: 16px;
        gap: 8px;
    }

    /* Button Colors */
    .delete-btn {
        background-color: #e74c3c;
    }

    .calculate-btn {
        background-color: #3498db;
    }

    .return-btn {
        background-color: #2ecc71;
    }

    /* Hover Effects */
    .action-btn:hover {
        transform: scale(1.05);
        opacity: 0.9;
    }

    .delete-btn:hover {
        background-color: #c0392b;
    }

    .calculate-btn:hover {
        background-color: #2980b9;
    }

    .return-btn:hover {
        background-color: #27ae60;
    }

    /* Button Focus Effect */
    .action-btn:focus {
        outline: none;
        box-shadow: 0 0 8px rgba(0, 0, 0, 0.3);
    }

    /* Icon Styling */
    .action-btn i {
        font-size: 18px;
    }

    </style>
</head>
<body>
    <div class="header">
        <h1>GPS Device Data - Xpander</h1>
        <p>Overview of GPS02-Xpander and its status</p>
    </div>

    <div id="clock">
        <span id="date"></span>, <span id="time"></span>
    </div>

    <div id="mainContainer">
        <!-- Legend Section -->
  <div id="legend" class="info-box" style="width: 250px; padding: 10px; border-radius: 8px; background-color: #ffffff; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
    <h3 style="text-align: center; font-size: 1.2em; color: #4b6cb7; margin-bottom: 15px;">Device Status</h3>

    <div class="legend-item" title="Device is active and reporting data" style="width: 100%; height: 80px; display: flex; align-items: center; background-color: #f9f9f9; border-radius: 8px; padding: 10px; margin-bottom: 10px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
        <div style="display: flex; align-items: center; gap: 10px; width: 100%;">
            <div style="width: 20px; height: 20px; background-color: #4CAF50; border-radius: 50%;"></div>
            <span class="legend-label" style="font-weight: bold; color: #4CAF50;">Active Device</span>
            <img src="/images/icons/XpanderVios-pin.png" alt="Active Device Icon" class="legend-icon" style="width: 50px; height: 50px; margin-left: auto;">
        </div>
    </div>

    <div class="legend-item" title="Device is offline and not reporting data" style="width: 100%; height: 80px; display: flex; align-items: center; background-color: #f9f9f9; border-radius: 8px; padding: 10px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
        <div style="display: flex; align-items: center; gap: 10px; width: 100%;">
            <div style="width: 20px; height: 20px; background-color: #f44336; border-radius: 50%;"></div>
            <span class="legend-label" style="font-weight: bold; color: #f44336;">Offline Device</span>
            <img src="/images/icons/offline-icon.png" alt="Offline Device Icon" class="legend-icon" style="width: 50px; height: 50px; margin-left: auto;">
        </div>
    </div>
</div>

        <!-- GPS Data Section -->
        <div id="gpsDataContainer" class="info-box">
            <h2 class="text-lg font-semibold">GPS DATA</h2>
            <div>Data is loading...</div>
        </div>

        <!-- Last Known Location Section -->
        <div id="lastKnownLocation" class="info-box">
            <h2 class="text-lg font-semibold">Last Known Location</h2>
            <div id="lastKnownLocationData" class="tile">
                <i class="fas fa-map-marker-alt text-blue-500"></i>
                <span id="lastLocationText" class="ml-2">Fetching last known location...</span>
            </div>
        </div>
    </div>

    <!-- Map Section -->
    <div id="map"></div>


    <!-- Buttons for additional functionalities -->
    <div class="button-group">
    <button id="deleteHistory" class="action-btn delete-btn">
        <i class="fas fa-trash-alt"></i> Delete History
    </button>
    <button id="calculateKM" class="action-btn calculate-btn">
        <i class="fas fa-route"></i> Calculate KM
    </button>
    <button id="returnCar" class="action-btn return-btn">
        <i class="fas fa-undo-alt"></i> Return Car
    </button>
</div>

<script>
    // Function to handle "Delete History" button
    document.getElementById('deleteHistory').addEventListener('click', function() {
        if (confirm("Are you sure you want to delete the history?")) {
            localStorage.removeItem('locationHistory'); // Remove history from local storage
            locationHistory = {}; // Reset local history variable
            displayLocationHistory(); // Update the display
            alert("Location history deleted successfully.");
        }
    });

    // Function to handle "Calculate KM" button
    document.getElementById('calculateKM').addEventListener('click', function() {
        if (locationHistory['GPS01'] && locationHistory['GPS01'].length > 1) {
            let totalDistance = 0;
            for (let i = 1; i < locationHistory['GPS01'].length; i++) {
                const prev = locationHistory['GPS01'][i - 1];
                const curr = locationHistory['GPS01'][i];
                totalDistance += calculateDistance(prev.latitude, prev.longitude, curr.latitude, curr.longitude);
            }
            alert(`Total Distance Covered: ${totalDistance.toFixed(2)} km`);
        } else {
            alert("Not enough data to calculate distance.");
        }
    });

    // Function to calculate distance between two coordinates using Haversine formula
    function calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371; // Radius of the Earth in kilometers
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                  Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                  Math.sin(dLon / 2) * Math.sin(dLon / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return R * c; // Distance in kilometers
    }

    // Function to handle "Return Car" button
    document.getElementById('returnCar').addEventListener('click', function() {
        const defaultLocation = { lat: 0, lng: 0 }; // Set to initial or predefined location
        if (marker) {
            animateMarker(defaultLocation.lat, defaultLocation.lng); // Animate back to default
            map.setCenter(defaultLocation);
            alert("Car returned to the starting point.");
        } else {
            alert("No car location found to return.");
        }
    });
</script>


    <!-- Location History Section -->
    <div id="locationHistoryContainer">
        <h3 class="text-lg font-semibold">Location History:</h3>
        <table id="locationHistoryList">
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

        // Animate marker smoothly
        function animateMarker(newLat, newLng) {
            const currentPos = marker.getPosition();
            const targetPos = { lat: newLat, lng: newLng };

            let step = 0;
            const totalSteps = 200;  // Number of steps for smoother movement (higher value = smoother)
            const intervalTime = 10; // Interval time in milliseconds (lower value = smoother)

            // Easing function for smooth acceleration and deceleration (ease-in-out)
            function easeInOutQuad(t, b, c, d) {
                t /= d / 2;
                if (t < 1) return c / 2 * t * t + b;
                t--;
                return -c / 2 * (t * (t - 2) - 1) + b;
            }

            // Function to update the marker's position over time with smooth transition
            function move() {
                if (step <= totalSteps) {
                    // Calculate the progress using the easing function
                    const progress = easeInOutQuad(step, 0, 1, totalSteps);

                    // Calculate the intermediate position using the progress factor
                    const lat = currentPos.lat() + (targetPos.lat - currentPos.lat()) * progress;
                    const lng = currentPos.lng() + (targetPos.lng - currentPos.lng()) * progress;

                    // Update marker's position smoothly
                    marker.setPosition(new google.maps.LatLng(lat, lng));

                    // Continuously update the map's center to keep the marker in focus
                    map.setCenter(new google.maps.LatLng(lat, lng));

                    step++;

                    // Call the move function again after the specified interval
                    setTimeout(move, intervalTime);
                } else {
                    // Ensure marker is exactly at the target position when done
                    marker.setPosition(targetPos);
                    map.setCenter(targetPos);  // Optionally, keep the map centered on the final position
                }
            }

            // Start the animation
            move();
        }

        // Fetch GPS data
        function fetchGPSData() {
            $.ajax({
                url: '/gps/fetch', // Adjust this route accordingly
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    console.log("Data fetched:", data);
                    let deviceFound = false;

                    $('#gpsDataContainer').empty();
                    data.forEach(device => {
                        if (device.gps_id === 'GPS02' && device.latitude && device.longitude) {
                            deviceFound = true;
                            processDeviceData(device);
                        }
                    });

                    if (!deviceFound) {
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
                    <div class="card-header">
                        <h3>Device ID: ${device.gps_id || 'N/A'}</h3>
                    </div>  
                    <div class="card-body">
                        <div class="tile">
                            <strong>Speed:</strong> <span class="value">${device.speed || 0} km/h</span>
                        </div>
                        <div class="tile">
                            <strong>GPS Status:</strong> 
                            <span class="status-icon ${gpsStatusClass}">
                                <i class="fas ${isGoodSignal ? 'fa-check-circle' : 'fa-times-circle'}"></i> 
                                ${device.gps_status || 'No Signal'}
                            </span>
                        </div>
                        <div class="tile">
                            <strong>Timestamp:</strong> 
                            <span class="timestamp">
                                ${device.timestamp ? new Date(device.timestamp * 1000).toLocaleString('en-PH', { 
                                    timeZone: 'Asia/Manila', 
                                    month: 'long', 
                                    day: 'numeric', 
                                    year: 'numeric', 
                                    hour: 'numeric', 
                                    minute: 'numeric', 
                                    second: 'numeric', 
                                    hour12: true 
                                }) : 'N/A'}
                            </span>
                        </div>
                        <p class="street-name"><strong>Street:</strong> <span id="street_${device.gps_id}">Loading...</span></p>
                    </div>
                </div>
                <hr class="divider">
            `;

            $('#gpsDataContainer').append(card);

            if (device.latitude && device.longitude) {
                addMarker(device);
                saveLastKnownLocation(device);
                getStreetName(device.latitude, device.longitude, device.gps_id); // Fetch street name
            }
        }

        // Add a marker for the device location
        function addMarker(device) {
            const carIconUrl = '/images/icons/XpanderVios-pin.png';

            marker = new google.maps.Marker({
                position: { lat: parseFloat(device.latitude), lng: parseFloat(device.longitude) },
                map: map,
                title: device.gps_id,
                icon: {
                    url: carIconUrl,
                    scaledSize: new google.maps.Size(62, 62),
                }
            });
            map.setCenter(marker.getPosition());

            // Animate the marker smoothly to the new position
            animateMarker(parseFloat(device.latitude), parseFloat(device.longitude));
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

        // Store location history for GPS02
        function storeLocationHistory(gps_id, latitude, longitude, address) {
            if (gps_id === 'GPS02') {
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
                displayLocationHistory(); // Update the table with history
            }
        }

        // Display location history in the table for GPS02
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
        function getStreetName(lat, lng, gps_id) {
            const geocodeUrl = `https://maps.googleapis.com/maps/api/geocode/json?latlng=${lat},${lng}&key=AIzaSyCDg7pLs7iesp74vQ-KSEjnFJW3BKhVq7k`;

            $.getJSON(geocodeUrl, function(response) {
                let street = 'Unavailable';
                if (response.status === 'OK' && response.results.length > 0) {
                    street = response.results[0].formatted_address;
                }
                $(`#street_${gps_id}`).text(street); // Set the street text for the specific GPS ID
            }).fail(function() {
                $(`#street_${gps_id}`).text('Unavailable'); // Set to unavailable if API call fails
            });
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

        // Initialize map and start data fetching
        initMap();
        fetchGPSData();
        setInterval(fetchGPSData, 10000); // Fetch GPS data every 10 seconds

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
    });

</script>


</body>
</html>
@endsection
