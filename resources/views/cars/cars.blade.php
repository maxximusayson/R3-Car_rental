@extends('layouts.myapp')

@section('content')
<style>
    .font-arial-black {
        font-family: 'Arial Black', Arial, sans-serif;
    }

    .bg-custom {
        background-color: #3B8CBF;
    }

    .car-container {
        position: relative;
        transform-style: preserve-3d;
        transition: transform 0.3s ease;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    }

    .hover-message {
        display: none;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        background-color: rgba(0, 0, 0, 0.6);
        padding: 8px 16px;
        border-radius: 8px;
        pointer-events: none;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 14px;
        transition: opacity 0.3s ease, transform 0.3s ease;
        opacity: 0;
    }

    .car-container:hover .hover-message {
        display: block;
        opacity: 1;
        transform: translate(-50%, -50%) scale(1.05);
    }

    #imageModal {
        display: none;
        position: fixed;
        z-index: 50;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
    }

    #imageModalContent {
        position: relative;
        margin: auto;
        padding: 20px;
        width: 80%;
        max-width: 700px;
        background: white;
        border-radius: 8px;
        text-align: justify;
    }

    #modalImage {
        width: 100%;
        height: auto;
        transition: transform 0.3s ease;
    }

    .zoomed {
        transform: scale(1.5);
    }

    .modal-description {
        margin-top: 10px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        text-align: justify;
    }

    .close {
        cursor: pointer;
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 24px;
        color: black;
    }

    .btn-confirm, .btn-cancel {
        margin: 10px;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .btn-confirm {
        background-color: #4CAF50;
        color: white;
    }

    .btn-cancel {
        background-color: #f44336;
        color: white;
    }

    .modal-buttons {
        margin-top: 20px;
        text-align: center;
    }

    .flex-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-top: 20px;
    }

    .flex-container form {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }

    .flex-container form input,
    .flex-container form select {
        margin: 0 10px;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }

    .search-bar {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 20px;
    }

    .search-bar input,
    .search-bar select {
        margin: 0 10px;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }

    #confirmationModal {
        display: none;
        position: fixed;
        z-index: 50;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
    }

    #confirmationModalContent {
        position: relative;
        margin: auto;
        padding: 20px;
        width: 80%;
        max-width: 700px;
        background: white;
        border-radius: 8px;
        text-align: justify;
    }

    .carousel-container {
        position: relative;
        max-width: 100%;
        margin: auto;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .carousel-images {
        display: flex;
        overflow-x: hidden;
        scroll-snap-type: x mandatory;
    }

    .carousel-images img {
        flex-shrink: 0;
        width: 100%;
        height: auto;
        scroll-snap-align: center;
        transition: transform 0.3s ease;
    }

    .carousel-images img:hover {
        transform: scale(1.05);
    }

    .carousel-control {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background-color: rgba(0, 0, 0, 0.5);
        color: white;
        border: none;
        padding: 10px;
        cursor: pointer;
        border-radius: 50%;
    }

    .carousel-control.prev {
        left: 10px;
    }

    .carousel-control.next {
        right: 10px;
    }
</style>

<div class="flex-container">
    <h2 class="font-arial-black text-3xl text-center mb-6">Car Listings</h2>

    <div class="search-bar">
        <input id="searchInput" type="text" placeholder="Search" class="search-input">
        <select id="priceRange" class="price-range">
            <option value="">Price range</option>
            <option value="0-1000">₱0 - ₱1000</option>
            <option value="1000-3000">₱1000 - ₱3000</option>
            <option value="3000-5000">₱3000 - ₱5000</option>
            <option value="5000-10000">₱5000 - ₱10000</option>
        </select>
        <select id="carBranch" class="branch-select">
            <option value="">Branch</option>
            <!-- Add branch options dynamically or statically -->
            <option value="Marikina">Marikina</option>
            <option value="Isabela">Isabela</option>
        </select>
    </div>

    <div class="mt-6 mb-2 grid md:grid-cols-3 justify-center items-center mx-auto max-w-screen-xl" id="carList">
        @foreach ($cars as $car)
            <div class="relative md:m-10 m-4 flex w-full max-w-xs flex-col overflow-hidden rounded-lg border border-orange-100 bg-orange shadow-md car-container car-row" 
                data-brand="{{ $car->brand }}" 
                data-branch="{{ $car->branch }}"
                data-price="{{ $car->price_per_day }}">
                @if ($car->images->isNotEmpty())
                    <a class="relative mx-3 mt-3 flex h-60 overflow-hidden rounded-xl" href="#" data-images="{{ $car->images->pluck('image_path') }}" data-brand="{{ addslashes($car->brand) }}" data-model="{{ addslashes($car->model) }}" data-engine="{{ addslashes($car->engine) }}" data-price="{{ number_format($car->price_per_day, 2) }}" data-description="{{ addslashes($car->description) }}" data-branch="{{ addslashes($car->branch) }}" onclick="showModal(event)">
                        <img loading="lazy" class="object-cover w-full h-full" src="{{ $car->images->first()->image_path }}" alt="product image" />
                        <span class="hover-message">Click to view images</span>
                    </a>
                @else
                    <a class="relative mx-3 mt-3 flex h-60 overflow-hidden rounded-xl" href="#" data-images="[]" data-brand="{{ addslashes($car->brand) }}" data-model="{{ addslashes($car->model) }}" data-engine="{{ addslashes($car->engine) }}" data-price="{{ number_format($car->price_per_day, 2) }}" data-description="{{ addslashes($car->description) }}" data-branch="{{ addslashes($car->branch) }}" onclick="showModal(event)">
                        <img loading="lazy" class="object-cover w-full h-full" src="/path/to/default/image.jpg" alt="product image" />
                        <span class="hover-message">Click to view images</span>
                    </a>
                @endif
                <div class="mt-4 px-5 pb-5">
                    <div>
                        <h5 class="font-arial-black text-xl tracking-tight text-slate-900">{{ $car->brand }} {{ $car->model }} {{ $car->engine }}</h5>
                        <p class="text-sm text-gray-700">Branch: {{ $car->branch }}</p>
                    </div>
                    <div class="mt-2 mb-5 flex items-center justify-between">
                        <p>
                            <span class="text-3xl font-bold text-black">₱{{ number_format($car->price_per_day, 2) }}</span>
                        </p>
                    </div>
                    <div class="flex justify-between">
                        <a href="{{ route('car.reservation', ['car' => $car->id]) }}" class="reserve-button flex items-center justify-center rounded-md bg-custom px-5 py-2.5 text-center text-sm font-medium text-white focus:outline-none focus:ring-4 focus:ring-blue-300">
                            Rent Now
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div id="confirmationModal">
        <div id="confirmationModalContent">
            <span class="close" onclick="hideConfirmationModal()">&times;</span>
            <h2>Notice: Important Information Before Renting</h2>
            <p>Please be aware of the following before proceeding with your reservation:</p>
            <ul>
                <li><strong>Booking Confirmation:</strong> Ensure that all details provided are accurate. Once you proceed, the reservation will be confirmed, and any changes may be subject to cancellation fees.</li>
                <li><strong>Identification:</strong> Valid identification and a driver’s license (if applicable) will be required at the time of pickup.</li>
                <li><strong>Deposit:</strong> A security deposit may be required, which will be refunded upon the return of the vehicle in good condition.</li>
                <li><strong>Insurance:</strong> Verify that you have adequate insurance coverage or opt-in for additional insurance options provided.</li>
                <li><strong>Terms and Conditions:</strong> Familiarize yourself with our rental terms and conditions to avoid any misunderstandings.</li>
            </ul>
            <p>Proceeding with this reservation signifies your acceptance of these terms and conditions. If you have any questions or need assistance, please contact our support team.</p>
            <div class="modal-buttons">
                <button id="confirmYes" class="btn-confirm">Yes</button>
                <button id="confirmNo" class="btn-cancel" onclick="hideConfirmationModal()">No</button>
            </div>
        </div>
    </div>

    <div id="imageModal">
        <div id="imageModalContent">
            <span class="close" onclick="hideModal()">&times;</span>
            <div class="carousel-container">
                <button class="carousel-control prev" onclick="prevImage()">&#10094;</button>
                <div class="carousel-images" id="carouselImages"></div>
                <button class="carousel-control next" onclick="nextImage()">&#10095;</button>
            </div>
            <div id="modalDescription" class="modal-description"></div>
        </div>
    </div>
</div>

<script>
    let currentImageIndex = 0;
    let images = [];

    function showModal(event) {
        event.preventDefault();
        const target = event.currentTarget;
        images = JSON.parse(target.getAttribute('data-images').replace(/&quot;/g,'"'));
        const brand = target.getAttribute('data-brand');
        const model = target.getAttribute('data-model');
        const engine = target.getAttribute('data-engine');
        const price = target.getAttribute('data-price');
        const description = target.getAttribute('data-description');
        const branch = target.getAttribute('data-branch');

        currentImageIndex = 0;
        updateModalImage();

        document.getElementById('modalDescription').innerHTML = `
            <p><strong>Brand:</strong> ${brand}</p>
            <p><strong>Model:</strong> ${model}</p>
            <p><strong>Engine:</strong> ${engine}</p>
            <p><strong>Price:</strong> ₱${price}</p>
            <p><strong>Description:</strong> ${description}</p>
            <p><strong>Branch:</strong> ${branch}</p>
        `;
        document.getElementById('imageModal').style.display = 'flex';
    }

    function hideModal() {
        document.getElementById('imageModal').style.display = 'none';
    }

    function hideConfirmationModal() {
        document.getElementById('confirmationModal').style.display = 'none';
    }

    function updateModalImage() {
        const carouselImages = document.getElementById('carouselImages');
        carouselImages.innerHTML = '';
        if (images.length > 0) {
            const imgElement = document.createElement('img');
            imgElement.src = images[currentImageIndex];
            imgElement.alt = `Car Image ${currentImageIndex + 1}`;
            carouselImages.appendChild(imgElement);
        }
    }

    function nextImage() {
        if (currentImageIndex < images.length - 1) {
            currentImageIndex++;
            updateModalImage();
        }
    }

    function prevImage() {
        if (currentImageIndex > 0) {
            currentImageIndex--;
            updateModalImage();
        }
    }

    document.getElementById('searchInput').addEventListener('input', filterCars);
    document.getElementById('priceRange').addEventListener('change', filterCars);
    document.getElementById('carBranch').addEventListener('change', filterCars);

    function filterCars() {
        const searchInput = document.getElementById('searchInput').value.toLowerCase();
        const priceRange = document.getElementById('priceRange').value;
        const carBranch = document.getElementById('carBranch').value;

        document.querySelectorAll('.car-row').forEach(car => {
            const brand = car.getAttribute('data-brand').toLowerCase();
            const branch = car.getAttribute('data-branch');
            const price = parseInt(car.getAttribute('data-price'));

            let priceMin = 0;
            let priceMax = Infinity;

            if (priceRange) {
                const [min, max] = priceRange.split('-').map(Number);
                priceMin = min;
                priceMax = max;
            }

            const matchesSearch = !searchInput || brand.includes(searchInput);
            const matchesPrice = price >= priceMin && price <= priceMax;
            const matchesBranch = !carBranch || branch === carBranch;

            if (matchesSearch && matchesPrice && matchesBranch) {
                car.style.display = 'block';
            } else {
                car.style.display = 'none';
            }
        });
    }

    document.querySelectorAll('.reserve-button').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const confirmationModal = document.getElementById('confirmationModal');
            confirmationModal.style.display = 'flex';
            document.getElementById('confirmYes').onclick = () => {
                window.location.href = button.href;
            };
            document.getElementById('confirmNo').onclick = hideConfirmationModal;
        });
    });
</script>

@endsection
