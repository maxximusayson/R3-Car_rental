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
        var formattedDeviceName = device.gps_id ? device.gps_id.charAt(0).toUpperCase() + device.gps_id.slice(1).toLowerCase() : 'Unknown Device'; // Format the device name
        
        // Determine the icon based on device type or other criteria
        var iconUrl = (device.type === 'car') ? '/images/icons/car.png' : '/images/icons/car2.png'; // Update with your other device's icon path

        var marker = new google.maps.Marker({
            position: { lat: parseFloat(device.latitude), lng: parseFloat(device.longitude) },
            map: map,
            title: formattedDeviceName, // Use the formatted device name as title
            icon: {
                url: iconUrl,
                scaledSize: new google.maps.Size(50, 50) // Set the size of the icon
            }
        });

        // Create an InfoWindow for the marker
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

        // Add click event to open InfoWindow
        marker.addListener('click', function() {
            infoWindow.open(map, marker);
        });

        markers.push(marker);
        map.setCenter(marker.getPosition());
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
