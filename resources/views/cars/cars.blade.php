    @extends('layouts.myapp')

    @section('content')
    <style>
        .font-arial-black {
            font-family: 'Century Gothic', sans-serif;
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
            font-family: 'Century Gothic', sans-serif;
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
            font-family: 'Century Gothic', sans-serif;
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
        /* Ensure the font is included, using a web-safe stack if needed */
        @import url('https://fonts.googleapis.com/css2?family=Century+Gothic:wght@400;700&display=swap');

        /* Modal Styles */
        #confirmationModal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.8);
        }

        #confirmationModalContent {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            border-radius: 8px;
            width: 80%;
            max-width: 600px;
            font-family: 'Century Gothic', sans-serif;
        }

        .modal-buttons {
            margin-top: 20px;
            text-align: center;
        }

        .btn-confirm, .btn-cancel {
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 5px;
        }

        .btn-confirm {
            background-color: #007BFF;
            color: #fff;
        }

        .btn-confirm:hover {
            background-color: #0056b3;
        }

        .btn-cancel {
            background-color: #ccc;
            color: #333;
        }

        .btn-cancel:hover {
            background-color: #999;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }

        h2 {
            font-size: 1.5em;
            margin-bottom: 15px;
            color: #333;
        }

        p {
            font-size: 1em;
            color: #666;
        }

        ul {
            list-style-type: disc;
            margin-left: 20px;
            color: #555;
        }

        li {
            margin-bottom: 10px;
        }
    </style>




<!-- car section -->
<!-- car section -->
<div class="mt-6 mb-2 grid md:grid-cols-3 gap-4 justify-center items-center mx-auto max-w-screen-xl" id="carList">
    @forelse($cars as $car)
        <div class="relative flex flex-col overflow-hidden rounded-lg border border-orange-100 bg-orange-50 shadow-md car-container car-row"
            data-brand="{{ $car->brand }}" 
            data-branch="{{ $car->branch }}"
            data-price="{{ $car->price_per_day }}">
            
            @if ($car->images->isNotEmpty() || $car->videos->isNotEmpty())
                <a class="relative mx-3 mt-3 flex h-80 overflow-hidden rounded-xl" href="#" 
                data-images="{{ $car->images->pluck('image_path') }}" 
                data-videos="{{ $car->videos->pluck('path') }}" 
                data-brand="{{ addslashes($car->brand) }}" 
                data-model="{{ addslashes($car->model) }}" 
                data-engine="{{ addslashes($car->engine) }}" 
                data-price="{{ number_format($car->price_per_day, 2) }}" 
                data-description="{{ addslashes($car->description) }}" 
                data-branch="{{ addslashes($car->branch) }}" 
                onclick="showModal(event)">
                    
                    @if ($car->videos->isNotEmpty())
                        <video class="object-cover w-full h-full" autoplay muted loop>
                            <source src="{{ asset($car->videos->first()->path) }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    @else
                        <img loading="lazy" class="object-cover w-full h-full" 
                            src="{{ asset($car->images->first()->image_path) }}" 
                            alt="Car image" />
                    @endif
                    
                    <span class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 text-white text-lg font-semibold opacity-0 hover:opacity-100 transition-opacity" style="font-family: 'Century Gothic', sans-serif;">
                        Click to view details
                    </span>

                </a>
            @else
                <a class="relative mx-3 mt-3 flex h-80 overflow-hidden rounded-xl" href="#" 
                data-images="[]" 
                data-videos="[]" 
                data-brand="{{ addslashes($car->brand) }}" 
                data-model="{{ addslashes($car->model) }}" 
                data-engine="{{ addslashes($car->engine) }}" 
                data-price="{{ number_format($car->price_per_day, 2) }}" 
                data-description="{{ addslashes($car->description) }}" 
                data-branch="{{ addslashes($car->branch) }}" 
                onclick="showModal(event)">
                    <img loading="lazy" class="object-cover w-full h-full" 
                        src="{{ asset('path/to/default/image.jpg') }}" 
                        alt="Default image" />
                    <span class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 text-white text-lg font-semibold opacity-0 hover:opacity-100 transition-opacity" style="font-family: 'Century Gothic', sans-serif;">
                        Click to view details
                    </span>

                </a>
            @endif
            
            <div class="mt-4 px-5 pb-5">
                <h5 class="font-semibold text-xl text-gray-800">{{ $car->brand }} {{ $car->model }} {{ $car->engine }}</h5>
                <p class="text-sm text-gray-600">Branch: {{ $car->branch }}</p>
                
                <div class="mt-2 mb-5 flex items-center justify-between">
                    <span class="text-3xl font-bold text-black">₱{{ number_format($car->price_per_day, 2) }}</span>
                </div>
                
                <div class="flex justify-between">
                <a href="{{ route('car.reservation', ['car' => $car->id]) }}" 
                   class="reserve-button flex items-center justify-center rounded-lg bg-green-600 px-6 py-3 text-base font-bold text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-400 shadow-lg transition-all duration-300 ease-in-out"
                   style="font-family: 'Century Gothic', sans-serif;">
                    Rent Now
                </a>

                </div>
                
                @auth
                <div class="mt-4">
                    <p class="text-sm font-semibold text-gray-700">Availability:</p>
                    @if ($car->reservations->isNotEmpty())
                        <div class="text-sm text-gray-600">
                            <p class="font-semibold">Reserved on:</p>
                            <ul class="list-disc list-inside">
                            @foreach ($car->reservations as $reservation)
                                <li>From {{ $reservation->start_date->format('M d, Y') }} to {{ $reservation->end_date->format('M d, Y') }}</li>
                            @endforeach
                            </ul>
                        </div>
                    @else
                        <p class="text-sm text-gray-600">This car is currently available.</p>
                    @endif
                </div>
                @endauth
                
                <!-- Display a badge if the car is currently reserved -->
                @auth
                    @if ($car->reservations()->where('end_date', '>=', now())->exists())
                        <div class="absolute top-0 right-0 mt-3 mr-3 bg-red-500 text-white text-sm font-semibold px-2 py-1 rounded-full badge">
                            Reserved
                        </div>
                    @endif
                @endauth

            </div>
        </div>
    @empty
        <p class="text-center text-gray-600">No cars available for the selected branch.</p>
    @endforelse
</div>




    <style>
        .availability-indicator {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
    }

    .available-date {
        background-color: #d4edda;
        color: #155724;
        border-radius: 3px;
        padding: 3px 8px;
        font-size: 0.875rem;
    }
        
    </style>

        <div id="confirmationModal">
            <div id="confirmationModalContent">
                <span class="close" onclick="hideConfirmationModal()">&times;</span>
                <h2>Notice: Important Information Before Renting</h2>
                <p>Please be aware of the following before proceeding with your reservation:</p>
                <ul>
                    <li><strong>Booking Confirmation:</strong> Ensure that all details provided are accurate. Once you proceed, the reservation will be confirmed, and any changes may be subject to cancellation fees.</li>
                    <li><strong>Identification:</strong> Valid identification and a driver’s license (if applicable) will be required at the time of pickup.</li>

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

    // Create a collage of images
    let collageImages = images.map((img, index) => 
        `<img src="${img}" alt="Car Image ${index + 1}" style="width: 100%; height: auto; object-fit: cover;" onclick="viewFitScreen('${img}')">`
    ).join('');

    // Update modal content
    const modalContent = `
        <div style="display: flex; flex-direction: row; align-items: flex-start;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; flex: 1;">
                ${collageImages}
            </div>
            <div style="max-width: 400px; padding-left: 20px; flex: 1;">
                <p><strong>Brand:</strong> ${brand}</p>
                <p><strong>Model:</strong> ${model}</p>
                <p><strong>Engine:</strong> ${engine}</p>
                <p><strong>Price:</strong> ₱${price}</p>
                <p><strong>Description:</strong> ${description}</p>
                <p><strong>Branch:</strong> ${branch}</p>
            </div>
        </div>
    `;

    document.getElementById('modalDescription').innerHTML = modalContent;
    document.getElementById('imageModal').style.display = 'flex';
}

function viewFitScreen(imageSrc) {
    const imgElement = document.createElement('img');
    imgElement.src = imageSrc;
    imgElement.style.maxWidth = '90%';
    imgElement.style.maxHeight = '90%';
    imgElement.style.objectFit = 'contain';
    imgElement.style.cursor = 'pointer';
    
    const fitScreenContainer = document.createElement('div');
    fitScreenContainer.style.position = 'fixed';
    fitScreenContainer.style.top = '0';
    fitScreenContainer.style.left = '0';
    fitScreenContainer.style.width = '100%';
    fitScreenContainer.style.height = '100%';
    fitScreenContainer.style.backgroundColor = 'rgba(0, 0, 0, 0.8)';
    fitScreenContainer.style.display = 'flex';
    fitScreenContainer.style.justifyContent = 'center';
    fitScreenContainer.style.alignItems = 'center';
    fitScreenContainer.style.zIndex = '10000';
    fitScreenContainer.appendChild(imgElement);

    document.body.appendChild(fitScreenContainer);

    // Click to close the view
    fitScreenContainer.addEventListener('click', () => {
        document.body.removeChild(fitScreenContainer);
    });
}

function hideModal() {
    document.getElementById('imageModal').style.display = 'none';
}

function hideConfirmationModal() {
    document.getElementById('confirmationModal').style.display = 'none';
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
