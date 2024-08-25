@extends('layouts.myapp')

@section('content')
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

    /* Logo styling */
    .logo-container {
        text-align: left;
        margin-bottom: 40px;
    }

    .logo-container img {
        max-width: 250px;
    }
</style>


<div class="container">
    <div class="content-wrapper">
        <!-- Left Content -->
        <div class="left-content">
            <div class="logo-container">
                <img src="/images/logos/R3LOGO.png" alt="logo">
            </div>

            <div class="welcome-text">
                <h2>Welcome to R3 Garage Car Rental Service</h2>
                <div class="max-w-3xl">
                    <p>Discover our convenient rental locations strategically positioned for your ease. Whether you're a local explorer or a traveler, our locations are designed to provide easy access to quality vehicles, ensuring a seamless rental experience.</p>
                    <p>Explore our diverse fleet and find the perfect vehicle to suit your needs. From compact cars to spacious SUVs, we have a range of options to accommodate your travel plans.</p>
                    <p>Experience convenience, reliability, and comfort with our rental services. Start your journey with us today!</p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-10">
                <div class="card">
                    <h2 class="card-title">Company Information</h2>
                    <p class="card-content">R3 Garage Car Rental</p>
                    <p class="card-content">Location: Marikina, Philippines</p>
                </div>

                <div class="card">
                    <h2 class="card-title">Address</h2>
                    <p class="card-content">36 Friendship St. Friendly Village 1,</p>
                    <p class="card-content">Marikina City, Philippines</p>
                    <p class="card-content">Zip Code/Postal code: 1807</p>
                </div>

                <div class="card">
                    <h2 class="card-title">Call Us</h2>
                    <p class="card-content">Call us to speak to a member of our team.</p>
                    <p class="card-content">We are always happy to help.</p>
                    <p class="card-content">+63-955-379-3727</p>
                </div>
            </div>
        </div>

        <!-- Right Content (Map) -->
        <div class="right-content">
            <div class="map-container">
                <iframe class="map" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3860.1782295743333!2d121.10443815026572!3d14.645822049462128!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397b9ba367f57b3%3A0x492856bd610c4ee4!2s1%2C%2036%20Friendship%2C%20Marikina%2C%201800%20Metro%20Manila!5e0!3m2!1sen!2sph!4v1712750045270!5m2!1sen!2sph" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </div>
</div>
@endsection
