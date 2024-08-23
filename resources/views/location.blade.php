@extends('layouts.myapp')

@section('content')
<style>
    /* Font style for paragraphs */
    .custom-font {
        font-family: "Century Gothic", sans-serif;
        font-size: 18px;
        line-height: 1.6;
    }

    /* Container styling */
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    /* Section heading styling */
    .section-heading {
        font-size: 24px;
        font-weight: bold;
        color: #333;
        margin-bottom: 20px;
    }

    /* Card styling */
    .card {
        background-color: #f9fafb;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    /* Card title styling */
    .card-title {
        font-size: 20px;
        font-weight: bold;
        color: #333;
        margin-bottom: 10px;
    }

    /* Card content styling */
    .card-content {
        font-size: 16px;
        color: #666;
        margin-bottom: 8px;
    }

    /* Map container styling */
    .map-container {
        margin-top: 40px;
        overflow: hidden;
        position: relative;
        width: 100%;
        height: 400px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    /* Responsive map styling */
    .map {
        width: 100%;
        height: 100%;
        border: 0;
    }
</style>

<div class="container">
    <div class="text-center mb-8">
        <img src="/images/logos/R3LOGO.png" alt="logo" class="w-[300px] mx-auto">
    </div>

    <div class="text-center mb-8">
    <h2 class="text-3xl font-bold text-gray-800 mb-4">Welcome to R3 Garage Car Rental Service</h2>
    <div class="max-w-3xl mx-auto">
        <p class="text-lg text-gray-700 leading-relaxed mb-4">Discover our convenient rental locations strategically positioned for your ease. Whether you're a local explorer or a traveler, our locations are designed to provide easy access to quality vehicles, ensuring a seamless rental experience.</p>
        <p class="text-lg text-gray-700 leading-relaxed mb-4">Explore our diverse fleet and find the perfect vehicle to suit your needs. From compact cars to spacious SUVs, we have a range of options to accommodate your travel plans.</p>
        <p class="text-lg text-gray-700 leading-relaxed">Experience convenience, reliability, and comfort with our rental services. Start your journey with us today!</p>
    </div>
</div>


    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="card">
            <h2 class="card-title">Company Information:</h2>
            <p class="card-content">R3 Garage Car Rental</p>
            <p class="card-content">Location: Marikina, Philippines</p>
        </div>

        <div class="card">
            <h2 class="card-title">Address:</h2>
            <p class="card-content">36 Friendship St. Friendly Village 1,</p>
            <p class="card-content">Marikina City, Philippines</p>
            <p class="card-content">Zip Code/Postal code: 1807</p>
        </div>

        <div class="card">
            <h2 class="card-title">Call us:</h2>
            <p class="card-content">Call us to speak to a member of our team.</p>
            <p class="card-content">We are always happy to help.</p>
            <p class="card-content">+63-955-379-3727</p>
        </div>
    </div>

    <div class="map-container" style="width: 1159px; height: 700px; display: flex; justify-content: center;">
    <iframe class="map" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3860.1782295743333!2d121.10443815026572!3d14.645822049462128!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397b9ba367f57b3%3A0x492856bd610c4ee4!2s1%2C%2036%20Friendship%2C%20Marikina%2C%201800%20Metro%20Manila!5e0!3m2!1sen!2sph!4v1712750045270!5m2!1sen!2sph" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
</div>

</div>
@endsection
