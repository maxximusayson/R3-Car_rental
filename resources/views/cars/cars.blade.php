@extends('layouts.myapp')

@section('content')

<!-- Filter Controls -->
<div class="search-bar">
    <select id="carBranch" class="search-select">
        <option value="">Select Branch</option>
        <option value="marikina">Marikina</option>
        <option value="isabela">Isabela</option>
        <!-- Add more branches as needed -->
    </select>

    <select id="carBrand" class="search-select">
        <option value="">Select Brand</option>
        <option value="toyota">Toyota</option>
        <option value="mitsubishi">Mitsubishi</option>
        <option value="nissan">Nissan</option>
        <option value="mg">MG</option>
        <!-- Add more brands as needed -->
    </select>

    <input type="text" id="searchInput" class="search-input" placeholder="Search by model...">
</div>


<!-- Car Section -->
<div class="mt-6 mb-2 grid md:grid-cols-3 gap-4 justify-center items-center mx-auto max-w-screen-xl" id="carList">
    @forelse($cars as $car)
        <div class="relative flex flex-col overflow-hidden rounded-lg border border-orange-100 bg-orange-50 shadow-md car-container car-row"
            data-brand="{{ $car->brand }}" 
            data-branch="{{ $car->branch }}"
            data-price="{{ $car->price_per_day }}">

            <!-- Folder-like view for images -->
            <div class="p-0 bg-white rounded-lg shadow-md">
                <div class="relative grid grid-cols-1 gap-0">
                    <!-- Image Section -->
                    @if ($car->images->count() > 0)
                        <a class="relative overflow-hidden rounded-xl" href="#" 
                            data-images="{{ $car->images->pluck('image_path') }}" 
                            data-brand="{{ addslashes($car->brand) }}" 
                            data-model="{{ addslashes($car->model) }}" 
                            data-engine="{{ addslashes($car->engine) }}" 
                            data-price="{{ number_format($car->price_per_day, 2) }}" 
                            data-description="{{ addslashes($car->description) }}" 
                            data-branch="{{ addslashes($car->branch) }}"
                            data-video-path="{{ addslashes($car->video_path) }}"
                            onclick="showModal(event)">
                            
                            <img loading="lazy" class="object-cover w-full h-full rounded-lg" 
                                src="{{ asset($car->images->first()->image_path) }}" 
                                alt="Car image" style="width: 100%; height: auto;" />

                            <span class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 text-white text-lg font-semibold opacity-0 hover:opacity-100 transition-opacity" style="font-family: 'Century Gothic', sans-serif;">
                                Click to view details
                            </span>
                        </a>
                    @else
                        <a class="relative overflow-hidden rounded-xl" href="#" 
                            data-images="[]" 
                            data-brand="{{ addslashes($car->brand) }}" 
                            data-model="{{ addslashes($car->model) }}" 
                            data-engine="{{ addslashes($car->engine) }}" 
                            data-price="{{ number_format($car->price_per_day, 2) }}" 
                            data-description="{{ addslashes($car->description) }}" 
                            data-branch="{{ addslashes($car->branch) }}"
                            data-video-path="{{ addslashes($car->video_path) }}"
                            onclick="showModal(event)">
                            <img loading="lazy" class="object-cover w-full h-full rounded-lg" 
                                src="{{ asset('path/to/default/image.jpg') }}" 
                                alt="Default image" style="width: 100%; height: auto;" />
                        </a>
                    @endif

                    <!-- Display a badge if the car is currently reserved -->
                    @if ($car->reservations()->where('start_date', '<=', now())->where('end_date', '>=', now())->count() > 0)
                        <div class="absolute top-0 right-0 mt-3 mr-3 bg-red-500 text-white text-sm font-semibold px-2 py-1 rounded-full badge">
                            Reserved
                        </div>
                    @endif
                </div>
            </div>

            <!-- Car Details Section -->
            <div class="mt-4 px-5 pb-5">
                <h5 class="font-semibold text-xl text-gray-800">{{ $car->brand }} {{ $car->model }} {{ $car->engine }}</h5>
                <p class="text-sm text-gray-600">Branch: {{ $car->branch }}</p>

                <div class="mt-2 mb-5 flex items-center justify-between">
                    <span class="text-3xl font-bold text-black">₱{{ number_format($car->price_per_day, 2) }}</span>
                </div>

                <div class="flex justify-between mt-4">
                    <a href="{{ route('car.reservation', ['car' => $car->id]) }}" 
                       class="reserve-button flex items-center justify-center rounded-lg bg-green-600 px-6 py-3 text-base font-bold text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-400 shadow-lg transition-all duration-300 ease-in-out"
                       style="font-family: 'Century Gothic', sans-serif;">
                        Rent Now
                    </a>

                    <!-- Rate Button -->
                    @auth
                        <button 
                            onclick="showRatingModal({{ $car->id }})"
                            class="rate-button flex items-center justify-center rounded-lg bg-yellow-500 px-6 py-3 text-base font-bold text-white hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-400 shadow-lg transition-all duration-300 ease-in-out"
                            style="font-family: 'Century Gothic', sans-serif;">
                            Rate this Car
                        </button>
                    @else
                        <a href="{{ route('login') }}" 
                           class="rate-button flex items-center justify-center rounded-lg bg-yellow-500 px-6 py-3 text-base font-bold text-white hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-400 shadow-lg transition-all duration-300 ease-in-out"
                           style="font-family: 'Century Gothic', sans-serif;">
                            Rate this Car
                        </a>
                    @endauth
                </div>

                <!-- Availability Section -->
                <div class="mt-4">
                    <p class="text-sm font-semibold text-gray-700">Availability:</p>
                    @if ($car->reservations->count() > 0)
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

                <!-- See Reviews Button -->
                <button onclick="showReviewsModal({{ $car->id }})" 
                        class="see-reviews-button mt-4 bg-blue-500 text-white px-4 py-2 rounded-lg">
                    See Reviews
                </button>

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
const sensitiveWords = ["nigga", "fuck", "shit", "bitch", "cunt", "asshole", "faggot", "whore", "slut", "dick", "gago","putang ina", "bobo", "tanga", "pussy", "angtangamo","tangina"];

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
<div id="confirmationModal" style="display: none;">
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
</style>

    @endsection
