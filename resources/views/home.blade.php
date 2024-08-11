@extends('layouts.myapp')
@section('content')
<style>
    body {
        background-color: #e8f4fa;
    }
    .main-content {
        text-align: center;
    }
    .cover-photo {
        width: 100%;
        height: 400px;
        max-height: 600px;
        margin-bottom: 20px;
        margin-left: auto;
        margin-right: auto;
        position: relative;
    }
    .slideshow-container {
        width: 100%;
        height: 100%;
        position: relative;
        overflow: hidden;
    }
    .mySlides {
        display: none;
    }
    .fade {
        animation: fadeEffect 3s linear infinite;
    }
    @keyframes fadeEffect {
        0% {opacity: 0;}
        20% {opacity: 1;}
        80% {opacity: 1;}
        100% {opacity: 0;}
    }
    .search-form {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
        max-width: 100%;
        margin: 0 auto;
    }
    .search-form div {
        flex: 1 1 15%;
        display: flex;
        flex-direction: column;
        min-width: 150px;
    }
    .search-form div label {
        margin-bottom: 5px;
        font-weight: bold;
    }
    .search-form div input, .search-form div button {
        padding: 10px;
        font-size: 1em;
    }
    .search-form div button {
        cursor: pointer;
        background-color: #007BFF;
        color: #fff;
        border: none;
        border-radius: 5px;
    }
    @media (max-width: 768px) {
        body {
            justify-content: flex-start;
            padding-top: 20px;
        }
        .search-form {
            flex-direction: column;
            align-items: stretch;
            max-width: 100%;
            width: 100%;
        }
        .search-form div {
            flex: 1 1 100%;
        }
    }
    .how-it-works, .popular-cars, .why-choose-us, .gallery {
        padding: 40px 20px;
        background-color: #d9effb;
        text-align: center;
        margin-bottom: 40px;
        border-radius: 8px;
    }
    .section-title {
        text-align: center;
        padding: 20px;
        background-color: #3B9ABF;
        color: #fff;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
    }
    .steps {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 20px;
    }
    .step {
        flex: 1 1 100px;
        text-align: center;
        padding: 20px;
        background-color: #f7f7f7;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    .step:hover {
        transform: translateY(-5px);
        background-color: #fff;
    }
    .step-icon, .step-number {
        font-size: 24px;
        color: #007bff;
        margin-bottom: 5px;
        font-weight: bold;
    }
    .step-text {
        font-size: 16px;
        color: #333;
    }
    .step-icon {
        font-size: 66px; /* Default size */
        color: #007bff;
        margin-bottom: 10px;
    }
    .large-icon {
        font-size: 58px; /* Increased size */
    }
    .cars {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: center;
    }
    .car {
        background-color: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        flex: 1 1 200px;
        text-align: center;
    }
    .car img {
        width: 100%;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    .features {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 20px;
    }
    .feature {
        background: white;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 20px;
        flex: 1 1 300px;
        max-width: 300px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s;
    }
    .feature:hover {
        transform: translateY(-10px);
    }
    .feature h4 {
        font-size: 1.5em;
        color: #007BFF;
        margin-bottom: 10px;
    }
    .feature p {
        font-size: 1em;
        color: #555;
        line-height: 1.5;
    }
    .gallery-images {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 10px;
    }
    .gallery .image img {
        width: 100%;
        height: auto;
        display: block;
        cursor: pointer;
        border-radius: 5px;
    }
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        padding-top: 100px;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.9);
    }
    .modal-content {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 550px;
        animation-name: zoom;
        animation-duration: 0.6s;
    }
    @keyframes zoom {
        from { transform: scale(0); }
        to { transform: scale(1); }
    }
    .close {
        position: absolute;
        top: 15px;
        right: 35px;
        color: #fff;
        font-size: 40px;
        font-weight: bold;
        transition: 0.3s;
    }
    .close:hover,
    .close:focus {
        color: #bbb;
        text-decoration: none;
        cursor: pointer;
    }
    #caption {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 700px;
        text-align: center;
        color: #ccc;
        padding: 10px 0;
        height: 150px;
    }
    .prev, .next {
        cursor: pointer;
        position: absolute;
        top: 50%;
        width: auto;
        padding: 16px;
        margin-top: -50px;
        color: white;
        font-weight: bold;
        font-size: 20px;
        transition: 0.3s;
        user-select: none;
        -webkit-user-select: none;
    }
    .prev {
        left: 0;
    }
    .next {
        right: 0;
    }
    .prev:hover, .next:hover {
        background-color: rgba(0, 0, 0, 0.8);
    }
    @media (max-width: 1200px) {
        .main-content {
            padding: 50px 20px;
        }
        .search-form {
            flex-direction: column;
        }
        .search-form button {
            width: 100%;
        }
    }
    @media (max-width: 768px) {
        .steps, .cars, .features {
            flex-direction: column;
        }
        .cover-photo {
            height: 300px;
        }
        .section-title h3,
        .feature h4,
        .step-text {
            font-size: 1.2em;
        }
    }
    @media (max-width: 576px) {
        .main-content {
            padding: 30px 10px;
        }
        .section-title h3,
        .feature h4,
        .step-text {
            font-size: 1em;
        }
        .cover-photo {
            height: 200px;
        }
    }
    h2 {
        font-size: 2.5em;
        color: #333;
    }
    p {
        font-size: 1.2em;
        color: #666;
    }
    .why-choose-us .feature i {
        font-size: 3em;
    }
    body {
        text-align: center;
    }
</style>
<main>

    
<!-- Homepage Section -->
<div class="main-content">
    <div class="cover-photo">
        <div class="slideshow-container">
            <div class="mySlides fade">
                <img src="{{ asset('images/homepage/advertise4.jpg') }}" style="width:100%">
            </div>
            <div class="mySlides fade">
                <img src="{{ asset('images/homepage/advertise2.jpg') }}" style="width:100%">
            </div>
            <div class="mySlides fade">
                <img src="{{ asset('images/homepage/advertise3.jpg') }}" style="width:100%">
            </div>
        </div>
    </div>
</div>

<!-- search -->
<div class="search-form">
        <div>
            <label for="location">Location</label>
            <input type="text" id="location" name="location" placeholder="Enter City, or Address">
        </div>
        <div>
            <label for="pickup_date">Pickup Date</label>
            <input type="date" id="pickup_date" name="pickup_date" value="2024-05-11">
        </div>
        <div>
            <label for="pickup_time">Pickup Time</label>
            <input type="time" id="pickup_time" name="pickup_time" value="08:00">
        </div>
        <div>
            <label for="return_date">Return Date</label>
            <input type="date" id="return_date" name="return_date" value="2024-05-11">
        </div>
        <div>
            <label for="return_time">Return Time</label>
            <input type="time" id="return_time" name="return_time" value="17:00">
        </div>
        <div>
            <button type="submit">Search</button>
        </div>
    </div>
</div>

<h1>Welcome to R3 Garage Car Rental</h1>
<!-- About Us Section -->
<div class="about-us" style="padding: 40px 20px; background-color: #d9effb; text-align: center; margin-bottom: 40px; border-radius: 8px;">
    <div class="section-title" style="padding: 20px; background-color: #3B9ABF; color: #fff; border-top-left-radius: 10px; border-top-right-radius: 10px;"><h3>About Us</h3></div>
    <p style="font-size: 1.2em; color: #666; line-height: 1.5;">Welcome to our car rental service! We strive to provide the best car rental experience to our customers. With a wide range of vehicles and flexible rental options, we make it easy for you to find the perfect car for your needs. Our dedicated team is committed to ensuring your satisfaction from reservation to return. Whether you need a car for a day, a week, or longer, we're here to help you get on the road and enjoy your journey.</p>
</div>

<!-- How It Works Section -->
<div class="how-it-works">
    <div class="section-title"><h3>How It Works</h3></div>
    <div class="steps">
        <div class="step">
            <div class="step-icon">🛒</div>
            <div class="step-number">1</div>
            <div class="step-text">Choose Your Car</div>
        </div>
        <div class="step">
            <div class="step-icon">📅</div>
            <div class="step-number">2</div>
            <div class="step-text">Select Pickup Date & Time</div>
        </div>
        <div class="step">
            <div class="step-icon">💳</div>
            <div class="step-number">3</div>
            <div class="step-text">Make a Reservation</div>
        </div>
        <div class="step">
            <div class="step-icon">🚗</div>
            <div class="step-number">4</div>
            <div class="step-text">Pickup Your Car</div>
        </div>
        <div class="step">
            <div class="step-icon">🛣️</div>
            <div class="step-number">5</div>
            <div class="step-text">Enjoy Your Ride</div>
        </div>
        <div class="step">
            <div class="step-icon">🔄</div>
            <div class="step-number">6</div>
            <div class="step-text">Return the Car</div>
        </div>
    </div>
</div>


        {{-- Our Cars Section --}}
        <section class="mx-auto max-w-screen-xl">
    <div class="flex align-middle  justify-center">
    <hr class="mt-8 h-2" style="background-color: #3B8CBF !important; width: 40%;">
        <p class="my-2 mx-8 p-2 font-bold text-black-900 text-3xl" style="font-family: Arial Black, sans-serif;">OUR CARS</p>
        <hr class="mt-8 h-2" style="background-color: #3B8CBF !important; width: 40%;">
        <hr>        
    </div>
    <div class="md:mr-16 mr-4 mb-4 flex justify-end">
    <a href="/cars">
    <button type="button" class="px-4 lg:px-5 py-2 lg:py-2.5 mr-2 text-white bg-custom-orange hover-bg-custom-orange font-medium rounded-lg text-sm" style="font-family: Arial Black, Arial, sans-serif;">
    See All
    </button>
    </a>
    </div>
</div>


<div class="grid md:grid-cols-3 md:ps-4 justify-center p-2 gap-4 items-center mx-auto max-w-screen-xl">
    @foreach ($cars as $car)
    <div class="relative md:m-10 flex w-full max-w-xs flex-col overflow-hidden rounded-xl border border-orange-900 bg-white shadow-md">
        <a class="relative mx-3 mt-3 flex h-60 overflow-hidden rounded-xl"
            href="{{ route('car.reservation', ['car' => $car->id]) }}">
            <img loading="lazy" class="object-cover" src="{{ $car->image }}" alt="" />
        </a>
        <div class="mt-4 px-5 pb-5">
            <div>
                <h5 class="font-bold text-xl tracking-tight text-black font-arial">{{ $car->brand }}
                    {{ $car->model }} {{ $car->engine }}</h5>
            </div>
            <div class="mt-2 mb-5 flex items-center justify-between">
                <p>
                    <span class="text-3xl font-bold text-black">₱{{ number_format($car->price_per_day, 2) }}</span>
                </p>
          
                <div class="flex items-center justify-between">
    <a href="{{ route('car.reservation', ['car' => $car->id]) }}"
        class="flex items-center justify-center rounded-md bg-custom  px-6 py-3 text-center text-sm font-medium text-white focus:outline-none focus:ring-4 focus:ring-blue-300 font-arial shadow-md">
        
        <div>
            <span class="font-bold">Reserve Now</span>
        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>


    <!-- Why Choose Us Section -->
<div class="why-choose-us">
    <div class="section-title"><h3>Why Choose Us</h3></div>
    <div class="features">
        <div class="feature">
            <i class="fas fa-car fa-3x"></i>
            <h4>Variety of Cars</h4>
            <p>We offer a wide range of vehicles to fit your needs and preferences.</p>
        </div>
        <div class="feature">
            <i class="fas fa-headset fa-3x"></i>
            <h4>24/7 Support</h4>
            <p>Our customer support is available around the clock to assist you.</p>
        </div>
        <div class="feature">
            <i class="fas fa-money-bill fa-3x"></i>
            <h4>Affordable Prices</h4>
            <p>We provide competitive pricing on all our rental cars.</p>
        </div>
        <div class="feature">
            <i class="fas fa-shield-alt fa-3x"></i>
            <h4>Comprehensive Insurance</h4>
            <p>Enjoy peace of mind with our comprehensive insurance options.</p>
        </div>
        <div class="feature">
            <i class="fas fa-tachometer-alt fa-3x"></i>
            <h4>Easy Booking</h4>
            <p>Experience a hassle-free booking process through our user-friendly platform.</p>
        </div>
    </div>
</div>

       <!-- Gallery Section -->
<section class="gallery" style="padding: 40px 20px; margin-bottom: 400px;">
    <div class="section-title">
        <h3>Gallery</h3>
    </div>
    <div class="gallery-images">
        <div class="image"><img src="{{ asset('images/gallery/Gallery1.jpg') }}" alt="Gallery Image 1" onclick="openModal(0)"></div>
        <div class="image"><img src="{{ asset('images/gallery/Gallery2.jpg') }}" alt="Gallery Image 2" onclick="openModal(1)"></div>
        <div class="image"><img src="{{ asset('images/gallery/Gallery3.jpg') }}" alt="Gallery Image 3" onclick="openModal(2)"></div>
        <div class="image"><img src="{{ asset('images/gallery/Gallery4.jpg') }}" alt="Gallery Image 4" onclick="openModal(3)"></div>
        <div class="image"><img src="{{ asset('images/gallery/Gallery5.jpg') }}" alt="Gallery Image 5" onclick="openModal(4)"></div>
        <div class="image"><img src="{{ asset('images/gallery/Gallery6.jpg') }}" alt="Gallery Image 6" onclick="openModal(5)"></div>
        <div class="image"><img src="{{ asset('images/gallery/Gallery7.jpg') }}" alt="Gallery Image 6" onclick="openModal(6)"></div>
        <div class="image"><img src="{{ asset('images/gallery/Gallery8.jpg') }}" alt="Gallery Image 6" onclick="openModal(7)"></div>
        <div class="image"><img src="{{ asset('images/gallery/Gallery9.jpg') }}" alt="Gallery Image 6" onclick="openModal(8)"></div>
        <div class="image"><img src="{{ asset('images/gallery/Gallery10.jpg') }}" alt="Gallery Image 6" onclick="openModal(9)"></div>
    </div>
</section>


<!-- Modal Structure -->
<div id="myModal" class="modal">
    <span class="close" onclick="closeModal()">&times;</span>
    <img class="modal-content" id="modalImage">
    <div id="caption"></div>
    <a class="prev" onclick="changeImage(-1)">&#10094;</a>
    <a class="next" onclick="changeImage(1)">&#10095;</a>
</div>


<script>
var currentImageIndex = 0;
var images = document.querySelectorAll('.gallery .image img');

function openModal(index) {
    currentImageIndex = index;
    var modal = document.getElementById("myModal");
    var modalImg = document.getElementById("modalImage");
    var captionText = document.getElementById("caption");

    modal.style.display = "block";
    modalImg.src = images[currentImageIndex].src;
    captionText.innerHTML = images[currentImageIndex].alt;
}

function closeModal() {
    var modal = document.getElementById("myModal");
    modal.style.display = "none";
}

function changeImage(direction) {
    currentImageIndex += direction;

    if (currentImageIndex >= images.length) {
        currentImageIndex = 0;
    } else if (currentImageIndex < 0) {
        currentImageIndex = images.length - 1;
    }

    var modalImg = document.getElementById("modalImage");
    var captionText = document.getElementById("caption");

    modalImg.src = images[currentImageIndex].src;
    captionText.innerHTML = images[currentImageIndex].alt;
}
var slideIndex = 0;
showSlides();

function showSlides() {
    var i;
    var slides = document.getElementsByClassName("mySlides");
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }
    slideIndex++;
    if (slideIndex > slides.length) {slideIndex = 1}
    slides[slideIndex-1].style.display = "block";
    setTimeout(showSlides, 3000); // Change image every 3 seconds
}

</script>
@endsection
