@extends('layouts.myapp1')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GPS Device Data - GPS02</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCWrXtUyyqoWHwLddsIRgZKjKc9YGeW7FI"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="header">
        <h1>MONTERO</h1>
        <p>Overview of GPS02-Montero and its status</p>
    </div>

    <div id="clock">
        <span id="date"></span>, <span id="time"></span>
    </div>

    <div id="mainContainer">


   <!-- Legend Section -->
<div id="legend" class="info-box" style="width: 250px; margin: 20px auto; padding: 20px; border-radius: 12px; background-color: #f9f9f9; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); font-family: 'Arial', sans-serif">
    <h3 style="text-align: center; font-size: 1.5em; color: #3B82F6; margin-bottom: 20px; font-weight: bold; text-transform: uppercase;">Legend</h3>

    <!-- Active Device -->
    <div class="legend-item" title="Device is active and reporting data" style="display: flex; align-items: center; background-color: #ffffff; border-radius: 10px; padding: 15px; margin-bottom: 15px; box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);">
        <div style="width: 30px; height: 30px; background-color: #4CAF50; border-radius: 50%; margin-right: 15px;"></div>
        <div style="flex-grow: 1;">
            <span class="legend-label" style="font-weight: bold; font-size: 1.1em; color: #4CAF50;">Active Device</span>
            <p style="margin: 5px 0 0; font-size: 0.9em; color: #6B7280;">Device is active and reporting data.</p>
        </div>
        <img src="/images/icons/Gps1-icon.png" alt="Active Device Icon" style="width: 40px; height: 40px; margin-left: auto;">
    </div>

    <!-- Offline Device -->
    <div class="legend-item" title="Device is offline and not reporting data" style="display: flex; align-items: center; background-color: #ffffff; border-radius: 10px; padding: 15px; box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);">
        <div style="width: 30px; height: 30px; background-color: #f44336; border-radius: 50%; margin-right: 15px;"></div>
        <div style="flex-grow: 1;">
            <span class="legend-label" style="font-weight: bold; font-size: 1.1em; color: #f44336;">Last Known Location / Offline</span>
            <p style="margin: 5px 0 0; font-size: 0.9em; color: #6B7280;">Device is offline or no signal and not reporting data.</p>
        </div>
        <img src="/images/icons/offline-icon.png" alt="Offline Device Icon" style="width: 40px; height: 40px; margin-left: auto;">
    </div>
</div>

<!-- GPS Data Section with Loader -->
<div id="gpsDataContainer" class="info-box" style="width: 250px; margin: 20px auto; padding: 20px; border-radius: 12px; background-color: #f9f9f9; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); font-family: 'Arial', sans-serif; text-align: justify;">
    <h2 style="font-size: 1.5em; font-weight: bold; color: #3B82F6; margin-bottom: 20px; text-align: center;">GPS Data</h2>
    
    <!-- Loader -->
    <div style="display: flex; justify-content: center; align-items: center; flex-direction: column; gap: 10px;">
        <div class="loader" style="width: 40px; height: 40px; border: 4px solid #e5e7eb; border-top: 4px solid #3B82F6; border-radius: 50%; animation: spin 1s linear infinite;"></div>
        <div style="font-size: 1em; color: #6B7280; font-weight: 500; margin-top: 10px;">
            Data is loading... Please wait as we retrieve the most up-to-date GPS data for your device.
        </div>
    </div>
</div>

<!-- Loader Animation -->
<style>
    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }
</style>


   <!-- Last Known Location Section -->
<div id="lastKnownLocation" class="info-box" style="width: 250px; margin: 20px auto; padding: 20px; border-radius: 12px; background-color: #f9f9f9; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); font-family: 'Arial', sans-serif; text-align: center;">
    <h2 style="font-size: 1.5em; font-weight: bold; color: #3B82F6; margin-bottom: 20px; text-align: center;">Last Known Location</h2>
    
    <div id="lastKnownLocationData" class="tile" style="display: flex; align-items: center; justify-content: center; gap: 10px; padding: 15px; background-color: #ffffff; border: 1px solid #e5e7eb; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);">
        <i class="fas fa-map-marker-alt" style="color: #3B82F6; font-size: 1.5em;"></i>
        <span id="lastLocationText" style="font-size: 1em; color: #6B7280; font-weight: 500;">Fetching last known location...</span>
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
    </div>


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


<div class="button-group">
    <button id="startRental1" class="action-btn start-btn">
        <i class="fas fa-play"></i> Start Rental
    </button>
    <button id="stopRental1" class="action-btn stop-btn" style="display: none;">
        <i class="fas fa-stop"></i> Stop Rental
    </button>
    <button id="exportLogs" class="action-btn export-btn">
        <i class="fas fa-download"></i> Export Logs (GPS02)
    </button>
</div>

<script>
    // Function to export logs for GPS02 as CSV
function exportLogsForGPS02() {
    // Filter logs for GPS02
    const filteredLogs = Object.entries(rentalLogs1).filter(([_, log]) => log.deviceId === 'GPS02');

    if (!filteredLogs.length) {
        alert('No rental logs available for GPS02 to export.');
        return;
    }

    // Generate CSV content
    const headers = ['Rental ID', 'Device ID', 'Created', 'Rented', 'Returned', 'Total Distance'];
    const rows = filteredLogs.map(([rentalId, log]) => {
        const createdDate = new Date(log.createdTimestamp).toLocaleString();
        const rentedDate = new Date(log.rentedTimestamp).toLocaleString();
        const returnedDate = log.returnedTimestamp
            ? new Date(log.returnedTimestamp).toLocaleString()
            : 'Not Returned';
        const totalDistance = log.returnedTimestamp
            ? `${log.totalDistance.toFixed(2)} km`
            : 'Not Calculated';

        return [
            rentalId,
            log.deviceId,
            createdDate,
            rentedDate,
            returnedDate,
            totalDistance
        ].join(',');
    });

    const csvContent = [headers.join(','), ...rows].join('\n');

    // Create a blob and download it as a CSV file
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);

    const a = document.createElement('a');
    a.href = url;
    a.download = 'rental_logs_gps02.csv';
    a.style.display = 'none';

    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);

    alert('Rental logs for GPS02 exported successfully.');
}

// Attach event listener to the Export Logs button
document.getElementById('exportLogs').addEventListener('click', exportLogsForGPS02);

</script>


<!-- Rental Logs Table -->
<div class="scrollable-table">
    <table id="rentalLogs1Table">
        <thead>
            <tr>
                <th>Device ID</th>
                <th>Created</th>
                <th>Rented</th>
                <th>Returned</th>
                <th>Total Distance</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Populated dynamically by JavaScript -->
        </tbody>
    </table>
</div>


<!-- Rental Log Detail View with Close Button -->
<div id="logDetailsContainer" style="display:none;">
    <div class="log-details-content">
        <button id="closeLog" class="close-log-btn">Close Log</button>
        <div id="logDetailsContent">
            <!-- Log details will be inserted here -->
        </div>
    </div>
</div>

<!-- Log Details Modal -->
<div id="logDetailsContainer" style="display:none;">
    <!-- Log details will be inserted here -->
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
                </script>

                <script>
                        let map;
                        let marker;
                        let lastKnownLocations = JSON.parse(localStorage.getItem('lastKnownLocations')) || {};
                        let locationHistory = JSON.parse(localStorage.getItem('locationHistory')) || {};

                        function updateClock() {
                    const now = new Date();
                    

                    
                    const dateOptions = {
                        weekday: 'long',   // shows day of the week
                        year: 'numeric',   // shows year in numeric format
                        month: 'long',     // shows month in long format
                        day: 'numeric'     // shows day in numeric format
                    };
                    
                    document.getElementById('date').textContent = now.toLocaleDateString(undefined, dateOptions);
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

                            function animateMarker(newLat, newLng) {
    const currentPos = marker.getPosition();
    const targetPos = { lat: newLat, lng: newLng };

    let step = 0;
    const totalSteps = 200; // Number of steps for smoother movement (higher value = smoother)
    const intervalTime = 10; // Interval time in milliseconds (lower value = smoother)

    // Easing function for smooth acceleration and deceleration (ease-in-out)
    function easeInOutQuad(t, b, c, d) {
        t /= d / 2;
        if (t < 1) return (c / 2) * t * t + b;
        t--;
        return (-c / 2) * (t * (t - 2) - 1) + b;
    }

    // Function to update the marker's position and map's center
    function move() {
        if (step <= totalSteps) {
            const progress = easeInOutQuad(step, 0, 1, totalSteps);

            // Calculate intermediate position
            const lat = currentPos.lat() + (targetPos.lat - currentPos.lat()) * progress;
            const lng = currentPos.lng() + (targetPos.lng - currentPos.lng()) * progress;

            // Update marker's position smoothly
            marker.setPosition(new google.maps.LatLng(lat, lng));

            // Smoothly pan the map to follow the marker
            map.panTo(new google.maps.LatLng(lat, lng));

            step++;

            // Recursive call with a slight delay
            setTimeout(move, intervalTime);
        } else {
            // Ensure marker and map are perfectly at the target position
            marker.setPosition(targetPos);
            map.panTo(targetPos);
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
                    // If there is an existing marker, remove it first
                    if (marker) {
                        marker.setMap(null);  // This will remove the old marker from the map
                    }

                    // Create a new marker at the initial position
                    const carIconUrl = '/images/icons/Gps1-icon.png';
                    marker = new google.maps.Marker({
                        position: { lat: parseFloat(device.latitude), lng: parseFloat(device.longitude) },
                        map: map,
                        title: device.gps_id,
                        icon: {
                            url: carIconUrl,
                            scaledSize: new google.maps.Size(62, 62),
                        }
                    });

                    // Center the map to the new marker's position
                    map.setCenter(marker.getPosition());

                    // Optionally, set the zoom level explicitly
                    map.setZoom(15); // Example of setting the zoom level to a fixed value

                    // Animate the marker smoothly to the new position and keep map focused on the marker
                    animateMarker(parseFloat(device.latitude), parseFloat(device.longitude));
                }




                            // Get street name from latitude and longitude using Google Geocoding API
                            function getStreetName(lat, lng, gps_id) {
                                const geocodeUrl = `https://maps.googleapis.com/maps/api/geocode/json?latlng=${lat},${lng}&key=AIzaSyCWrXtUyyqoWHwLddsIRgZKjKc9YGeW7FI`;

                                $.getJSON(geocodeUrl, function(response) {
                                    let street = 'Unavailable';
                                    if (response.status === 'OK' && response.results.length > 0) {
                                        street = response.results[0].formatted_address;
                                    }
                                    $(`#street_${gps_id}`).text(street);
                                }).fail(function() {
                                    $(`#street_${gps_id}`).text('Unavailable');
                                });
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

                            // Initialize map and start data fetching
                            initMap();
                            fetchGPSData();
                            setInterval(fetchGPSData, 10000); // Fetch GPS data every 10 seconds
                        });

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
                    function addLegend() {
                    const legend = document.getElementById('legend');
                    map.controls[google.maps.ControlPosition.RIGHT_TOP].push(legend);
                }

                // Call the addLegend function after initializing the map
                function initMap() {
                    map = new google.maps.Map(document.getElementById('map'), {
                        center: { lat: 0, lng: 0 },
                        zoom: 10
                    });

                    addLegend(); // Add the legend to the map
                }
                
    </script>
    
    
    
    

     <script>
const deviceId = 'GPS02'; // Hardcoded device ID for GPS02
let rentalLogs1 = JSON.parse(localStorage.getItem('rentalLogs1')) || {};
let currentRentalId1 = localStorage.getItem('currentRentalId1') || '';
let lastCoordinates1 = JSON.parse(localStorage.getItem('lastCoordinates1')) || null;
let isRentalActive1 = currentRentalId1 !== ''; // Set rental active if currentRentalId1 exists


// Ensure actions are only triggered for the correct device
function validateDeviceId1(actionName) {
    if (deviceId !== 'GPS02') {
        alert(`This action (${actionName}) is only allowed for GPS02.`);
        return false;
    }
    return true;
}

// Start Rental Button (Only for GPS02)
document.getElementById('startRental1').addEventListener('click', () => {
    if (!validateDeviceId1('Start Rental')) return;
    startRental1();
});

// Stop Rental Button (Only for GPS02)
document.getElementById('stopRental1').addEventListener('click', () => {
    if (!validateDeviceId1('Stop Rental')) return;
    stopRental1();
});

// Export Logs Button (Only for GPS02)
document.getElementById('exportLogs').addEventListener('click', () => {
    if (!validateDeviceId1('Export Logs')) return;
    exportLogs();
});
    // Function to display rental logs in the table
function displayRentalLogs1() {
    const logsTable = document.querySelector('#rentalLogs1Table tbody');
    logsTable.innerHTML = Object.entries(rentalLogs1)
        .filter(([_, log]) => log.deviceId === 'GPS02') // Only include logs for GPS02
        .map(([rentalId, log]) => {
            const createdDate = new Date(log.createdTimestamp).toLocaleDateString('en-US', { 
                year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' 
            });
            const rentedDate = new Date(log.rentedTimestamp).toLocaleDateString('en-US', { 
                year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' 
            });
            const returnedDate = log.returnedTimestamp
                ? new Date(log.returnedTimestamp).toLocaleDateString('en-US', { 
                    year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' 
                })
                : 'Not Returned';
            const totalDistance = log.returnedTimestamp
                ? `${log.totalDistance.toFixed(2)} km`
                : 'Not Calculated';

            return `
                <tr>
                    <td>${log.deviceId}</td>
                    <td>${createdDate}</td>
                    <td>${rentedDate}</td>
                    <td>${returnedDate}</td>
                    <td>${totalDistance}</td>
                    <td>
                        <button class="view-log-btn" data-rental-id="${rentalId}">View Log</button>
                        <button class="delete-log-btn" data-rental-id="${rentalId}">Delete</button>
                    </td>
                </tr>`;
        })
        .join('');
}


    async function getAddressFromCoordinates(lat, lon) {
    const apiKey = 'AIzaSyCWrXtUyyqoWHwLddsIRgZKjKc9YGeW7FI'; // Replace with your actual API key
    const url = `https://maps.googleapis.com/maps/api/geocode/json?latlng=${lat},${lon}&key=${apiKey}`;

    try {
        const response = await fetch(url);
        const data = await response.json();

        if (data.status === 'OK' && data.results.length > 0) {
            return data.results[0].formatted_address; // Return the first result
        } else {
            return 'Address not found';
        }
    } catch (error) {
        console.error('Error fetching address:', error);
        return 'Error fetching address';
    }
}

async function viewLogDetails(rentalId) {
    const log = rentalLogs1[rentalId];
    if (!log) {
        alert('Rental log not found.');
        return;
    }

    const address = log.currentLatitude && log.currentLongitude
        ? await getAddressFromCoordinates(log.currentLatitude, log.currentLongitude)
        : 'N/A';

    const logDetailsHTML = `
        <div style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; padding: 20px;">
            <h3 style="color: #4CAF50; margin-bottom: 15px;">Rental Log Details</h3>
            
            <p><strong>Device ID:</strong> ${log.deviceId}</p>
            <p><strong>Created:</strong> ${new Date(log.createdTimestamp).toLocaleDateString('en-US', { 
                year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' 
            })}</p>
            <p><strong>Rented:</strong> ${new Date(log.rentedTimestamp).toLocaleDateString('en-US', { 
                year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' 
            })}</p>
            <p><strong>Returned:</strong> ${log.returnedTimestamp 
                ? new Date(log.returnedTimestamp).toLocaleDateString('en-US', { 
                    year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' 
                }) 
                : '<span style="color: red;">Not Returned</span>'}</p>
            <p><strong>Total Distance:</strong> ${log.totalDistance 
                ? `${log.totalDistance.toFixed(2)} km` 
                : '<span style="color: red;">Not Calculated</span>'}</p>

            <h4 style="color: #4CAF50; margin-top: 25px; margin-bottom: 15px;">GPS Monitoring</h4>
            <p><strong>Current Location:</strong> ${address}</p>
            <p><strong>Current Speed:</strong> ${log.currentSpeed ? `${log.currentSpeed} km/h` : '<em>0 km/h</em>'}</p>
        </div>
    `;

    const logDetailsContainer = document.getElementById('logDetailsContent');
    logDetailsContainer.innerHTML = logDetailsHTML;
    document.getElementById('logDetailsContainer').style.display = 'block';
}

    // Close the rental log details
    function closeLogDetails() {
        document.getElementById('logDetailsContainer').style.display = 'none';
    }

    // Function to create a new rental log
function createRentalLog(rentalId) {
    return {
        deviceId,
        createdTimestamp: Date.now(),
        rentedTimestamp: Date.now(),
        status: 'active',
        currentLatitude: null,
        currentLongitude: null,
        currentSpeed: 0,
        totalDistance: 0,
        returnedTimestamp: null,
        data: []
    };
}

    // Function to start a rental session
function startRental1() {
    // Check if the device is GPS02
    if (deviceId !== 'GPS02') {
        alert('Only GPS02 is allowed to start a rental session.');
        return;
    }

    // Proceed with rental logic
    $.ajax({
        url: '/gps/fetch',
        type: 'GET',
        dataType: 'json',
        success: handleGPSData,
        error: () => alert('Error fetching GPS data. Please try again later.')
    });
}


    // Handle GPS data to start a rental
function handleGPSData(data) {
    const deviceActive = data.some(
        (device) => device.gps_id === deviceId && device.latitude && device.longitude
    );

    if (!deviceActive) {
        alert('Device is not active. Please check and try again.');
        return;
    }

    currentRentalId1 = `rental_${Date.now()}`;
    localStorage.setItem('currentRentalId1', currentRentalId1);

    rentalLogs1[currentRentalId1] = createRentalLog(currentRentalId1);
    localStorage.setItem('rentalLogs1', JSON.stringify(rentalLogs1));

    displayRentalLogs1();
    toggleRentalButtons(true);
    isRentalActive1 = true;
    monitorGPSData();
}

    // Function to stop a rental session
  function stopRental1() {
    if (!currentRentalId1 || !rentalLogs1[currentRentalId1]) {
        alert('No active rental session to stop.');
        return;
    }

    const rental = rentalLogs1[currentRentalId1];
    if (rental.status === 'stopped') {
        alert('Rental session is already stopped.');
        return;
    }

    rental.status = 'stopped';
    rental.returnedTimestamp = Date.now();
    rental.totalDistance = calculateTotalDistance(rental);

    localStorage.setItem('rentalLogs1', JSON.stringify(rentalLogs1));
    localStorage.removeItem('currentRentalId1');
    displayRentalLogs1();

    toggleRentalButtons(false);
    isRentalActive1 = false;

    alert(`Rental session ended. Total distance: ${rental.totalDistance.toFixed(2)} km`);
}


// Initialize button visibility based on deviceId
function initializeButtons() {
    if (deviceId !== 'GPS02') {
        document.getElementById('startRental1').style.display = 'none'; // Hide start button for other devices
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    initializeButtons();
    displayRentalLogs1();
    resumeRentalSession();
});


// Attach event listeners
document.getElementById('stopRental1').addEventListener('click', stopRental1);

// Call the function on page load
document.addEventListener('DOMContentLoaded', initializeButtons);

    // Monitor GPS data
 function monitorGPSData() {
    if (!isRentalActive1) return;

    fetchGPSData();
    setTimeout(monitorGPSData, 10000);
}

    // Fetch and update GPS data
   function fetchGPSData() {
    $.ajax({
        url: '/gps/fetch',
        type: 'GET',
        dataType: 'json',
        success: (data) => {
            const rental = rentalLogs1[currentRentalId1];
            if (!rental || rental.status !== 'active') return;

            data.forEach((device) => {
                if (device.gps_id === deviceId && device.latitude && device.longitude) {
                    updateMonitoringData(rental, device);
                }
            });
        }
    });
}

    // Update monitoring data
     function updateMonitoringData(rental, device) {
    rental.currentLatitude = device.latitude;
    rental.currentLongitude = device.longitude;
    rental.currentSpeed = device.speed || 0;

    if (lastCoordinates1) {
        rental.totalDistance += haversineDistance(
            lastCoordinates1.latitude, lastCoordinates1.longitude,
            device.latitude, device.longitude
        );
    }
    
     rental.data.push({ latitude: device.latitude, longitude: device.longitude });
    lastCoordinates1 = { latitude: device.latitude, longitude: device.longitude };

    localStorage.setItem('lastCoordinates1', JSON.stringify(lastCoordinates1));
    localStorage.setItem('rentalLogs1', JSON.stringify(rentalLogs1));
}

    
          // Save the current session state on page unload
          window.addEventListener('beforeunload', () => {
            localStorage.setItem('rentalLogs1', JSON.stringify(rentalLogs1));
            localStorage.setItem('currentRentalId1', currentRentalId1);
            localStorage.setItem('lastCoordinates1', JSON.stringify(lastCoordinates1));
        });

    // Calculate total distance using Haversine formula
 function calculateTotalDistance(rental) {
    let totalDistance = 0;
    const coordinates = rental.data;

    for (let i = 0; i < coordinates.length - 1; i++) {
        const { latitude: lat1, longitude: lon1 } = coordinates[i];
        const { latitude: lat2, longitude: lon2 } = coordinates[i + 1];

        totalDistance += haversineDistance(lat1, lon1, lat2, lon2);
    }

    return totalDistance;
}

    // Haversine formula for calculating distance
    function haversineDistance(lat1, lon1, lat2, lon2) {
        const R = 6371; // Earth's radius in km
        const dLat = deg2rad(lat2 - lat1);
        const dLon = deg2rad(lon2 - lon1);
        const a =
            Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
            Math.sin(dLon / 2) * Math.sin(dLon / 2);
        return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    }

    // Convert degrees to radians
    function deg2rad(deg) {
        return deg * (Math.PI / 180);
    }

    // Toggle start/stop buttons
   function toggleRentalButtons(isActive) {
    document.getElementById('startRental1').style.display = isActive ? 'none' : 'inline-block';
    document.getElementById('stopRental1').style.display = isActive ? 'inline-block' : 'none';
}

  // Resume ongoing rental on page load
       function resumeRentalSession() {
    if (currentRentalId1 && rentalLogs1[currentRentalId1]?.status === 'active') {
        isRentalActive1 = true;
        monitorGPSData(); // Resume GPS monitoring
        toggleRentalButtons(true);
    }
}
        
    // Initialize on page load
        function initialize() {
            displayRentalLogs1();
            resumeRentalSession();
        }
        
        

    // Event listeners
    document.getElementById('stopRental1').addEventListener('click', stopRental1);
    document.getElementById('closeLog').addEventListener('click', closeLogDetails);
    $(document).on('click', '.delete-log-btn', function () {
        const rentalId = $(this).data('rental-id');
        if (confirm('Are you sure you want to delete this rental log?')) {
            delete rentalLogs1[rentalId];
            localStorage.setItem('rentalLogs1', JSON.stringify(rentalLogs1));
            displayRentalLogs1();
        }
    });
    $(document).on('click', '.view-log-btn', function () {
        const rentalId = $(this).data('rental-id');
        viewLogDetails(rentalId);
    });

     // Initialize
        displayRentalLogs1();
        resumeRentalSession();
         initialize();
</script>








<style>
    .scrollable-table {
    max-height: 400px; /* Set the maximum height for the table */
    overflow-y: auto; /* Enable vertical scrolling */
    border: 1px solid #ddd; /* Optional: Add a border around the scrollable area */
}

.scrollable-table table {
    width: 100%; /* Ensure the table spans the full width */
    border-collapse: collapse; /* Optional: Collapse table borders */
}

.scrollable-table th, .scrollable-table td {
    padding: 8px; /* Add padding for better readability */
    text-align: left; /* Align text to the left */
    border-bottom: 1px solid #ddd; /* Add a bottom border for rows */
}

.scrollable-table th {
    background-color: #f4f4f4; /* Light background for header */
    position: sticky; /* Sticky header */
    top: 0; /* Position at the top */
    z-index: 1; /* Ensure the header is above the rows */
}

    .close-log-btn {
        background-color: #f44336; /* Red */
        color: white;
        padding: 10px 15px;
        border: none;
        cursor: pointer;
        margin-top: 20px;
        border-radius: 5px;
    }

    .close-log-btn:hover {
        background-color: #d32f2f; /* Darker Red */
    }

    .close-modal {
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 20px;
        cursor: pointer;
        color: #333;
    }


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
        padding: 10px;
        background-color: #ffffff;
        border-radius: 6px;
        margin-bottom: 10px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: background-color 0.3s ease;
        cursor: pointer;
    }

    .legend-item:hover {
        background-color: #f0f4f8;
    }

    .legend-icon {
        width: 40px;
        height: 40px;
    }

    /* Card Styling */
    .card {
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin-bottom: 20px;
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

    /* Map Container */
    #map {
        width: 100%;
        height: 1000px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin-top: 20px;
    }

    /* Last Known Location and GPS Data Containers */
    #lastKnownLocation,
    #gpsDataContainer {
        flex: 1;
        background-color: #ffffff;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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

    .delete-btn:hover {
        background-color: #c0392b;
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
    
  
     
    /* Style the rental logs table */
#rentalLogs1Container {
    margin-top: 20px;
    padding: 15px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

#rentalLogs1Table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

#rentalLogs1Table th, #rentalLogs1Table td {
    padding: 10px;
    text-align: left;
    border: 1px solid #ddd;
}

#rentalLogs1Table th {
    background-color: #4CAF50;
    color: white;
}

#rentalLogs1Table tbody tr:nth-child(even) {
    background-color: #f9f9f9;
}

#rentalLogs1Table tbody tr:hover {
    background-color: #f1f1f1;
}

/* Style the buttons */
.button-group {
    margin-top: 20px;
    display: flex;
    gap: 15px;
    justify-content: center;
}

.action-btn {
    padding: 10px 20px;
    font-size: 16px;
    cursor: pointer;
    border-radius: 5px;
    border: none;
    display: inline-flex;
    align-items: center;
    transition: background-color 0.3s ease;
}

.start-btn {
    background-color: #4CAF50;
    color: white;
}

.start-btn:hover {
    background-color: #45a049;
}

.stop-btn {
    background-color: #f44336;
    color: white;
}

.stop-btn:hover {
    background-color: #e53935;
}

.action-btn i {
    margin-right: 8px;
}

.view-log-btn {
    background-color: #2196F3;
    color: white;
    padding: 5px 10px;
    border-radius: 5px;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.view-log-btn:hover {
    background-color: #1976D2;
}

/* Add some space between the table and buttons */
#rentalLogs1Container {
    margin-top: 40px;
}

/* Style for Delete button */
.delete-log-btn {
    background-color: #f44336;  /* Red color */
    color: white;
    padding: 5px 10px;
    border-radius: 5px;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s ease;
    font-size: 14px;
}

.delete-log-btn:hover {
    background-color: #e53935;
}

    

.close-log-btn {
        background-color: #f44336; /* Red */
        color: white;
        padding: 10px 15px;
        border: none;
        cursor: pointer;
        margin-top: 20px;
        border-radius: 5px;
    }

    .close-log-btn:hover {
        background-color: #d32f2f; /* Darker Red */
    }

    #logDetailsContainer {
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 5px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
    .modal {
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .modal-content {
        background-color: #fff;
        padding: 20px;
        border-radius: 5px;
        width: 50%;
        max-width: 600px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        position: relative;
        text-align: left;
    }
    .close-modal {
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 20px;
        cursor: pointer;
        color: #333;
    }
    .button-group {
    display: flex;
    gap: 10px;
}

.action-btn {
    padding: 10px 15px;
    font-size: 14px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 5px;
}

.action-btn i {
    font-size: 16px;
}

.start-btn {
    background-color: #4caf50; /* Green */
    color: white;
}

.stop-btn {
    background-color: #f44336; /* Red */
    color: white;
}

.export-btn {
    background-color: #2196f3; /* Blue */
    color: white;
}

.action-btn:hover {
    opacity: 0.9;
}

.action-btn:active {
    transform: scale(0.98);
    opacity: 0.8;
}



</style>
</body>
</html>
@endsection
