@extends('layouts.myapp')
@section('title', 'R3 Garage Car Rental | Home')
@section('content')

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Bootstrap JS (for Slideshow Functionality) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

<main>
    <div class="row justify-content-center">
        <div class="container-fluid p-0">
            <!-- Banner Slideshow Section -->
            <div id="photoSlideshow" class="carousel slide carousel-fade" data-ride="carousel">
                <div class="carousel-inner">
                    <!-- Slide 1 -->
                    <div class="carousel-item active">
                        <div class="banner d-flex align-items-center justify-content-center">
                            <div class="banner-content text-white text-center">
                                <h1 class="banner-title">Welcome to R3 Garage Car Rentals</h1>
                                <p class="lead">Your trusted partner in finding the perfect vehicle for your next journey.</p>
                                <button class="btn btn-light explore-btn mt-4" onclick="scrollToSection()">Explore Now</button>
                            </div>
                        </div>
                    </div>

                    <!-- Slide 2 -->
                    <div class="carousel-item">
                        <div class="banner d-flex align-items-center justify-content-center">
                            <div class="banner-content text-white text-center">
                                <h1 class="banner-title">Explore Our Premium Fleet</h1>
                                <p class="lead">Find a car for every adventure!</p>
                                <button class="btn btn-light explore-btn mt-4" onclick="scrollToSection()">Explore Now</button>
                            </div>
                        </div>
                    </div>

                    <!-- Slide 3 -->
                    <div class="carousel-item">
                        <div class="banner d-flex align-items-center justify-content-center">
                            <div class="banner-content text-white text-center">
                                <h1 class="banner-title">Drive in Style</h1>
                                <p class="lead">Reliable and affordable car rentals, anytime.</p>
                                <button class="btn btn-light explore-btn mt-4" onclick="scrollToSection()">Explore Now</button>
                            </div>
                        </div>
                    </div>

                    <!-- Slide 4 -->
                    <div class="carousel-item">
                        <div class="banner d-flex align-items-center justify-content-center">
                            <div class="banner-content text-white text-center">
                                <h1 class="banner-title">Unforgettable Journeys</h1>
                                <p class="lead">Create lasting memories with our top-notch car rentals.</p>
                                <button class="btn btn-light explore-btn mt-4" onclick="scrollToSection()">Start Your Journey</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Controls for manual navigation -->
                <a class="carousel-control-prev" href="#photoSlideshow" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#photoSlideshow" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Full Pick-up and Return Date/Time Section with Header, Car Brands, Location Input, and Search Button -->
<section class="py-5" style="background: linear-gradient(135deg, #4e73df, #1cc88a);">
    <div class="container p-5 rounded" style="background: linear-gradient(135deg, #ffffff, #f8f9fc); box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);">
        <!-- Header: Choose Dates & Location -->
        <div class="text-center mb-4">
            <h3 class="text-dark" style="font-size: 2rem;">Choose Dates & Location</h3>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <!-- Pick-up Date/Time -->
                    <div class="d-flex flex-column mb-3 mb-md-0">
                        <h5 class="text-dark" style="font-size: 1.5rem;">Pick-up Date/Time</h5>
                        <div class="input-group input-group-lg">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fa fa-calendar"></i>
                                </span>
                            </div>
                            <input type="date" class="form-control" value="2024-09-27">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="fa fa-clock"></i>
                                </span>
                            </div>
                            <input type="time" class="form-control" value="08:00">
                        </div>
                    </div>

                    <!-- Return Date/Time -->
                    <div class="d-flex flex-column mb-3 mb-md-0">
                        <h5 class="text-dark" style="font-size: 1.5rem;">Return Date/Time</h5>
                        <div class="input-group input-group-lg">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fa fa-calendar"></i>
                                </span>
                            </div>
                            <input type="date" class="form-control" value="2024-09-29">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="fa fa-clock"></i>
                                </span>
                            </div>
                            <input type="time" class="form-control" value="08:00">
                        </div>
                    </div>
                </div>

                <!-- Car Brand and Location -->
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start mt-4">
                    <!-- Car Brand -->
                    <div class="d-flex flex-column mb-3 mb-md-0">
                        <h5 class="text-dark" style="font-size: 1.5rem;">Car Brand</h5>
                        <select class="form-control form-control-lg" id="carBrand">
                            <option value="TOYOTA">TOYOTA</option>
                            <option value="MITSUBISHI">MITSUBISHI</option>
                            <option value="NISSAN">NISSAN</option>
                            <option value="MG">MG</option>
                        </select>
                    </div>

                    <!-- Location Input -->
                    <div class="d-flex flex-column mb-3 mb-md-0">
                        <h5 class="text-dark" style="font-size: 1.5rem;">Enter Location</h5>
                        <input type="text" class="form-control form-control-lg" id="locationInput" placeholder="Enter location">
                    </div>

                    <!-- Search Button -->
                    <div class="ml-0 mt-3 mt-md-0">
                        <button type="button" class="btn btn-primary btn-lg shadow-sm" onclick="submitSearch()">Search</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function submitSearch() {
        const carBrand = document.getElementById('carBrand').value;
        const location = document.getElementById('locationInput').value;

        // Redirect to the cars page with query parameters for brand and location
        window.location.href = `https://r3garagecarrental.online/cars?brand=${carBrand}&location=${location}`;
    }
</script>

<!-- Display Blog Posts -->
<div class="container mt-4">
    <h2 class="section-title text-center mb-4" style="font-family: 'Century Gothic', sans-serif; font-weight: bold;">Latest Posts</h2>

    @if($posts->count() > 0)
        <div class="row">
            @foreach($posts as $post)
                <div class="col-md-6 mb-4"> <!-- Use two columns for a blog-style layout -->
                    <div class="card h-100 shadow-sm"> <!-- Add shadow for a modern blog style -->
                        @if($post->image_path)
                            <img src="{{ asset('images/posts/' . $post->image_path) }}" alt="{{ $post->title }}" class="card-img-top" style="object-fit: contain; width: 100%; height: 300px;"> <!-- Display full image with object-fit: contain -->
                        @else
                            <div class="card-img-top bg-light d-flex justify-content-center align-items-center" style="height: 300px;">
                                <p class="text-danger">Image not found.</p> <!-- Optional: Show an error if no image -->
                            </div>
                        @endif

                        <div class="card-body" style="font-family: 'Times New Roman', Times, serif;">
                            <h3 class="card-title text-center">{{ $post->title }}</h3>

                            <p class="card-text" style="text-align: justify;">
                                {{ Str::limit($post->content, 150) }} <!-- Limit the content to 150 characters -->
                            </p>

                            <!-- Optional: Blog post metadata (author and date) -->
                            <p class="text-muted" style="font-size: 0.9rem;">
    <em>By {{ $post->author }} | {{ $post->created_at->timezone('Asia/Manila')->format('M d, Y - h:i A') }}</em>
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-center" style="font-family: 'Times New Roman', Times, serif;">No posts available.</p> <!-- Center the message and apply font -->
    @endif
</div>


















<!-- Section to scroll to (e.g., Our Cars section) -->
<section id="ourCars" class="our-cars py-5 bg-light">
    <div class="container text-center">
        <h3 class="section-title mb-4">Best Choice</h3>
        <div class="row justify-content-center">
        @if($cars->count() > 0)
            @foreach($cars as $car)
                <div class="col-sm-12 col-md-6 col-lg-4 mb-4 car-card-wrapper">
                    <div class="car-card card border-0 shadow-sm rounded-lg overflow-hidden h-100">
                        <!-- Modern Horizontal Best Choice Ribbon -->
                        <div class="ribbon">Best Choice</div>

                        <a href="{{ route('car.reservation', ['car' => $car->id]) }}" class="img-wrapper">
                            <img class="card-img-top img-fluid" src="{{ asset($car->images->first()->image_path) }}" alt="{{ $car->brand }} {{ $car->model }}">
                        </a>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title font-weight-bold">{{ $car->brand }} {{ $car->model }}</h5>
                            <p class="text-muted">{{ $car->engine }}</p>
                            <p class="card-text text-primary">₱{{ number_format($car->price_per_day, 2) }}</p>
                            <a href="{{ route('car.reservation', ['car' => $car->id]) }}" class="btn btn-primary btn-block mt-auto shadow-sm">Reserve Now</a>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <p>No cars available.</p> <!-- Message if no cars are found -->
        @endif
        </div>
        <!-- See All Cars Button -->
        <a href="https://r3garagecarrental.online/cars" class="btn btn-outline-secondary mt-4 px-4 py-2 shadow-sm">See All Cars</a>
    </div>
</section>





<style>
    .car-card-wrapper {
        opacity: 0; /* Initially hidden */
        transform: translateY(30px); /* Start position from below */
        transition: opacity 0.6s ease-out, transform 0.6s ease-out; /* Smooth transition */
    }

    .car-card-wrapper.visible {
        opacity: 1; /* Fully visible */
        transform: translateY(0); /* Final position */
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const carCards = document.querySelectorAll('.car-card-wrapper');

        function checkScroll() {
            const viewportHeight = window.innerHeight;

            carCards.forEach(card => {
                const cardPosition = card.getBoundingClientRect().top;

                // Add visible class when scrolling down
                if (cardPosition < viewportHeight - 100) {
                    card.classList.add('visible');
                } 
                // Remove visible class when scrolling back up
                else {
                    card.classList.remove('visible');
                }
            });
        }

        window.addEventListener('scroll', checkScroll);
        checkScroll(); // Initial check
    });
</script>



<!-- Script for smooth scrolling -->
<script>
function scrollToSection() {
    const targetSection = document.getElementById('ourCars');
    targetSection.scrollIntoView({ behavior: 'smooth' });
}
</script>

<!-- About Us Section -->
<section class="about-us py-4 bg-light">
    <div class="container text-center">
        <h3 class="section-title mb-3">About Us</h3>

        @if($aboutUs)
            <p class="lead">{{ Str::limit($aboutUs->content, 500) }}</p>
        @else
            <p class="lead">Content not available.</p> <!-- Default message if content is missing -->
        @endif
    </div>
</section>







<style>
    .about-us {
        opacity: 0; /* Initially hidden */
        transform: translateY(30px); /* Start position */
        transition: opacity 0.6s ease-out, transform 0.6s ease-out; /* Smooth transition */
    }

    .about-us.visible {
        opacity: 1; /* Fully visible */
        transform: translateY(0); /* Final position */
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const aboutSection = document.querySelector('.about-us');

        function checkScroll() {
            const sectionPosition = aboutSection.getBoundingClientRect().top;
            const viewportHeight = window.innerHeight;

            // Add visible class when scrolling down
            if (sectionPosition < viewportHeight - 100) {
                aboutSection.classList.add('visible');
            } 
            // Remove visible class when scrolling back up
            else {
                aboutSection.classList.remove('visible');
            }
        }

        window.addEventListener('scroll', checkScroll);
        checkScroll(); // Initial check
    });
</script>



<!-- Why Choose Us Section -->
<section class="why-choose-us py-4">
    <div class="container text-center">
        <h3 class="section-title mb-3">Why Choose Us</h3>
        <div class="row">
            <div class="col-sm-12 col-md-4">
                <div class="feature">
                    <i class="fas fa-car fa-3x mb-2"></i>
                    <h4>Variety of Cars</h4>
                    <p>We offer a wide range of vehicles to fit your needs and preferences.</p>
                </div>
            </div>
            <div class="col-sm-12 col-md-4">
                <div class="feature">
                    <i class="fas fa-headset fa-3x mb-2"></i>
                    <h4>24/7 Support</h4>
                    <p>Our customer support is available around the clock to assist you.</p>
                </div>
            </div>
            <div class="col-sm-12 col-md-4">
                <div class="feature">
                    <i class="fas fa-money-bill fa-3x mb-2"></i>
                    <h4>Affordable Prices</h4>
                    <p>We provide competitive pricing on all our rental cars.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .why-choose-us .feature {
        opacity: 0; /* Initially hidden */
        transform: translateY(30px); /* Start position */
        transition: opacity 0.6s ease-out, transform 0.6s ease-out; /* Smooth transition */
    }

    .why-choose-us .feature.visible {
        opacity: 1; /* Fully visible */
        transform: translateY(0); /* Final position */
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const features = document.querySelectorAll('.why-choose-us .feature');

        function checkScroll() {
            features.forEach(feature => {
                const sectionPosition = feature.getBoundingClientRect().top;
                const viewportHeight = window.innerHeight;

                if (sectionPosition < viewportHeight - 100) { // Trigger when entering the viewport
                    feature.classList.add('visible');
                } else { // Remove the visible class when scrolling up
                    feature.classList.remove('visible');
                }
            });
        }

        window.addEventListener('scroll', checkScroll);
        checkScroll(); // Check on initial load
    });
</script>




<!-- Gallery Section -->
<section class="gallery py-4 bg-light">
    <div class="container text-center">
        <h3 class="section-title mb-4">Gallery</h3>
        <div class="row">
            @foreach($galleryImages as $image)
                <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                    <img src="{{ asset($image->image_path) }}" alt="Gallery Image" class="img-fluid rounded shadow-sm">
                </div>
            @endforeach
        </div>
    </div>
</section>

<style>
    .gallery-img {
        opacity: 0; /* Initially hidden */
        transform: translateY(30px); /* Start position */
        transition: opacity 0.6s ease-out, transform 0.6s ease-out; /* Smooth transition */
    }

    .gallery-img.visible {
        opacity: 1; /* Fully visible */
        transform: translateY(0); /* Final position */
        animation: bounce 0.6s forwards; /* Bounce effect */
    }

    @keyframes bounce {
        0% { transform: translateY(30px); }
        30% { transform: translateY(-10px); }
        50% { transform: translateY(5px); }
        100% { transform: translateY(0); }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const galleryItems = document.querySelectorAll('.gallery-img');

        function checkScroll() {
            galleryItems.forEach(item => {
                const sectionPosition = item.getBoundingClientRect().top;
                const viewportHeight = window.innerHeight;

                if (sectionPosition < viewportHeight - 100) { // Trigger when entering the viewport
                    item.classList.add('visible');
                } else { // Remove the visible class when scrolling up
                    item.classList.remove('visible');
                }
            });
        }

        window.addEventListener('scroll', checkScroll);
        checkScroll(); // Check on initial load
    });
</script>


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
/* General Styles */
body {
    margin: 0;
    padding: 0;
    font-family: 'Century Gothic', sans-serif;
}

/* Banner Slideshow Section */
.carousel-item {
    position: relative;
    height: 100vh; /* Full-screen height */
}

.banner {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-size: cover; /* Ensures the entire image covers the background */
    background-position: center; /* Center the image */
}

.carousel-item:nth-child(1) .banner {
    background-image: url('{{ asset('images/homepage/1.png') }}'); /* Replace with your first image */
}

.carousel-item:nth-child(2) .banner {
    background-image: url('{{ asset('images/homepage/2.png') }}'); /* Replace with your second image */
}

.carousel-item:nth-child(3) .banner {
    background-image: url('{{ asset('images/homepage/3.png') }}'); /* Replace with your third image */
}

.carousel-item:nth-child(4) .banner {
    background-image: url('{{ asset('images/homepage/4.png') }}'); /* Replace with your fourth image */
}
.banner-content {
    z-index: 2;
}

.banner-title {
    font-size: 4rem;
    font-weight: bold;
    color: white;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
    margin-bottom: 20px;
}

.banner .lead {
    font-size: 1.5rem;
    color: white;
    text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
}

/* Carousel fade effect */
.carousel-fade .carousel-item {
    transition: opacity 1s ease-in-out;
    opacity: 0;
}

.carousel-fade .carousel-item.active {
    opacity: 1;
}

/* Optional: Controls Styling */
.carousel-control-prev-icon,
.carousel-control-next-icon {
    background-color: rgba(0, 0, 0, 0.5);
    border-radius: 50%;
    padding: 10px;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .banner-title {
        font-size: 2.5rem;
    }

    .banner .lead {
        font-size: 1.2rem;
    }
}

/* General Section Styling */
.our-cars {
    background-color: #f8f9fa; /* Light background to differentiate the section */
    padding: 5rem 0;
}

.section-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #333;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 1.5rem;
    position: relative;
}

.section-title::after {
    content: '';
    display: block;
    width: 100px;
    height: 4px;
    background-color: #007bff; /* Blue accent color */
    margin: 1rem auto 0;
    border-radius: 2px;
}

/* Card Styles */
.card {
    border-radius: 15px; /* Rounded corners */
    transition: transform 0.3s ease, box-shadow 0.3s ease; /* Smooth transition on hover */
    overflow: hidden;
    position: relative;
}

.card:hover {
    transform: translateY(-10px); /* Lifts the card on hover */
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2); /* Soft shadow on hover */
}

/* Image Wrapper for controlling the aspect ratio */
.img-wrapper {
    overflow: hidden;
}

.card-img-top {
    object-fit: cover; /* Ensures the image covers the container without distortion */
    height: 200px; /* Fixes the image height to maintain consistency */
    border-top-left-radius: 15px;
    border-top-right-radius: 15px;
}

/* Card Body Styling */
.card-body {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    text-align: left;
}

.card-title {
    font-size: 1.25rem;
    color: #333;
    font-weight: 600;
}

.text-primary {
    color: #007bff !important; /* Price color */
    font-weight: 700;
    font-size: 1.2rem;
}

.text-muted {
    font-size: 0.9rem;
    color: #6c757d;
}

/* Buttons */
.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
    padding: 0.75rem 1rem;
    font-weight: 600;
    border-radius: 10px;
    box-shadow: 0 5px 10px rgba(0, 123, 255, 0.2); /* Soft shadow */
}

.btn-primary:hover {
    background-color: #0056b3;
    box-shadow: 0 10px 20px rgba(0, 123, 255, 0.3);
}

.btn-outline-secondary {
    border-color: #6c757d;
    color: #6c757d;
    transition: all 0.3s ease;
}

.btn-outline-secondary:hover {
    background-color: #6c757d;
    color: #fff;
}

/* Responsive Layout Adjustments */
@media (max-width: 768px) {
    .card-img-top {
        height: 180px; /* Adjust image height for smaller screens */
    }

    .section-title {
        font-size: 2rem;
    }
}

/* Gallery Images */
.gallery-img {
    cursor: pointer;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    width: 100%;
    height: auto;
    max-height: 470px;
    object-fit: cover;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    transform-style: preserve-3d;
}

.gallery-img:hover {
    transform: scale(1.05) rotateZ(1deg);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
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
    transform-style: preserve-3d;
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
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    transform: perspective(1000px) rotateY(0deg);
    transition: transform 0.3s ease;
}

.modal-content img, .modal-content video {
    max-width: 100%;
    max-height: 100%;
    height: auto;
    border-radius: 15px;
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
    z-index: 1050;
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
    background-color: rgba(0,0,0,0.8);
}

.next {
    right: 0;
    border-radius: 3px 0 0 3px;
}

.prev:hover, .next:hover {
    background-color: rgba(0,0,0,1);
}

#caption {
    color: #fff;
    text-align: center;
    font-size: 18px;
    margin-top: 10px;
    text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
}

.text-shadow {
    text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
}

@media (max-width: 768px) {
    .section-title {
        font-size: 2rem;
    }
    .card {
        margin-bottom: 1rem;
    }
    .carousel-inner img {
        max-height: 300px;
    }
    .gallery-img {
        max-height: 300px;
    }
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
/* Ensure the card takes full width and has a max height */
.car-card {
    display: flex;
    flex-direction: column;
    max-width: 100%; /* Allows card to expand to full width */
    max-height: 900px; /* Set the maximum height for the card */
    height: 100%;
    margin: 0 auto;
}

/* Ensure images fit the card width and cover the area */
.card-img-top {
    width: 100%; /* Ensure image takes full width */
    height: 500px; /* Fixed height for the image */
    object-fit: cover; /* Ensure image covers the area */
    max-height: 700px; /* Max height to fit in the card */
}

/* Ensure card body expands to fill remaining space */
.card-body {
    display: flex;
    flex-direction: column;
    flex-grow: 1; /* Allows body to fill the space */
    overflow: hidden; /* Prevents overflow of content */
}

/* Optional: If you want to ensure the card fits within the container */
.row {
    display: flex;
    flex-wrap: wrap; /* Ensures cards wrap to the next line */
}
/* Style for the 'See All Cars' button */
.btn-secondary {
    background-color: #6c757d; /* Adjust this color as needed */
    border-color: #6c757d; /* Adjust this color as needed */
}

.btn-secondary:hover {
    background-color: #5a6268; /* Adjust this color as needed */
    border-color: #545b62; /* Adjust this color as needed */
}

/* Responsive layout adjustments */
@media (max-width: 767.98px) {
    .row {
        display: flex;
        flex-wrap: wrap; /* Ensure cards wrap to next line on small screens */
        margin-left: -15px; /* Adjust spacing */
        margin-right: -15px; /* Adjust spacing */
    }
    .col-md-4 {
        flex: 0 0 100%; /* Full width on small screens */
        max-width: 100%; /* Full width on small screens */
    }
    .btn-secondary {
        display: block; /* Ensure button is block level to prevent overlap */
        width: 100%; /* Full width on small screens */
    }
}
.explore-btn {
        padding: 10px 20px;
        font-size: 1.2rem;
        border-radius: 25px;
        background-color: rgba(255, 255, 255, 0.8);
        color: #333;
        transition: background-color 0.3s, transform 0.3s;
    }

    .explore-btn:hover {
        background-color: rgba(255, 255, 255, 1);
        transform: scale(1.05);
    }
    /* Modern Horizontal Ribbon Styling */
.ribbon {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    background-color: #e74c3c; /* Flat, modern red */
    color: white;
    font-size: 14px;
    font-weight: bold;
    text-transform: uppercase;
    text-align: center;
    padding: 5px 0;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
    z-index: 10; /* Ensures it's above other content */
}

.ribbon::before {
    content: "";
    position: absolute;
    bottom: -10px;
    left: 0;
    width: 0;
    height: 0;
    border-left: 10px solid transparent;
    border-right: 10px solid transparent;
    border-top: 10px solid #e74c3c;
}

.ribbon::after {
    content: "";
    position: absolute;
    bottom: -10px;
    right: 0;
    width: 0;
    height: 0;
    border-left: 10px solid transparent;
    border-right: 10px solid transparent;
    border-top: 10px solid #e74c3c;
}

</style>

@endsection
