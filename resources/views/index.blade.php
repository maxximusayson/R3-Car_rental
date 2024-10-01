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
            display: flex; /* Use flexbox for layout */
            flex-direction: column; /* Stack elements vertically */
            gap: 20px; /* Space between elements */
        }

        /* Added a container for the GPS data and map */
        #mainContainer {
            display: flex; /* Align items horizontally */
            gap: 20px; /* Space between GPS data and map */
        }

        #gpsDataContainer {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 20px;
            height: 450px; /* Set fixed height */
            background: #ffffff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            overflow-y: hidden; /* Prevent scrolling */
            flex: 1; /* Allow GPS data container to take available space */
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
            align-items: center; /* Aligns items vertically */
            justify-content: flex-start; /* Align text to the left */
            margin: 8px 0;
            font-size: 1rem;
            padding: 5px 0;
            color: #555;
        }

        .tile i {
            color: #007bff;
            font-size: 1.2rem;
            margin-right: 5px; /* Reduced space between icon and text */
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
            height: 800px; /* Increased height for the map */
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            background-color: #e9ecef;
            flex: 1; /* Allow map to take available space */
        }

        @media (min-width: 768px) {
            #mainContainer {
                flex-direction: row; /* Change layout direction for larger screens */
            }
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

    <script>
         // Function to update the date and clock
    function updateClock() {
        const now = new Date();
        const options = { year: 'numeric', month: 'long', day: 'numeric' }; // Month as a word
        const dateString = now.toLocaleDateString(undefined, options); // Format date
        const timeString = now.toLocaleTimeString(); // Format time
        document.getElementById('date').textContent = dateString;
        document.getElementById('time').textContent = timeString;
    }

    // Initialize the clock
    updateClock();
    setInterval(updateClock, 1000);

        var map;
        var markers = [];

        $(document).ready(function() {
            function initMap() {
                map = new google.maps.Map(document.getElementById('map'), {
                    center: {lat: 0, lng: 0},
                    zoom: 20
                });
            }

            function fetchGPSData() {
                $.ajax({
                    url: '/gps-data',
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#gpsDataContainer').empty();
                        markers.forEach(function(marker) {
                            marker.setMap(null);
                        });
                        markers = [];

                        $.each(data, function(index, device) {
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
                                var marker = new google.maps.Marker({
                                    position: {lat: parseFloat(device.latitude), lng: parseFloat(device.longitude)},
                                    map: map,
                                    title: device.gps_id || 'Unknown Device'
                                });
                                markers.push(marker);
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching GPS data:', error);
                    }
                });
            }

            initMap();
            fetchGPSData();
            setInterval(fetchGPSData, 2000);
        });
    </script>
</body>
</html>

@endsection
