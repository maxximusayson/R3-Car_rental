@extends('layouts.app')

@section('content')
<style>
    /* Custom CSS for styling the GCash UI */
    html, body {
        margin: 0;
        padding: 0;
        height: 100%; /* Make sure body takes full height */
        overflow: hidden; /* Disable scrolling */
    }

    body {
        background-color: #007bff; /* GCash background color */
        background-attachment: fixed; /* Make the background fixed */
        background-size: cover; /* Ensure background covers the entire viewport */
        display: flex;
        justify-content: center;
        align-items: center; /* Vertically center the content */
        flex-direction: column;
    }

    .gcash-logo {
    width: 250px; /* Adjust the size of the logo */
    margin-bottom: 30px; /* Space between the logo and content */
    margin-left: 75px; /* Adjust this value to move the logo more to the right */
    /* margin-right: 50px; */ /* Optional: you can adjust the right margin if necessary */
}


    .gcash-container {
        display: flex;
        justify-content: center;
        align-items: center;
        font-family: Arial, sans-serif;
        flex-direction: column;
        text-align: center;
    }

    .qr-card {
        background-color: white;
        padding: 20px;
        border-radius: 20px;
        width: 400px;
        text-align: center;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        position: relative;
        display: flex;
        justify-content: center; /* Center horizontally */
        align-items: center; /* Center vertically */
        flex-direction: column; /* Ensure content is stacked vertically */
    }

    .qr-image {
        width: 500px; /* Adjust QR code size */
        border-radius: 8px;
        margin: 15px 0; /* Space above and below the QR code */
    }

    .user-name {
        font-weight: bold;
        font-size: 1.2em;
        color: #333;
        margin-top: 10px;
    }

    .user-number {
        font-size: 0.9em;
        color: #555;
    }

    .back-button {
        margin-top: 20px;
    }

    .btn-custom {
        background-color: #007bff;
        color: white;
        border: none;
        font-size: 1em;
        padding: 10px 20px;
        border-radius: 5px;
        text-decoration: none;
    }

    .btn-custom:hover {
        background-color: #0056b3;
        color: white;
    }
</style>
</head>
<body>

<!-- Centered GCash Logo -->
<img src="{{ asset('images/icons/gcash-icon.png') }}" alt="GCash Logo" class="gcash-logo">

<div class="gcash-container">
    <div class="qr-card">
        
        <!-- QR Code Image -->
        <img src="{{ asset('images/icons/GcashQr.jpg') }}" alt="QR Code" class="qr-image">

        <!-- Go Back to Reservation Button -->
        <div class="back-button">
            <a href="{{ route('reservations.show', ['id' => 109]) }}" class="btn-custom">
                <i class="fas fa-arrow-left"></i> Go Back to Reservation
            </a>
        </div>
    </div>
</div>

@endsection
