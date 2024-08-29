@extends('layouts.myapp')
@section('content')

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

<main>
<div class="container-fluid main-content py-4">
<div class="row justify-content-center">
    <!-- Carousel Section -->
    <div class="col-12 col-md-10 col-lg-8">
        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="{{ asset('images/homepage/cover1.png') }}" class="d-block w-100" alt="Cover Image 1">
                    <div class="carousel-caption d-none d-md-block">
                        <h5 class="text-shadow">Unlock Your Dream Ride</h5>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('images/homepage/cover2.png') }}" class="d-block w-100" alt="Cover Image 2">
                    <div class="carousel-caption d-none d-md-block">
                        <h5 class="text-shadow">Incredible Car Rentals Await!</h5>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('images/homepage/cover3.png') }}" class="d-block w-100" alt="Cover Image 3">
                    <div class="carousel-caption d-none d-md-block">
                        <h5 class="text-shadow">Affordable and Reliable</h5>
                    </div>
                </div>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>

    <!-- Welcome Message -->
    <div class="col-12 col-md-10 col-lg-8 text-center">
        <h1 class="welcome-message mb-3">Welcome to Our Car Rental Service!</h1>
        <p class="lead text-muted mb-4">We are thrilled to have you here. Explore our wide range of vehicles and find the perfect one for your next journey.</p>
    </div>
</div>


<!-- Our Cars Section -->
<section class="our-cars py-4">
    <div class="container text-center">
        <h3 class="section-title mb-3">Our Cars</h3>
        <div class="row">
            @foreach ($cars as $car)
            <div class="col-md-4">
                <div class="car-card card mb-3">
                    <a href="{{ route('car.reservation', ['car' => $car->id]) }}">
                        <img class="card-img-top" src="{{ asset($car->images->first()->image_path) }}" alt="{{ $car->brand }} {{ $car->model }}">
                    </a>
                    <div class="card-body">
                        <h5 class="card-title">{{ $car->brand }} {{ $car->model }} {{ $car->engine }}</h5>
                        <p class="card-text">₱{{ number_format($car->price_per_day, 2) }}</p>
                        <a href="{{ route('car.reservation', ['car' => $car->id]) }}" class="btn btn-primary w-100">Reserve Now</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- About Us Section -->
<section class="about-us py-4 bg-light">
    <div class="container text-center">
        <h3 class="section-title mb-3">About Us</h3>
        <p class="lead">Welcome to our car rental service! We strive to provide the best car rental experience to our customers. With a wide range of vehicles and flexible rental options, we make it easy for you to find the perfect car for your needs. Our dedicated team is committed to ensuring your satisfaction from reservation to return.</p>
    </div>
</section>

<!-- Why Choose Us Section -->
<section class="why-choose-us py-4">
    <div class="container text-center">
        <h3 class="section-title mb-3">Why Choose Us</h3>
        <div class="row">
            <div class="col-md-4">
                <div class="feature">
                    <i class="fas fa-car fa-3x mb-2"></i>
                    <h4>Variety of Cars</h4>
                    <p>We offer a wide range of vehicles to fit your needs and preferences.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature">
                    <i class="fas fa-headset fa-3x mb-2"></i>
                    <h4>24/7 Support</h4>
                    <p>Our customer support is available around the clock to assist you.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature">
                    <i class="fas fa-money-bill fa-3x mb-2"></i>
                    <h4>Affordable Prices</h4>
                    <p>We provide competitive pricing on all our rental cars.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Gallery Section -->
<section class="gallery py-4 bg-light">
    <div class="container text-center">
        <h3 class="section-title mb-4">Gallery</h3>
        <div class="row">
            @foreach(range(1, 11) as $i) <!-- Loop for images -->
            <div class="col-md-4 mb-3">
                @php
                    $imagePath = 'images/gallery/' . $i . '.png';
                @endphp
                <img src="{{ asset($imagePath) }}" alt="Gallery Image {{ $i }}" class="img-fluid rounded shadow-sm gallery-img" onclick="openModal({{ $i-1 }}, 'image')">
            </div>
            @endforeach

            @foreach([5 => 'montage.mp4', 11 => 'montage2.mp4'] as $i => $video)
            <div class="col-md-4 mb-3">
                @php
                    $videoPath = 'images/gallery/' . $video;
                @endphp
                <video controls autoplay muted loop class="img-fluid rounded shadow-sm gallery-img" onclick="openModal({{ $i-1 }}, 'video')">
                    <source src="{{ asset($videoPath) }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Modal Structure -->
<div id="myModal" class="modal">
    <span class="close" onclick="closeModal()">&times;</span>
    <div class="modal-content-container">
        <img class="modal-content" id="modalImage" style="display: none;">
        <video class="modal-content" id="modalVideo" controls style="display: none;">
            <source id="videoSource" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>
    <div id="caption"></div>
    <a class="prev" onclick="changeMedia(-1)">&#10094;</a>
    <a class="next" onclick="changeMedia(1)">&#10095;</a>
</div>

<script>
var currentMediaIndex = 0;
var mediaElements = document.querySelectorAll('.gallery-img');

function openModal(index, type) {
    currentMediaIndex = index;
    var modal = document.getElementById("myModal");
    var modalImg = document.getElementById("modalImage");
    var modalVideo = document.getElementById("modalVideo");
    var videoSource = document.getElementById("videoSource");
    var captionText = document.getElementById("caption");

    modal.style.display = "block";

    if (type === 'image') {
        modalImg.src = mediaElements[currentMediaIndex].src;
        modalImg.style.display = "block";
        modalVideo.style.display = "none";
        captionText.innerHTML = mediaElements[currentMediaIndex].alt;
    } else if (type === 'video') {
        videoSource.src = mediaElements[currentMediaIndex].querySelector('source').src;
        modalVideo.load();
        modalVideo.style.display = "block";
        modalImg.style.display = "none";
        captionText.innerHTML = mediaElements[currentMediaIndex].alt;
    }
}

function closeModal() {
    document.getElementById("myModal").style.display = "none";
}

function changeMedia(direction) {
    currentMediaIndex += direction;
    if (currentMediaIndex >= mediaElements.length) {
        currentMediaIndex = 0;
    } else if (currentMediaIndex < 0) {
        currentMediaIndex = mediaElements.length - 1;
    }

    var modalImg = document.getElementById("modalImage");
    var modalVideo = document.getElementById("modalVideo");
    var videoSource = document.getElementById("videoSource");
    var captionText = document.getElementById("caption");

    if (mediaElements[currentMediaIndex].tagName.toLowerCase() === 'img') {
        modalImg.src = mediaElements[currentMediaIndex].src;
        modalImg.style.display = "block";
        modalVideo.style.display = "none";
        captionText.innerHTML = mediaElements[currentMediaIndex].alt;
    } else if (mediaElements[currentMediaIndex].tagName.toLowerCase() === 'video') {
        videoSource.src = mediaElements[currentMediaIndex].querySelector('source').src;
        modalVideo.load();
        modalVideo.style.display = "block";
        modalImg.style.display = "none";
        captionText.innerHTML = mediaElements[currentMediaIndex].alt;
    }
}
</script>


<style>
body {
    background-color: #f4f4f4;
    font-family: 'Century Gothic', sans-serif;
    margin: 0;
    padding: 0;
}

.main-content {
    padding: 40px 0;
    background-color: #ffffff;
    color: #333;
}

.section-title {
    font-size: 2rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: #333;
}

.bg-gradient {
    background-color: #0056b3;
    color: white;
}

.card {
    border-radius: 10px;
    overflow: hidden;
    border: none;
    transition: transform 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.card-title {
    color: #333;
    font-weight: 500;
}

.card-text {
    color: #007bff;
    font-weight: 700;
}

.btn-primary {
    background-color: #0056b3;
    border-color: #0056b3;
    transition: background-color 0.3s ease;
    font-weight: 500;
    padding: 0.6rem 1rem;
    border-radius: 8px;
}

.btn-primary:hover {
    background-color: #004494;
    border-color: #004494;
}

.gallery-img {
    cursor: pointer;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    width: 100%;
    height: auto;
    max-height: 470px; /* Adjust this value based on the desired height */
    object-fit: cover;
    border-radius: 8px;
}

.gallery-img:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    padding-top: 60px;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.9);
}

/* Modal Content */
.modal-content-container {
    text-align: center;
}

.modal-content {
    margin: auto;
    display: block;
    width: 80%;
    max-width: 700px;
}

/* Modal Image */
.modal-content img {
    max-width: 100%;
    max-height: 100%;
    height: auto; /* Maintain aspect ratio */
}

/* Close Button */
.close {
    position: absolute;
    top: 15px;
    right: 35px;
    color: #f1f1f1;
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

/* Previous/Next Buttons */
.prev, .next {
    cursor: pointer;
    position: absolute;
    top: 50%;
    width: auto;
    padding: 16px;
    margin-top: -22px;
    color: white;
    font-weight: bold;
    font-size: 20px;
    transition: 0.6s ease;
    border-radius: 0 3px 3px 0;
    user-select: none;
}

.next {
    right: 0;
    border-radius: 3px 0 0 3px;
}

.prev:hover, .next:hover {
    background-color: rgba(0,0,0,0.8);
}

#caption {
    color: #fff;
    text-align: center;
    font-size: 16px;
    margin-top: 10px;
}

.text-shadow {
    text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.6);
}

@media (max-width: 768px) {
    .section-title {
        font-size: 1.5rem;
    }
    .card {
        margin-bottom: 1rem;
    }
}
.carousel-inner img {
    width: 500%;
    height: auto; /* Maintain aspect ratio */
    max-height: 500px; /* Adjust this value as needed */
    object-fit: cover; /* Ensure the image covers the container */
}
</style>

@endsection
