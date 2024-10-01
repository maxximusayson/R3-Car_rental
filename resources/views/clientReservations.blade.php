@extends('layouts.myapp2')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<!-- Header Section -->
<header class="relative w-full bg-gradient-to-r from-purple-500 via-blue-500 to-teal-500 text-white py-12 shadow-lg rounded-lg">
    <div class="absolute top-6 left-6 md:left-12 lg:left-20 text-sm md:text-base lg:text-lg font-medium bg-white bg-opacity-90 px-6 py-3 rounded-full shadow-md flex items-center text-gray-800">
        <i class="fas fa-robot text-purple-500 mr-3"></i>
        Welcome, {{ Auth::user()->name }}!
    </div>
    <h1 class="text-4xl md:text-6xl lg:text-7xl font-extrabold text-white text-center mt-10">
        User Dashboard
    </h1>
    <div id="clock" class="absolute top-6 right-6 md:right-12 lg:right-20 text-sm md:text-base lg:text-lg font-light bg-white bg-opacity-90 px-6 py-3 rounded-full shadow-md text-gray-800 text-right"></div>
</header>

<!-- Main Content Section -->
<div class="container mx-auto px-6 py-12 flex-grow">
    <div class="flex flex-col lg:flex-row lg:space-x-8 space-y-8 lg:space-y-0">

        <!-- Profile Section -->
        <div class="lg:w-1/3 space-y-8">
            <div class="bg-white rounded-xl shadow-md p-6 border-t-4 border-blue-500">
    <h2 class="text-xl md:text-2xl font-semibold mb-4 text-blue-600">User Profile</h2>
    <div class="flex flex-col items-center">
        <div class="relative group">
            <img src="{{ Auth::user()->profile_picture_url }}" alt="Profile Picture" class="w-32 h-32 md:w-40 md:h-40 rounded-full mb-4 shadow-lg cursor-pointer hover:shadow-xl transition-shadow duration-300" id="profileImage">
            <span id="hoverText" class="absolute inset-0 flex items-center justify-center text-white bg-black bg-opacity-50 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300">Click to view</span>
        </div>
        
        <!-- Remove Profile Picture Button -->
        <button onclick="openConfirmationModal()" class="text-red-500 hover:text-red-700 transition mt-4">
            Remove Profile Picture
        </button>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="w-full mt-6">
    @csrf
    <div class="mt-4">
        <label for="name" class="block text-left text-gray-700 font-medium">Name:</label>
        <input type="text" id="name" name="name" value="{{ Auth::user()->name }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500">
    </div>
    <div class="mt-4">
        <label for="username" class="block text-left text-gray-700 font-medium">Username:</label>
        <input type="text" id="username" name="username" value="{{ Auth::user()->username }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500">
    </div>
    <div class="mt-4">
        <label for="email" class="block text-left text-gray-700 font-medium">Email:</label>
        <input type="email" id="email" name="email" value="{{ Auth::user()->email }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500" required>
    </div>
    <div class="mt-4">
        <label for="phone_number" class="block text-left text-gray-700 font-medium">Phone Number:</label>
        <input type="text" id="phone_number" name="phone_number" value="{{ Auth::user()->phone_number }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500">
    </div>
    <div class="mt-4">
        <label for="profile_picture" class="block text-left text-gray-700 font-medium">Profile Picture:</label>
        <input type="file" id="profile_picture" name="profile_picture" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500">
    </div>
    <button type="submit" class="mt-6 px-4 py-3 w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg shadow-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-300 font-semibold">Save</button>
</form>

    </div>
</div>

<!-- Confirmation Modal -->
<div id="confirmation-modal" class="fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white p-4 rounded-lg shadow-lg w-full max-w-md mx-auto">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Are you sure you want to remove your profile picture?</h3>
        <div class="flex justify-between">
            <button onclick="confirmRemove()" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600">Yes</button>
            <button onclick="closeConfirmationModal()" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">No</button>
        </div>
    </div>
</div>

<script>
    function openConfirmationModal() {
        document.getElementById('confirmation-modal').classList.remove('hidden');
    }

    function closeConfirmationModal() {
        document.getElementById('confirmation-modal').classList.add('hidden');
    }

    function confirmRemove() {
        // Submit the form to remove the profile picture
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ route('profile.removePicture') }}"; // Make sure this route exists
        form.innerHTML = '@csrf'; // Include CSRF token

        document.body.appendChild(form);
        form.submit();
    }
</script>

        
            <div class="bg-white rounded-xl shadow-md p-6 border-t-4 border-blue-500">
                <h2 class="text-xl md:text-2xl font-semibold mb-4 text-blue-600">Reservation Stats</h2>
                <div class="grid grid-cols-1 gap-4">
                    @foreach(['Active', 'Pending', 'Completed', 'Canceled'] as $status)
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-700">{{ $status }} Reservations:</span>
                            <span class="font-bold text-gray-900">{{ Auth::user()->reservations->where('status', $status)->count() }}</span>
                        </div>
                    @endforeach
                    <div class="flex justify-between mt-4">
                        <span class="font-medium text-gray-700">Total Reservations:</span>
                        <span class="font-bold text-gray-900">{{ Auth::user()->reservations->count() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Section -->
        <div class="lg:w-2/3 space-y-8">
            <!-- Notifications Section -->
            <div class="bg-white rounded-xl shadow-md p-6 h-80 overflow-y-auto border-t-4 border-blue-500 relative">
                <h2 class="text-2xl md:text-3xl font-semibold text-blue-600 mb-4">Notifications</h2>
                
                <!-- Button moved to the right upper corner -->
                <div class="absolute top-6 right-6">
                    <button 
                        id="see-more-btn" 
                        class="text-white bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded-lg"
                        onclick="toggleModal(true)"
                    >
                        See More
                    </button>
                </div>

                @if(Auth::user()->alerts->isEmpty())
                    <div class="text-center py-20 text-gray-500">
                        <p class="text-lg">No alerts available.</p>
                    </div>
                @else
                    <table class="w-full text-left border-separate border-spacing-2 md:border-spacing-4">
                        <thead>
                            <tr>
                                <th class="pb-3 text-left text-gray-600">Message</th>
                                <th class="pb-3 text-left text-gray-600">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(Auth::user()->alerts()->orderBy('created_at', 'desc')->take(2)->get() as $alert)
                                <tr class="border-t">
                                    <td class="py-2 md:py-3 text-gray-700 flex items-center">
                                        <span class="inline-block w-2 h-2 mr-2 bg-blue-500 rounded-full"></span>
                                        {{ $alert->message }}
                                    </td>
                                    <td class="py-2 md:py-3 text-gray-700">{{ $alert->created_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            <!-- Modal for Viewing All Notifications -->
            <div id="notification-modal" class="fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 hidden items-center justify-center z-50">
                <div class="bg-white p-4 rounded-lg shadow-lg w-full max-w-lg mx-auto h-3/4 overflow-y-auto relative">
                    <!-- Close Button at Top Right -->
                    <button 
                        class="absolute top-4 right-4 text-gray-600 hover:text-gray-900"
                        onclick="toggleModal(false)"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>

                    <h3 class="text-2xl font-semibold text-blue-600 mb-2">All Notifications</h3>
                    <table class="w-full text-left border-separate border-spacing-2 md:border-spacing-4">
                        <thead>
                            <tr>
                                <th class="pb-3 text-left text-gray-600">Message</th>
                                <th class="pb-3 text-left text-gray-600">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(Auth::user()->alerts()->orderBy('created_at', 'desc')->get() as $alert)
                                <tr class="border-t">
                                    <td class="py-2 md:py-3 text-gray-700 flex items-center">
                                        <span class="inline-block w-2 h-2 mr-2 bg-blue-500 rounded-full"></span>
                                        {{ $alert->message }}
                                    </td>
                                    <td class="py-2 md:py-3 text-gray-700">{{ $alert->created_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <script>
                function toggleModal(show) {
                    const modal = document.getElementById('notification-modal');
                    if (show) {
                        modal.classList.remove('hidden');
                        modal.classList.add('flex');
                    } else {
                        modal.classList.add('hidden');
                        modal.classList.remove('flex');
                    }
                }
            </script>

            <!-- My Recent Bookings -->
            <div class="bg-white rounded-xl shadow-md p-6 h-80 overflow-y-auto border-t-4 border-blue-500 relative">
                <h2 class="text-2xl md:text-3xl font-semibold text-blue-600 mb-4">My Recent Bookings</h2>

                <!-- Button to open modal positioned at the top-right corner -->
                <button 
                    id="see-bookings-btn" 
                    class="absolute top-4 right-4 text-white bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded-lg"
                    onclick="toggleBookingsModal(true)"
                >
                    View My Bookings
                </button>

                <table class="w-full text-left border-separate border-spacing-2 md:border-spacing-4">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="pb-3 text-left px-2 text-gray-600">Car</th>
                            <th class="pb-3 text-left px-2 text-gray-600">Started At</th>
                            <th class="pb-3 text-left px-2 text-gray-600">End At</th>
                            <th class="pb-3 text-left px-2 text-gray-600">Duration</th>
                            <th class="pb-3 text-left px-2 text-gray-600">Remaining Days</th>
                            <th class="pb-3 text-right px-2 text-gray-600">Price</th>
                            <th class="pb-3 text-left px-2 text-gray-600">Payment Status</th>
                            <th class="pb-3 text-left px-2 text-gray-600">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reservations->sortByDesc(function($reservation) {
                            return $reservation->status === 'Active';
                        }) as $reservation)
                            @php
                                $start_date = \Carbon\Carbon::parse($reservation->start_date);
                                $end_date = \Carbon\Carbon::parse($reservation->end_date);
                                $duration = $start_date->diffInDays($end_date);
                                $remaining_days = now()->diffInDays($end_date, false);
                            @endphp
                            <tr class="border-t @if($reservation->status === 'Active') bg-blue-50 @endif">
                                <td class="py-2 md:py-3 px-2 text-gray-700">{{ $reservation->car->brand }} {{ $reservation->car->model }}</td>
                                <td class="py-2 md:py-3 px-2 text-gray-700">{{ $start_date->format('Y-m-d') }}</td>
                                <td class="py-2 md:py-3 px-2 text-gray-700">{{ $end_date->format('Y-m-d') }}</td>
                                <td class="py-2 md:py-3 px-2 text-gray-700">{{ $duration }} days</td>
                                <td class="py-2 md:py-3 px-2 text-gray-700">{{ $remaining_days }} days</td>
                                <td class="py-2 md:py-3 text-right px-2 text-gray-700">{{ $reservation->total_price }} <span class="text-black">₱</span></td>
                                <td class="py-2 md:py-3 text-left text-green-500 px-2">{{ $reservation->payment_status }}</td>
                                <td class="py-2 md:py-3 text-left px-2 text-gray-700">{{ $reservation->status }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-3 text-center text-gray-500">There are no reservations yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Modal for Viewing All Recent Bookings -->
            <div id="bookings-modal" class="fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 hidden items-center justify-center z-50">
                <div class="bg-white p-4 rounded-lg shadow-lg w-full max-w-4xl mx-auto h-4/5 overflow-y-auto relative">
                    <!-- Close Button at Top Right -->
                    <button 
                        class="absolute top-4 right-4 text-gray-600 hover:text-gray-900"
                        onclick="toggleBookingsModal(false)"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>

                    <h3 class="text-2xl font-semibold text-blue-600 mb-4">My Recent Bookings</h3>
                    <table class="w-full text-left border-separate border-spacing-2 md:border-spacing-4">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="pb-3 text-left px-2 text-gray-600">Car</th>
                                <th class="pb-3 text-left px-2 text-gray-600">Started At</th>
                                <th class="pb-3 text-left px-2 text-gray-600">End At</th>
                                <th class="pb-3 text-left px-2 text-gray-600">Duration</th>
                                <th class="pb-3 text-left px-2 text-gray-600">Remaining Days</th>
                                <th class="pb-3 text-right px-2 text-gray-600">Price</th>
                                <th class="pb-3 text-left px-2 text-gray-600">Payment Status</th>
                                <th class="pb-3 text-left px-2 text-gray-600">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reservations as $reservation)
                                @php
                                    $start_date = \Carbon\Carbon::parse($reservation->start_date);
                                    $end_date = \Carbon\Carbon::parse($reservation->end_date);
                                    $duration = $start_date->diffInDays($end_date);
                                    $remaining_days = now()->diffInDays($end_date, false);
                                @endphp
                                <tr class="border-t @if($reservation->status === 'Active') bg-blue-50 @endif">
                                    <td class="py-2 md:py-3 px-2 text-gray-700">{{ $reservation->car->brand }} {{ $reservation->car->model }}</td>
                                    <td class="py-2 md:py-3 px-2 text-gray-700">{{ $start_date->format('Y-m-d') }}</td>
                                    <td class="py-2 md:py-3 px-2 text-gray-700">{{ $end_date->format('Y-m-d') }}</td>
                                    <td class="py-2 md:py-3 px-2 text-gray-700">{{ $duration }} days</td>
                                    <td class="py-2 md:py-3 px-2 text-gray-700">{{ $remaining_days }} days</td>
                                    <td class="py-2 md:py-3 text-right px-2 text-gray-700">{{ $reservation->total_price }} <span class="text-black">₱</span></td>
                                    <td class="py-2 md:py-3 text-left text-green-500 px-2">{{ $reservation->payment_status }}</td>
                                    <td class="py-2 md:py-3 text-left px-2 text-gray-700">{{ $reservation->status }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-3 text-center text-gray-500">There are no reservations yet.</td>
                                </tr>
                            @endforelse
                            </tbody>
                    </table>
                </div>
            </div>

            <script>
                function toggleBookingsModal(show) {
                    const modal = document.getElementById('bookings-modal');
                    if (show) {
                        modal.classList.remove('hidden');
                        modal.classList.add('flex');
                    } else {
                        modal.classList.add('hidden');
                        modal.classList.remove('flex');
                    }
                }
            </script>

            <!-- Display the uploaded IDs -->
<h2 class="text-2xl md:text-3xl font-semibold text-blue-600 mb-4">Reservation and Uploaded ID's</h2>
<div class="bg-white shadow-md rounded-lg p-6 relative">
    <!-- Button to see all uploaded IDs -->
    <div class="absolute top-6 right-6">
        <button 
            id="see-more-ids-btn" 
            class="text-white bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded-lg"
            onclick="toggleIdsModal(true)"
        >
            See More
        </button>
    </div>

    @foreach($reservations as $reservation)
        <div class="border-b border-gray-200 mb-4 pb-4">
            <h3 class="text-xl font-semibold">{{ $reservation->car->brand }} {{ $reservation->car->model }}</h3>
            <p class="text-gray-600">Reservation Date: {{ $reservation->start_date }} to {{ $reservation->end_date }}</p>
            
            <!-- Display the uploaded IDs -->
            <div class="mt-4">
                <div class="flex gap-6 mb-6">
                    <!-- Driver's License -->
                    <div class="flex-1">
                        <p class="font-medium text-gray-700 mb-2">Driver's License:</p>
                        @if($reservation->driver_license)
                            <img src="{{ asset($reservation->driver_license) }}" alt="Driver's License" class="rounded shadow-sm cursor-pointer" onclick="showImageModal(this)">
                        @else
                            <p class="text-gray-500">No driver's license uploaded.</p>
                        @endif
                    </div>
                    
                    <!-- Valid ID -->
                    <div class="flex-1">
                        <p class="font-medium text-gray-700 mb-2">Valid ID:</p>
                        @if($reservation->valid_id)
                            <img src="{{ asset($reservation->valid_id) }}" alt="Valid ID" class="rounded shadow-sm cursor-pointer" onclick="showImageModal(this)">
                        @else
                            <p class="text-gray-500">No valid ID uploaded.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Display remaining balance -->
            <div class="bg-gray-50 p-4 rounded-md border border-gray-200 shadow-sm">
                <p class="font-medium text-gray-700">Remaining Balance:</p>
                <p class="text-gray-800 text-lg" id="remaining-balance">
                    {{ number_format($reservation->remaining_balance, 1) }} PHP
                </p>
            </div>

            <!-- Delete Button -->
            <div class="mt-4">
                <form action="{{ route('reservations.destroy', $reservation->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this reservation?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 transition">
                        Delete Reservation
                    </button>
                </form>
            </div>
        </div>
    @endforeach
</div>

<!-- Modal for Viewing All Uploaded IDs -->
<div id="ids-modal" class="fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white p-4 rounded-lg shadow-lg w-full max-w-4xl mx-auto h-4/5 overflow-y-auto relative">
        <!-- Close Button at Top Right -->
        <button 
            class="absolute top-4 right-4 text-gray-600 hover:text-gray-900"
            onclick="toggleIdsModal(false)"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <h3 class="text-2xl font-semibold text-blue-600 mb-4">All Uploaded IDs</h3>
        <div class="space-y-4">
            @foreach($reservations as $reservation)
                <div class="border-b border-gray-200 pb-4">
                    <h4 class="text-xl font-semibold">{{ $reservation->car->brand }} {{ $reservation->car->model }}</h4>
                    <p class="text-gray-600">Reservation Date: {{ $reservation->start_date }} to {{ $reservation->end_date }}</p>
                    
                    <div class="flex gap-6 mt-4">
                        <div class="flex-1">
                            <p class="font-medium text-gray-700">Driver's License:</p>
                            @if($reservation->driver_license)
                                <img src="{{ asset($reservation->driver_license) }}" alt="Driver's License" class="rounded shadow-sm cursor-pointer" onclick="showImageModal(this)">
                            @else
                                <p class="text-gray-500">No driver's license uploaded.</p>
                            @endif
                        </div>

                        <div class="flex-1">
                            <p class="font-medium text-gray-700">Valid ID:</p>
                            @if($reservation->valid_id)
                                <img src="{{ asset($reservation->valid_id) }}" alt="Valid ID" class="rounded shadow-sm cursor-pointer" onclick="showImageModal(this)">
                            @else
                                <p class="text-gray-500">No valid ID uploaded.</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<script>
    // Toggle the IDs modal
    function toggleIdsModal(show) {
        const modal = document.getElementById('ids-modal');
        if (show) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        } else {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }
</script>

<style>
    /* Styles to enhance UI */
    img {
        max-width: 100%; /* Ensure images do not overflow their container */
        height: auto; /* Maintain aspect ratio */
    }
</style>

        </div>
    </div>
</div>

<!-- Modal for Fullscreen Image -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden flex justify-center items-center z-50">
    <img id="modalImage" src="" alt="Image" class="max-w-full max-h-full rounded-lg shadow-lg">
</div>

<script>
    // Image modal functionality
    function showImageModal(imageElement) {
        const modal = document.getElementById('imageModal');
        const modalImage = document.getElementById('modalImage');
        modalImage.src = imageElement.src;
        modal.classList.remove('hidden');
    }

    document.getElementById('imageModal').addEventListener('click', function(event) {
        if (event.target === this) {
            this.classList.add('hidden');
        }
    });

    // Real-time clock
    function updateDateTime() {
        const now = new Date();
        const date = now.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        const time = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        document.getElementById('clock').innerHTML = `${date}<br>${time}`;
    }
    
    setInterval(updateDateTime, 1000);
    updateDateTime();
</script>

<style>
    /* Styles to enhance UI */
    table {
        border-collapse: separate;
        border-spacing: 0 8px;
    }

    th, td {
        padding: 12px 15px;
        text-align: left;
    }

    thead th {
        background-color: #f9fafb;
        font-weight: bold;
        color: #4a5568;
    }

    tbody tr {
        background-color: #ffffff;
        border-bottom: 1px solid #e2e8f0;
    }

    tbody tr:last-child {
        border-bottom: none;
    }

    tbody tr.bg-blue-50 {
        background-color: #ebf8ff;
    }

    /* Profile Image Hover Text */
    #hoverText {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: rgba(0, 0, 0, 0.6);
        color: white;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 0.875rem;
        text-align: center;
        pointer-events: none;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    #profileImage:hover #hoverText {
        opacity: 1;
    }
</style>

@endsection

