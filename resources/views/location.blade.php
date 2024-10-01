@extends('layouts.myapp')
@section('title', 'R3 Garage Car Rental | Location')

@section('content')
<!-- Banner Section -->
<div class="banner">
    <h1>Our Locations</h1>
</div>

<div class="container">
    <div class="content-wrapper">
        <!-- Left Content -->
        <div class="left-content">
    <!-- Marikina Branch Info -->
    <div class="branch-info">
        <div class="branch-icon">
            <i class="fas fa-map-marker-alt"></i>
        </div>
        <div class="branch-details">
            <h2>R3 Garage - Marikina Branch</h2>
            <p><strong>Phone:</strong> 0955-379-3727</p>
            <p><strong>Email:</strong> rilleracl@yahoo.com</p>
            <p><strong>Address:</strong> 36 Friendship st. Friendly Village 1, Marikina City, Philippines</p>
        </div>
    </div>

    <!-- Isabela Branch Info -->
    <div class="branch-info">
        <div class="branch-icon">
            <i class="fas fa-map-marker-alt"></i>
        </div>
        <div class="branch-details">
            <h2>R3 Garage - Isabela Branch</h2>
            <p><strong>Phone:</strong> 0955-379-3727</p>
            <p><strong>Email:</strong> rilleracl@yahoo.com</p>
            <p><strong>Address:</strong> Alicia, Isabela, Philippines</p>
        </div>
    </div>
</div>

        <!-- Right Content (Maps for both locations) -->
        <div class="right-content">
            <!-- Marikina Map -->
            <div class="map-container">
                <iframe class="map" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3860.1782295743333!2d121.10443815026572!3d14.645822049462128!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397b9ba367f57b3%3A0x492856bd610c4ee4!2s1%2C%2036%20Friendship%2C%20Marikina%2C%201800%20Metro%20Manila!5e0!3m2!1sen!2sph!4v1712750045270!5m2!1sen!2sph" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>

            <!-- Alicia, Isabela Map -->
            <div class="map-container" style="margin-top: 20px;">
                <iframe class="map" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3930.7441736430677!2d121.62655107406403!3d16.780710399872313!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33855af0ffb19173%3A0xf6e07471f8e3d370!2sAlicia%2C%20Isabela%2C%20Philippines!5e0!3m2!1sen!2sph!4v1712751110000!5m2!1sen!2sph" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </div>
</div>
<style>
    /* General container styling */
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px;
        font-family: 'Century Gothic', sans-serif;
    }

    /* Flexbox layout for the main content and map */
    .content-wrapper {
        display: flex;
        flex-direction: column;
        gap: 40px;
    }

    @media (min-width: 768px) {
        .content-wrapper {
            flex-direction: row;
            justify-content: space-between;
        }
    }

    /* Left content styling */
    .left-content {
        flex: 1;
    }

    /* Right content (map) styling */
    .right-content {
        flex: 1;
        margin-left: 20px;
    }

    /* Card container styling */
    .card {
        background-color: #ffffff;
        border-radius: 12px;
        padding: 25px;
        text-align: center;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease-in-out;
    }

    .card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 25px rgba(0, 0, 0, 0.15);
    }

    /* Card title styling */
    .card-title {
        font-size: 22px;
        font-weight: 600;
        color: #333333;
        margin-bottom: 15px;
    }

    /* Card content styling */
    .card-content {
        font-size: 16px;
        color: #666666;
        margin-bottom: 12px;
    }

    /* Map container styling */
    .map-container {
        overflow: hidden;
        position: relative;
        width: 100%;
        height: 450px;
        border-radius: 12px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    /* Responsive map styling */
    .map {
        width: 100%;
        height: 100%;
        border: none;
        border-radius: 12px;
    }

    /* Welcome text styling */
    .welcome-text {
        text-align: left;
        margin-bottom: 40px;
    }

    .welcome-text h2 {
        font-size: 36px;
        font-weight: 700;
        color: #222222;
        margin-bottom: 20px;
    }

    .welcome-text p {
        font-size: 18px;
        color: #4a4a4a;
        line-height: 1.8;
        margin-bottom: 10px;
    }

    /* Banner styling */
    .banner {
        background-size: cover;
        background-position: center;
        background-image: url('images/homepage/cover4.png'); /* Replace with your image URL */
        height: 400px;
        display: flex;
        justify-content: center;
        align-items: center;
        position: relative;
    }

    .banner::before {
        content: '';
        position: absolute;
        inset: 0;
        background-color: rgba(0, 0, 0, 0.4);
    }

    .banner h1, .banner p {
        position: relative;
        color: white;
        z-index: 2;
        text-align: center;
    }

    .banner h1 {
        font-size: 3rem;
        font-weight: bold;
        font-family: 'Century Gothic', sans-serif;
    }

    .banner p {
        font-size: 1.25rem;
        margin-top: 10px;
        font-family: 'Century Gothic', sans-serif;
    }
      /* General container styling */
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px;
        font-family: 'Inter', sans-serif;
    }

    /* Flexbox layout for the main content and map */
    .content-wrapper {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
    }

    .left-content {
        width: 45%;
    }

    .right-content {
        width: 50%;
    }

    .branch-info {
        display: flex;
        gap: 15px;
        background-color: #ffffff;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        transition: box-shadow 0.3s;
    }

    .branch-info:hover {
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
    }

    .branch-image {
        flex: 1;
        min-width: 150px;
    }

    .branch-image img {
        max-width: 100%;
        border-radius: 8px;
    }

    .branch-details {
        flex: 2;
    }

    .branch-details h2 {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 10px;
        color: #333;
    }

    .branch-details p {
        font-size: 1rem;
        color: #666;
        margin-bottom: 8px;
    }

    .toggle-button {
        display: block;
        margin-top: 5px;
        background-color: transparent;
        border: none;
        color: #007bff;
        font-size: 1rem;
        cursor: pointer;
    }

    .toggle-button:hover {
        text-decoration: underline;
    }

    /* Map container styling */
    .map-container {
        position: relative;
        width: 100%;
        height: 600px;
        border-radius: 12px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .map {
        width: 100%;
        height: 100%;
        border: none;
        border-radius: 12px;
    }
    /* Flexbox layout for the main content and icon */
    .branch-info {
        display: flex;
        gap: 15px;
        background-color: #ffffff;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        transition: box-shadow 0.3s;
    }

    .branch-info:hover {
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
    }

    /* Red Pin Icon Styling */
    .branch-icon {
        font-size: 40px;
        color: #ff0000; /* Red color for the pin */
        flex: 0 0 50px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .branch-details {
        flex: 1;
    }

    .branch-details h2 {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 10px;
        color: #333;
    }

    .branch-details p {
        font-size: 1rem;
        color: #666;
        margin-bottom: 8px;
    }
    /* Responsive Design for Small Screens */
    @media (max-width: 768px) {
        .content-wrapper {
            flex-direction: column;
        }

        .left-content, 
        .right-content {
            width: 100%;
            margin-left: 0;
        }

        .map-container {
            height: 300px;
        }

        .branch-icon {
            font-size: 30px;
        }

        .branch-details h2 {
            font-size: 1.25rem;
        }

        .branch-details p {
            font-size: 0.9rem;
        }
    }

    /* Even Smaller Devices */
    @media (max-width: 480px) {
        .branch-info {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .branch-icon {
            font-size: 25px;
            margin-bottom: 10px;
        }

        .map-container {
            height: 250px;
        }

        .branch-details h2 {
            font-size: 1.2rem;
        }

        .branch-details p {
            font-size: 0.85rem;
        }
    }

</style>
@endsection
