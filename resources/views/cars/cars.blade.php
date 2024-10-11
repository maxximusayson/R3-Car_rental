@extends('layouts.myapp')

@section('title', 'R3 Garage Car Rental | Cars')

@section('content')

@php
    // Get filters from query string
    $brand = request()->input('brand');
    $location = strtolower(request()->input('location')); // Convert to lowercase for case insensitivity

    // Logic to determine nearest branch based on location
    $nearestBranch = null;

    $marikinaBranches = ['marikina', 'marikina center', 'san roque', 'concepcion', 'pasay', 'makati', 'manila', 'quezon city', 'valenzuela', 'las pinas'];
    $isabelaBranches = ['isabela', 'santiago', 'cauayan'];

    // Determine the nearest branch based on the location input
    if (in_array($location, $marikinaBranches, true)) {
        $nearestBranch = 'Marikina';
    } elseif (in_array($location, $isabelaBranches, true)) {
        $nearestBranch = 'Isabela'; // Adjust as needed
    }

    // Filter cars based on the selected brand and nearest branch
    $filteredCars = $cars->filter(function($car) use ($nearestBranch, $brand) {
        return (!$nearestBranch || $car->branch === $nearestBranch) && (!$brand || $car->brand === $brand);
    });
@endphp


<!-- Filter Section -->
<div class="filter-container">
    <form method="GET" action="{{ route('cars') }}">
        <div class="filter-row">
            <!-- Location Filter -->
            <div class="filter-column">
                <label for="location" class="filter-label">Location</label>
                <div class="input-group">
                    <span class="input-icon"><i class="fas fa-map-marker-alt"></i></span>
                    <select id="location" name="location" class="filter-select">
                        <option value="">All Locations</option>
                        <option value="marikina" {{ $location === 'marikina' ? 'selected' : '' }}>Marikina</option>
                        <option value="isabela" {{ $location === 'isabela' ? 'selected' : '' }}>Isabela</option>
                    </select>
                </div>
            </div>

            <!-- Car Brand Filter -->
            <div class="filter-column">
                <label for="brand" class="filter-label">Brand</label>
                <div class="input-group">
                    <span class="input-icon"><i class="fas fa-car"></i></span>
                    <select id="brand" name="brand" class="filter-select">
                        <option value="">All Brands</option>
                        <option value="TOYOTA" {{ $brand === 'TOYOTA' ? 'selected' : '' }}>TOYOTA</option>
                        <option value="MITSUBISHI" {{ $brand === 'MITSUBISHI' ? 'selected' : '' }}>MITSUBISHI</option>
                        <option value="NISSAN" {{ $brand === 'NISSAN' ? 'selected' : '' }}>NISSAN</option>
                        <option value="MG" {{ $brand === 'MG' ? 'selected' : '' }}>MG</option>
                    </select>
                </div>
            </div>

            <!-- Filter and Clear Buttons -->
            <div class="filter-column filter-buttons">
                <button type="submit" class="btn-filter">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <a href="{{ route('cars') }}" class="btn-clear">
                    <i class="fas fa-undo"></i> Clear
                </a>
            </div>
        </div>
    </form>
</div>

<!-- FontAwesome for icons -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>





<!-- Car Section -->
<div class="mt-6 mb-2 grid md:grid-cols-3 gap-6 justify-center items-center mx-auto max-w-screen-xl px-4" id="carList">
    @forelse($filteredCars as $car)
        <div class="relative flex flex-col overflow-hidden rounded-lg border border-gray-300 bg-white shadow-lg car-container car-row"
            data-brand="{{ $car->brand }}" 
            data-branch="{{ $car->branch }}"
            data-price="{{ $car->price_per_day }}">

            <!-- Image Section -->
            <div class="p-0 rounded-lg shadow-md">
                @if ($car->images->count() > 0)
                    <a class="relative overflow-hidden rounded-t-lg" href="#" 
                        data-images="{{ $car->images->pluck('image_path') }}" 
                        data-brand="{{ addslashes($car->brand) }}" 
                        data-model="{{ addslashes($car->model) }}" 
                        data-engine="{{ addslashes($car->engine) }}" 
                        data-price="{{ number_format($car->price_per_day, 2) }}" 
                        data-description="{{ addslashes($car->description) }}" 
                        data-branch="{{ addslashes($car->branch) }}"
                        data-video-path="{{ addslashes($car->video_path) }}"
                        onclick="showModal(event)">
                        
                        <img loading="lazy" class="object-cover w-full h-48 rounded-t-lg" 
                            src="{{ asset($car->images->first()->image_path) }}" 
                            alt="Car image" />

                        <span class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 text-white text-lg font-semibold opacity-0 hover:opacity-100 transition-opacity" style="font-family: 'Century Gothic', sans-serif;">
                            Click to view details
                        </span>
                    </a>
                @else
                    <a class="relative overflow-hidden rounded-t-lg" href="#" 
                        data-images="[]" 
                        data-brand="{{ addslashes($car->brand) }}" 
                        data-model="{{ addslashes($car->model) }}" 
                        data-engine="{{ addslashes($car->engine) }}" 
                        data-price="{{ number_format($car->price_per_day, 2) }}" 
                        data-description="{{ addslashes($car->description) }}" 
                        data-branch="{{ addslashes($car->branch) }}"
                        data-video-path="{{ addslashes($car->video_path) }}"
                        onclick="showModal(event)">
                        <img loading="lazy" class="object-cover w-full h-48 rounded-t-lg" 
                            src="{{ asset('path/to/default/image.jpg') }}" 
                            alt="Default image" />
                    </a>
                @endif
            </div>

            <!-- Car Details Section -->
            <div class="mt-4 px-5 pb-5">
                <h5 class="font-semibold text-xl text-gray-800">{{ $car->brand }} {{ $car->model }}</h5>
                <p class="text-sm text-gray-600">Branch: {{ $car->branch }}</p>
                <span class="text-lg font-bold text-black">₱{{ number_format($car->price_per_day, 2) }}</span>

                <div class="flex flex-col space-y-3 mt-4">
                    <div class="flex justify-between space-x-3">
                        <a href="{{ route('car.reservation', ['car' => $car->id]) }}" 
                           class="reserve-button flex-grow flex items-center justify-center rounded-lg bg-green-600 px-6 py-3 text-base font-bold text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-400 shadow-lg transition-all duration-300 ease-in-out"
                           style="font-family: 'Century Gothic', sans-serif;">
                            Rent Now
                        </a>

                        <!-- Rate Button -->
                        @auth
                            <button 
                                onclick="showRatingModal({{ $car->id }})"
                                class="rate-button flex-grow flex items-center justify-center rounded-lg bg-yellow-500 px-6 py-3 text-base font-bold text-white hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-400 shadow-lg transition-all duration-300 ease-in-out"
                                style="font-family: 'Century Gothic', sans-serif;">
                                Rate this Car
                            </button>
                        @else
                            <a href="{{ route('login') }}" 
                               class="rate-button flex-grow flex items-center justify-center rounded-lg bg-yellow-500 px-6 py-3 text-base font-bold text-white hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-400 shadow-lg transition-all duration-300 ease-in-out"
                               style="font-family: 'Century Gothic', sans-serif;">
                                Rate this Car
                            </a>
                        @endauth
                    </div>

                    <!-- See Reviews Button -->
                    <div class="flex justify-center">
                        <button onclick="showReviewsModal({{ $car->id }})" 
                                class="see-reviews-button flex-grow max-w-xs flex items-center justify-center rounded-lg bg-blue-500 text-white px-6 py-3 text-base font-bold hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 shadow-lg transition-all duration-300 ease-in-out"
                                style="font-family: 'Century Gothic', sans-serif;">
                            See Reviews
                        </button>
                    </div>
                </div>

               <!-- Availability Section -->
<div class="mt-4">
    <p class="text-sm font-semibold text-gray-700">Availability:</p>
    @if ($car->reservations->count() > 0)
        <div class="text-sm text-gray-600">
            <p class="font-semibold">Reserved on:</p>
            <ul class="list-disc list-inside">
                @foreach ($car->reservations->take(1) as $reservation)
                    <li>From {{ $reservation->start_date->format('M d, Y') }} to {{ $reservation->end_date->format('M d, Y') }}</li>
                @endforeach
            </ul>
            @if ($car->reservations->count() > 2)
                <button id="seeMoreBtn" class="text-blue-500 hover:underline text-sm mt-2">See more</button>
            @endif
        </div>
    @else
        <p class="text-sm text-gray-600">This car is currently available.</p>
    @endif
</div>

<!-- Modal (hidden by default) -->
<div id="reservationModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center hidden z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg w-1/2 max-h-full overflow-y-auto">
        <h2 class="text-lg font-semibold mb-4">All Reservations</h2>
        <ul class="list-disc list-inside">
            @foreach ($car->reservations as $reservation)
                <li>From {{ $reservation->start_date->format('M d, Y') }} to {{ $reservation->end_date->format('M d, Y') }}</li>
            @endforeach
        </ul>
        <button id="closeModalBtn" class="mt-4 bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">Close</button>
    </div>
</div>

<!-- JavaScript to handle modal -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const seeMoreBtn = document.getElementById('seeMoreBtn');
        const reservationModal = document.getElementById('reservationModal');
        const closeModalBtn = document.getElementById('closeModalBtn');
        
        if (seeMoreBtn) {
            seeMoreBtn.addEventListener('click', function() {
                reservationModal.classList.remove('hidden');  // Show the modal
            });
        }

        if (closeModalBtn) {
            closeModalBtn.addEventListener('click', function() {
                reservationModal.classList.add('hidden');  // Hide the modal when "Close" is clicked
            });
        }

        // Optional: Close modal if user clicks outside of the modal content
        reservationModal.addEventListener('click', function(event) {
            if (event.target === reservationModal) {
                reservationModal.classList.add('hidden');
            }
        });
    });
</script>


                <!-- Reviews Modal (Initially Hidden) -->
                <div id="reviewsModal-{{ $car->id }}" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 hidden">
                    <div class="bg-white rounded-lg p-6 shadow-lg w-full max-w-lg relative">
                        <button onclick="closeReviewsModal({{ $car->id }})" class="absolute top-2 right-2 text-gray-600 hover:text-black">
                            &times;
                        </button>
                        <h2 class="text-xl font-semibold mb-4">Reviews for {{ $car->brand }} {{ $car->model }}</h2>
                        <div id="ratingListModal-{{ $car->id }}" class="mt-4 max-h-96 overflow-y-auto">
                            @if($car->ratings->count() > 0)
                                @foreach($car->ratings as $rating)
                                    <div id="rating-{{ $rating->id }}" class="p-4 border-t flex justify-between items-center" data-rating="{{ $rating->rating }}">
                                        <div>
                                            <p class="font-bold" id="rating-{{ $rating->id }}-stars" data-rating="{{ $rating->rating }}">{{ str_repeat('⭐', $rating->rating) }}</p>
                                            <p id="comment-{{ $rating->id }}">{{ $rating->comment }}</p>
                                            @auth
                                                @if($rating->user_id == auth()->id())
                                                    <p class="text-sm text-gray-500" id="user-{{ $rating->id }}">Rated by: {{ $rating->user->name }}</p>
                                                @endif
                                            @endauth
                                        </div>
                                        @auth
                                            @if($rating->user_id == auth()->id())
                                                <div class="text-sm text-gray-500 flex space-x-2">
                                                    <button onclick="editRating({{ $car->id }}, {{ $rating->id }})" class="text-blue-500 hover:underline">Edit</button>
                                                    <button onclick="deleteRating({{ $car->id }}, {{ $rating->id }})" class="text-red-500 hover:underline">Delete</button>
                                                </div>
                                            @endif
                                        @endauth
                                    </div>
                                @endforeach
                            @else
                                <p class="text-gray-600">No ratings available for this car.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <p class="text-center text-gray-600">No cars available for the selected branch.</p>
    @endforelse
</div>





<!-- Rate Modal -->
<div id="rateModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 hidden">
    <div class="bg-white rounded-lg p-6 shadow-lg w-full max-w-md">
        <h2 id="rateModalTitle" class="text-xl font-semibold mb-4">Rate and Review</h2>
        <form id="rateForm" onsubmit="submitRating(event)">
            <div class="mb-4">
                <label for="rating" class="block text-gray-700 font-bold mb-2">Your Rating:</label>
                <div class="flex items-center">
                    <span id="star1" class="star cursor-pointer text-gray-400" onclick="setRating(1)">&#9733;</span>
                    <span id="star2" class="star cursor-pointer text-gray-400" onclick="setRating(2)">&#9733;</span>
                    <span id="star3" class="star cursor-pointer text-gray-400" onclick="setRating(3)">&#9733;</span>
                    <span id="star4" class="star cursor-pointer text-gray-400" onclick="setRating(4)">&#9733;</span>
                    <span id="star5" class="star cursor-pointer text-gray-400" onclick="setRating(5)">&#9733;</span>
                </div>
                <input type="hidden" id="rating" name="rating" value="0">
            </div>
            <div class="mb-4">
                <label for="comment" class="block text-gray-700 font-bold mb-2">Your Comment:</label>
                <textarea id="comment" name="comment" rows="4" class="w-full p-2 border rounded-lg focus:outline-none focus:border-green-500"></textarea>
            </div>
            <div class="flex justify-end">
                <button type="button" onclick="closeRateModal()" class="bg-gray-500 text-white px-4 py-2 rounded-lg mr-2">Cancel</button>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg">Submit</button>
            </div>
        </form>
    </div>
</div>


<script>
let isEditMode = false;
let currentEditId = null;

// List of sensitive words
const sensitiveWords = ["nigga", "fuck", "shit", "bitch", "cunt", "asshole", "faggot", "whore", "slut", "dick", "gago","putang ina", "bobo", "tanga", "pussy", "angtangamo","tangina",];

// Function to check if a comment contains sensitive words
function containsSensitiveWords(comment) {
    return sensitiveWords.some(word => comment.toLowerCase().includes(word.toLowerCase()));
}

// Function to open the rate modal
function showRatingModal(carId, ratingId = null) {
    isEditMode = ratingId !== null;
    currentEditId = ratingId;

    document.getElementById('rateModalTitle').innerText = isEditMode ? 'Edit Your Review' : 'Rate and Review';

    if (isEditMode) {
        const ratingElement = document.getElementById(`rating-${ratingId}-stars`);
        const commentElement = document.getElementById(`comment-${ratingId}`);

        if (ratingElement && commentElement) {
            const rating = ratingElement.getAttribute('data-rating');
            const comment = commentElement.innerText;

            setRating(parseInt(rating));
            document.getElementById('comment').value = comment;
        }
    } else {
        setRating(0);
        document.getElementById('comment').value = '';
    }

    document.getElementById('rateModal').style.display = 'flex';
    document.getElementById('rateForm').setAttribute('data-car-id', carId);
}

// Function to close the rate modal
function closeRateModal() {
    document.getElementById('rateModal').style.display = 'none';
}

// Function to handle star rating
function setRating(rating) {
    document.getElementById('rating').value = rating;
    for (let i = 1; i <= 5; i++) {
        document.getElementById('star' + i).style.color = i <= rating ? 'gold' : 'gray';
    }
}

// Function to handle the rating form submission
function submitRating(event) {
    event.preventDefault();
    const carId = document.getElementById('rateForm').getAttribute('data-car-id');
    const rating = document.getElementById('rating').value;
    const comment = document.getElementById('comment').value;

    // Check if the comment contains sensitive words
    if (containsSensitiveWords(comment)) {
        alert('Your comment contains inappropriate language and cannot be submitted.');
        return; // Stop the submission
    }

    const url = isEditMode ? `/ratings/${currentEditId}` : '/ratings';
    const method = isEditMode ? 'PUT' : 'POST';

    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: JSON.stringify({
            car_id: carId,
            rating: rating,
            comment: comment,
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(result => {
        if (isEditMode) {
            document.getElementById(`rating-${currentEditId}`).innerHTML = `
                <div>
                    <p class="font-bold" id="rating-${currentEditId}-stars" data-rating="${result.rating}">${'⭐'.repeat(result.rating)}</p>
                    <p id="comment-${currentEditId}">${result.comment}</p>
                </div>
                <div class="text-sm text-gray-500 flex space-x-2">
                    <button onclick="editRating(${carId}, ${currentEditId})" class="text-blue-500 hover:underline">Edit</button>
                    <button onclick="deleteRating(${carId}, ${currentEditId})" class="text-red-500 hover:underline">Delete</button>
                </div>
            `;
        } else {
            addRatingToList(carId, result.rating, result.comment, result.id);
        }
        closeRateModal();
    })
    .catch(error => console.error('Error during submission:', error));
}

// Function to add the new or edited rating to the list
function addRatingToList(carId, rating, comment, ratingId) {
    const ratingList = document.getElementById('ratingListModal-' + carId);
    if (ratingList) {
        const newRating = document.createElement('div');
        newRating.className = 'p-4 border-t flex justify-between items-center';
        newRating.setAttribute('id', `rating-${ratingId}`);
        newRating.setAttribute('data-rating', rating);

        newRating.innerHTML = `
            <div>
                <p class="font-bold" id="rating-${ratingId}-stars" data-rating="${rating}">${'⭐'.repeat(rating)}</p>
                <p id="comment-${ratingId}">${comment}</p>
            </div>
            <div class="text-sm text-gray-500 flex space-x-2">
                <button onclick="editRating(${carId}, ${ratingId})" class="text-blue-500 hover:underline">Edit</button>
                <button onclick="deleteRating(${carId}, ${ratingId})" class="text-red-500 hover:underline">Delete</button>
            </div>
        `;
        ratingList.appendChild(newRating);
    }
}

// Function to edit a rating
function editRating(carId, ratingId) {
    showRatingModal(carId, ratingId);
}

// Function to delete a rating
function deleteRating(carId, ratingId) {
    fetch(`/ratings/${ratingId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(result => {
        if (result.success) {
            const ratingElement = document.getElementById(`rating-${ratingId}`);
            if (ratingElement) {
                ratingElement.remove();
            }
        } else {
            console.error('Failed to delete rating:', result.message); // Error log
        }
    })
    .catch(error => console.error('Error during deletion:', error));
}

// Function to show the reviews modal
function showReviewsModal(carId) {
    const modal = document.getElementById('reviewsModal-' + carId);
    if (modal) {
        modal.classList.remove('hidden');
    }
}

// Function to close the reviews modal
function closeReviewsModal(carId) {
    const modal = document.getElementById('reviewsModal-' + carId);
    if (modal) {
        modal.classList.add('hidden');
    }
}
</script>








<!-- Confirmation Modal -->
<div id="confirmationModal" class="flex">
    <div id="confirmationModalContent" class="bg-white rounded-lg shadow-lg p-6 max-w-lg mx-auto">
        <span class="close text-black text-xl cursor-pointer" onclick="hideConfirmationModal()">&times;</span>
        <h2 class="text-xl font-semibold mb-4">Notice: Important Information Before Renting</h2>
        <p class="mb-4">Please be aware of the following before proceeding with your reservation:</p>
        <ul class="list-disc list-inside mb-4">
            <li><strong>Booking Confirmation:</strong> Ensure that all details provided are accurate. Once you proceed, the reservation will be confirmed, and any changes may be subject to cancellation fees.</li>
            <li><strong>Identification:</strong> Valid identification and a driver’s license (if applicable) will be required at the time of pickup.</li>
            <li><strong>Insurance:</strong> Verify that you have adequate insurance coverage or opt-in for additional insurance options provided.</li>
            <li><strong>Terms and Conditions:</strong> Familiarize yourself with our rental terms and conditions to avoid any misunderstandings.</li>
        </ul>
        <p class="mb-4">Proceeding with this reservation signifies your acceptance of these terms and conditions. If you have any questions or need assistance, please contact our support team.</p>
        <div class="flex justify-end space-x-4">
            <button id="confirmYes" class="btn-confirm bg-green-600 text-white px-4 py-2 rounded-lg">Yes</button>
            <button id="confirmNo" class="btn-cancel bg-red-600 text-white px-4 py-2 rounded-lg" onclick="hideConfirmationModal()">No</button>
        </div>
    </div>
</div>

<!-- JavaScript for Modal behavior -->
<script>
    // Function to show the modal
    function showConfirmationModal() {
        document.getElementById('confirmationModal').style.display = 'flex';
    }

    // Function to hide the modal
    function hideConfirmationModal() {
        document.getElementById('confirmationModal').style.display = 'none';
    }

   

    // Open the modal when needed
    // You can trigger the modal to open by calling `showConfirmationModal()`
</script>

<!-- Image Modal -->
<div id="imageModal" style="display: none;">
    <div id="imageModalContent" class="bg-white rounded-lg shadow-lg p-6 max-w-3xl mx-auto">
        <span class="close text-black text-xl cursor-pointer" onclick="hideModal()">&times;</span>
        <div id="modalDescription" class="modal-description"></div>
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
        const videoPath = target.getAttribute('data-video-path');

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
                    ${videoPath ? `
                    <!-- Include video playback here if exists -->
                    <div class="mt-4 rounded-lg overflow-hidden">
                        <video controls class="w-full rounded-lg">
                            <source src="${videoPath}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>` : ''}
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

    #imageModal, #confirmationModal {
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

    #imageModalContent, #confirmationModalContent {
        position: relative;
        margin: auto;
        padding: 20px;
        width: 80%;
        max-width: 700px;
        background: white;
        border-radius: 8px;
        text-align: justify;
        font-family: 'Century Gothic', sans-serif;
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
    .flex-container form select,
    .search-bar input,
    .search-bar select {
        margin: 0 10px;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
        font-family: 'Century Gothic', sans-serif;
    }

    .search-bar {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 20px;
        padding: 10px;
        background-color: #f8f9fa;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .search-bar select,
    .search-bar input {
        flex-grow: 1;
        max-width: 300px;
    }

    .search-select {
        appearance: none;
        background-image: url('data:image/svg+xml;charset=US-ASCII,<svg xmlns="http://www.w3.org/2000/svg" width="8" height="8" fill="none" stroke="%23495057" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><path d="M1 1l4 4-4 4"/></svg>');
        background-repeat: no-repeat;
        background-position: right 10px center;
        background-size: 12px;
    }

    .search-input::placeholder {
        color: #6c757d;
        opacity: 0.7;
    }

    .search-bar select:focus,
    .search-bar input:focus {
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        outline: none;
    }

    .search-bar select:hover,
    .search-bar input:hover {
        border-color: #007bff;
    }

    .search-bar input[type="text"] {
        max-width: 400px;
    }

    #reviewsModal .bg-white {
        max-width: 600px;
        max-height: 80vh;
        overflow-y: auto;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    }

    #reviewsModal .bg-white button.close {
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 1.5rem;
        background: transparent;
        border: none;
        color: #333;
        cursor: pointer;
    }

    #reviewsModal .bg-white button.close:hover {
        color: #000;
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
    .banner {
    background-size: cover;
    background-position: center;
    position: relative;
}

.banner h1, .banner p {
    color: white;
}

.banner h1 {
    font-size: 4rem;
    font-family: 'Century Gothic', sans-serif;
}

.banner p {
    font-size: 1.25rem;
    font-family: 'Century Gothic', sans-serif;
    margin-top: 1rem;
}
/* Add this to your existing CSS */

.scroll-effect {
    transition: transform 0.3s ease;
}

.scroll-up {
    transform: translateY(0); /* Show the element */
}

.scroll-down {
    transform: translateY(-20px); /* Hide the element slightly */
}
    
</style>

<!-- Styles -->
<style>
   /* Base Styles */
.filter-container {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
    background-color: #f8f9fc;
    border-radius: 10px;
    box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
    max-width: 1200px;
    margin: 20px auto; /* Center the container with vertical spacing */
}

.filter-row {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    width: 100%;
    justify-content: space-between; /* Distribute space evenly */
}

.filter-column {
    flex: 1;
    min-width: 200px;
    display: flex;
    flex-direction: column;
}

.filter-label {
    font-weight: bold;
    color: #4e73df;
    margin-bottom: 10px;
    font-size: 1rem;
}

.input-group {
    position: relative;
    width: 100%;
}

.input-icon {
    position: absolute;
    left: 10px;
    top: 50%;
    transform: translateY(-50%);
    background-color: #4e73df;
    color: white;
    padding: 8px;
    border-radius: 5px 0 0 5px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.filter-select {
    width: 100%;
    padding: 10px 10px 10px 35px; /* Padding left to accommodate the icon */
    border: 1px solid #4e73df;
    border-radius: 0 5px 5px 0;
    font-size: 1rem;
    appearance: none; /* Remove default arrow */
    background-color: #fff;
    /* Optional: Add a custom dropdown arrow */
    background-image: url('data:image/svg+xml;base64,PHN2ZyB...'); /* Replace with a valid SVG or remove if not needed */
    background-repeat: no-repeat;
    background-position: right 10px center;
    background-size: 12px;
}

/* Buttons */
.filter-buttons {
    display: flex;
    gap: 10px;
}

.btn-filter {
    background-color: #4e73df;
    color: white;
    padding: 12px 20px;
    border-radius: 5px;
    border: none;
    cursor: pointer;
    font-size: 1rem;
    display: flex;
    align-items: center;
    gap: 5px;
    transition: opacity 0.3s ease;
}

.btn-clear {
    background-color: transparent;
    color: #4e73df;
    border: 2px solid #4e73df;
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 1rem;
    display: flex;
    align-items: center;
    gap: 5px;
    transition: opacity 0.3s ease;
}

.btn-filter:hover,
.btn-clear:hover {
    opacity: 0.8;
}

/* Responsive Styles */

/* Tablets and smaller screens */
@media (max-width: 768px) {
    .filter-row {
        flex-direction: column;
        align-items: stretch;
    }

    .filter-column {
        align-items: stretch;
    }

    .filter-buttons {
        flex-direction: column;
    }

    .btn-filter,
    .btn-clear {
        width: 100%;
        justify-content: center;
    }
}

/* Mobile phones */
@media (max-width: 480px) {
    .filter-container {
        padding: 10px;
    }

    .filter-select {
        padding: 8px 8px 8px 30px; /* Adjust padding for smaller screens */
        font-size: 0.9rem;
    }

    .btn-filter,
    .btn-clear {
        padding: 8px 16px;
        font-size: 0.9rem;
    }

    .filter-label {
        font-size: 0.9rem;
    }

    .input-icon {
        padding: 6px;
    }
}
  /* Background overlay */
  #confirmationModal {
      display: none; /* Hidden by default */
      position: fixed;
      z-index: 50;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5); /* Black background with transparency */
      justify-content: center;
      align-items: center;
  }

  /* Modal content */
  #confirmationModalContent {
      background-color: white;
      border-radius: 10px;
      padding: 20px;
      max-width: 600px;
      width: 90%;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      animation: fadeIn 0.3s ease-out; /* Animation for a smooth appearance */
  }

  /* Close button */
  .close {
      float: right;
      font-size: 1.5rem;
      cursor: pointer;
  }

  /* Simple fade-in effect */
  @keyframes fadeIn {
      from {
          opacity: 0;
          transform: translateY(-10%);
      }
      to {
          opacity: 1;
          transform: translateY(0);
      }
  }

  /* Buttons */
  .btn-confirm, .btn-cancel {
      padding: 10px 20px;
      border-radius: 5px;
      cursor: pointer;
  }

  .btn-confirm:hover {
      background-color: #4caf50;
  }

  .btn-cancel:hover {
      background-color: #d32f2f;
  }

</style>
@endsection

