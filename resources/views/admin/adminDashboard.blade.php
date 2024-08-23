@extends('layouts.myapp1')

@section('content')

@php
    $totalCars = \App\Models\Car::count();
    $totalUsedCars = \App\Models\Reservation::where('status', 'Active')->count();
    $totalNotUsedCars = $totalCars - $totalUsedCars;
@endphp

<div class="container mx-auto mt-8">

    <!-- Welcome Message -->
    <div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 text-white p-6 rounded-lg shadow-lg mb-8">
        <h1 class="text-2xl font-bold">Welcome, Admin!</h1>
        <p class="mt-2">Here's an overview of your current activities and statistics.</p>
    </div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Total Cars -->
    <div class="bg-white p-6 rounded-lg shadow-lg flex items-center">
        <div class="flex-shrink-0">
            <!-- Icon for Total Cars -->
            <svg class="w-12 h-12 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 3.75l-1.5 9.5h7.5l-1.5-9.5M4.5 13.5h15v5h-1.5l-1.5 3h-9l-1.5-3H4.5v-5zm0-3h15a1.5 1.5 0 100-3h-15a1.5 1.5 0 100 3z" />
            </svg>
        </div>
        <div class="ml-6">
            <h3 class="text-lg font-semibold text-gray-700">Total Cars</h3>
            <p class="text-4xl font-bold text-gray-900">{{ $totalCars }}</p>
        </div>
    </div>
    
    <!-- Cars in Use -->
    <div class="bg-white p-6 rounded-lg shadow-lg flex items-center">
        <div class="flex-shrink-0">
            <!-- Icon for Cars in Use -->
            <svg class="w-12 h-12 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M5 17h14M8 12h8" />
            </svg>
        </div>
        <div class="ml-6">
            <h3 class="text-lg font-semibold text-gray-700">Cars in Use</h3>
            <p class="text-4xl font-bold text-gray-900">{{ $totalUsedCars }}</p>
        </div>
    </div>
    
    <!-- Cars Not in Use -->
    <div class="bg-white p-6 rounded-lg shadow-lg flex items-center">
        <div class="flex-shrink-0">
            <!-- Icon for Cars Not in Use -->
            <svg class="w-12 h-12 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9V3.75m0 16.5V15m0 0H9.75M12 15h2.25M21 6.75l-1.5 9.5h-15l-1.5-9.5M4.5 13.5h15v5h-1.5l-1.5 3h-9l-1.5-3H4.5v-5z" />
            </svg>
        </div>
        <div class="ml-6">
            <h3 class="text-lg font-semibold text-gray-700">Cars Not in Use</h3>
            <p class="text-4xl font-bold text-gray-900">{{ $totalNotUsedCars }}</p>
        </div>
    </div>
</div>


<!-- Clock -->
<div id="clock" class="text-white text-lg font-semibold absolute top-6 right-6 bg-gradient-to-r from-blue-500 to-indigo-600 py-2 px-4 rounded-lg shadow-lg">
    <span id="time"></span>
</div>

<script>
    function updateTime() {
        const now = new Date();
        let hours = now.getHours();
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        const ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12;
        hours = hours ? hours : 12; // the hour '0' should be '12'

        const month = now.toLocaleString('default', { month: 'long' });
        const day = now.getDate();
        const year = now.getFullYear();

        const dateString = `${month} ${day}, ${year}`;
        const timeString = `${hours}:${minutes}:${seconds} ${ampm}`;
        document.getElementById('time').textContent = `${dateString} ${timeString}`;
    }

    setInterval(updateTime, 1000);
    updateTime();  // Initial call to display the time immediately
</script>
<!-- Notifications Section -->
<div class="container mx-auto mt-8">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
        <div class="flex justify-between items-center px-6 py-4">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-300">Notifications</h2>
            <div>
                <form action="{{ route('notifications.export') }}" method="GET" class="inline">
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg shadow hover:bg-blue-700">
                        Export to CSV
                    </button>
                </form>
                <form action="{{ route('notifications.import') }}" method="POST" enctype="multipart/form-data" class="inline">
                    @csrf
                    <input type="file" name="csv_file" accept=".csv" class="hidden" id="csv-file-input">
                    <label for="csv-file-input" class="px-4 py-2 bg-green-500 text-white rounded-lg shadow hover:bg-green-700 cursor-pointer">
                        Import CSV
                    </label>
                </form>
            </div>
        </div>
        <div class="overflow-x-auto">
            <div class="min-w-full overflow-y-auto" style="max-height: 300px;">
                <table class="w-full whitespace-nowrap">
                    <thead>
                        <tr class="text-left font-semibold text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700">
                            <th class="px-6 py-3">Message</th>
                            <th class="px-6 py-3">Created At</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($notifications as $notification)
                        <tr class="text-gray-700 dark:text-gray-400 notification-row" data-id="{{ $notification->id }}">
                            <td class="px-6 py-4">
                                {{ $notification->data['feedback'] ?? 'No message available' }}
                            </td>
                            <td class="px-6 py-4">{{ $notification->created_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                        @empty
                        <tr class="text-gray-700 dark:text-gray-400">
                            <td colspan="2" class="text-center py-4">No notifications found.</td>
                        </tr>
                        @endforelse
                        @forelse($upcomingBookings as $booking)
                        <tr class="text-gray-700 dark:text-gray-400 notification-row" data-id="{{ $booking->id }}">
                            <td class="px-6 py-4">
                                <a href="{{ route('rentDetails', ['reservation' => $booking->id]) }}" class="flex items-center">
                                    <span class="inline-block h-4 w-4 rounded-full bg-green-500 mr-2"></span>
                                    Upcoming booking for {{ $booking->user->name }}: {{ $booking->car->model }} from {{ $booking->start_date }} to {{ $booking->end_date }}.
                                </a>
                            </td>
                            <td class="px-6 py-4">{{ \Carbon\Carbon::parse($booking->start_date)->format('Y-m-d H:i:s') }}</td>
                        </tr>
                        @empty
                        <tr class="text-gray-700 dark:text-gray-400">
                            <td colspan="2" class="text-center py-4">No upcoming bookings found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<!-- Chart Section -->
<div class="container px-4 mx-auto mt-8">
    <h2 class="my-4 text-xl font-semibold text-gray-800 dark:text-gray-300">
        Reservations Distribution
    </h2>
    <div class="w-full p-6 bg-white rounded-lg shadow-lg">
        <canvas id="reservationsPieChart" width="600" height="300"></canvas>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('reservationsPieChart').getContext('2d');
    var reservationsData = {!! json_encode($reservationsData) !!};

    var reservationsPieChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: reservationsData.labels,
            datasets: [{
                label: 'Reservations',
                data: reservationsData.data,
                backgroundColor: [
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(255, 206, 86, 0.8)',
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(153, 102, 255, 0.8)',
                    'rgba(255, 159, 64, 0.8)'
                ],
                borderColor: '#ffffff', // White border for clean separation between segments
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right', // Position the legend to the right
                    labels: {
                        color: '#6b7280', // Modern gray color for legend labels
                        font: {
                            size: 14
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)', // Dark background for tooltips
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderWidth: 1,
                    borderColor: '#ddd',
                    titleFont: {
                        weight: 'bold',
                        size: 14
                    },
                    bodyFont: {
                        size: 12
                    },
                    cornerRadius: 8,
                    padding: 10
                }
            }
        }
    });
</script>


<!-- Booking History -->
<div class="container mx-auto mt-8">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
        <h2 class="px-6 py-4 text-xl font-semibold text-gray-800 dark:text-gray-300">Booking History</h2>
        <div class="overflow-x-auto">
            <div class="min-w-full overflow-y-auto" style="max-height: 200px;">
                <table class="w-full whitespace-nowrap">
                    <thead>
                        <tr class="text-left font-semibold text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700">
                            <th class="px-6 py-3">User</th>
                            <th class="px-6 py-3">Car</th>
                            <th class="px-6 py-3">Start Date</th>
                            <th class="px-6 py-3">End Date</th>
                            <th class="px-6 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($bookingHistory->take(5) as $index => $reservation)
                        <tr class="text-gray-700 dark:text-gray-400 @if($index == 0) bg-yellow-200 @endif">
                            <td class="px-6 py-4">{{ $reservation->user->name }}</td>
                            <td class="px-6 py-4">{{ $reservation->car->model }}</td>
                            <td class="px-6 py-4">{{ $reservation->start_date }}</td>
                            <td class="px-6 py-4">{{ $reservation->end_date }}</td>
                            <td class="px-6 py-4">{{ $reservation->status }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Upcoming Bookings -->
<div class="container mx-auto mt-8">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
        <h2 class="px-6 py-4 text-xl font-semibold text-gray-800 dark:text-gray-300">Upcoming Bookings</h2>
        <div class="overflow-x-auto">
            <div class="min-w-full overflow-y-auto" style="max-height: 200px;">
                <table class="w-full whitespace-nowrap">
                    <thead>
                        <tr class="text-left font-semibold text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700">
                            <th class="px-6 py-3">User</th>
                            <th class="px-6 py-3">Car</th>
                            <th class="px-6 py-3">Start Date</th>
                            <th class="px-6 py-3">End Date</th>
                            <th class="px-6 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($upcomingBookings->take(5)->reverse() as $index => $reservation)
                        <tr class="text-gray-700 dark:text-gray-400 @if($reservation->status == 'Active') bg-yellow-200 @endif">
                            <td class="px-6 py-4">{{ $reservation->user->name }}</td>
                            <td class="px-6 py-4">{{ $reservation->car->model }}</td>
                            <td class="px-6 py-4">{{ $reservation->start_date }}</td>
                            <td class="px-6 py-4">{{ $reservation->end_date }}</td>
                            <td class="px-6 py-4">{{ $reservation->status }}</td>
                        </tr>
                        @empty
                        <tr class="text-gray-700 dark:text-gray-400">
                            <td colspan="5" class="text-center py-4">No upcoming bookings found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Users Section -->
<div class="container mx-auto mt-8">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
        <h2 class="px-6 py-4 text-xl font-semibold text-gray-800 dark:text-gray-300">Current Users</h2>
        
        <!-- Search Input -->
        <div class="px-6 py-4 flex items-center">
            <div class="mr-3">
                <i class="fas fa-search text-gray-400"></i>
            </div>
            <input type="text" id="userFilter" placeholder="Search users..." class="w-full md:w-1/3 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>

        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead>
                    <tr class="text-left font-semibold text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700">
                        <th class="px-6 py-3">Profile Picture</th>
                        <th class="px-6 py-3">Name</th>
                        <th class="px-6 py-3">Email</th>
                        <th class="px-6 py-3">Status</th>
                    </tr>
                </thead>
                <tbody id="userTableBody" class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($currentUsers as $user)
                    <tr class="text-gray-700 dark:text-gray-400">
                        <!-- Profile Picture -->
                        <td class="px-6 py-4">
                            @if($user->profile_picture_url)
                                <img src="{{ asset($user->profile_picture_url) }}" alt="{{ $user->name }}" class="h-10 w-10 rounded-full object-cover">
                            @else
                                <img src="{{ asset('images/default-profile.png') }}" alt="Default Profile Picture" class="h-10 w-10 rounded-full object-cover">
                            @endif
                        </td>
                        <td class="px-6 py-4">{{ $user->name }}</td>
                        <td class="px-6 py-4">{{ $user->email }}</td>
                        <td class="px-6 py-4 flex items-center">
                            <div class="h-4 w-4 rounded-full mr-2 @if($user->active) bg-green-500 @else bg-red-500 @endif"></div>
                            <span>@if($user->active) Active @else Not Active @endif</span>
                        </td>
                    </tr>
                    @endforeach
                    @if ($currentUsers->isEmpty())
                    <tr class="text-gray-700 dark:text-gray-400" id="noUsersFound">
                        <td colspan="4" class="text-center py-4">No current users found.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- Reservation Table -->
<div class="container mx-auto mt-8">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
        <h2 class="px-6 py-4 text-xl font-semibold text-gray-800 dark:text-gray-300">Reservations</h2>
        <div class="overflow-x-auto">
            <div class="min-w-full overflow-y-auto" style="max-height: 200px;">
                <table class="w-full whitespace-nowrap">
                    <thead>
                        <tr class="text-left font-semibold text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700">
                            <th class="px-6 py-3">Client</th>
                            <th class="px-6 py-3">Car</th>
                            <th class="px-6 py-3">Started at</th>
                            <th class="px-6 py-3">End at</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($reservations->take(5) as $index => $reservation)
                        <tr class="text-gray-700 dark:text-gray-400 @if($reservation->status == 'Active') bg-yellow-200 @endif">
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-semibold">{{ $reservation->user->name }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $reservation->user->email }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">{{ $reservation->car->brand }} {{ $reservation->car->model }}</td>
                            <td class="px-6 py-4">{{ Carbon\Carbon::parse($reservation->start_date)->format('Y-m-d') }}</td>
                            <td class="px-6 py-4">{{ Carbon\Carbon::parse($reservation->end_date)->format('Y-m-d') }}</td>
                            <td class="px-6 py-4">{{ $reservation->status }}</td>
                            <td class="px-6 py-4">
                                <a class="text-blue-600 hover:underline" href="{{ route('editStatus', ['reservation' => $reservation->id]) }}">Update Client Status</a><br>
                                <a class="text-blue-600 hover:underline" href="{{ route('editPayment', ['reservation' => $reservation->id]) }}">Edit Payment</a><br>
                                <a class="text-red-600 hover:underline" href="{{ route('deleteReservation', ['reservation' => $reservation->id]) }}" onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this reservation?')) { document.getElementById('delete-form-{{ $reservation->id }}').submit(); }">Delete</a>
                                <form id="delete-form-{{ $reservation->id }}" action="{{ route('deleteReservation', ['reservation' => $reservation->id]) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr class="text-gray-700 dark:text-gray-400">
                            <td colspan="6" class="text-center py-4">No reservations found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    // JavaScript for filtering users based on input
    const userFilterInput = document.getElementById('userFilter');
    const userTableBody = document.getElementById('userTableBody');
    const noUsersFoundRow = document.getElementById('noUsersFound');

    userFilterInput.addEventListener('input', function () {
        const filterValue = this.value.toLowerCase();
        const rows = userTableBody.getElementsByTagName('tr');
        let foundUsers = false;

        for (let i = 0; i < rows.length; i++) {
            const nameColumn = rows[i].getElementsByTagName('td')[0];
            const emailColumn = rows[i].getElementsByTagName('td')[1];

            if (nameColumn || emailColumn) {
                const nameText = nameColumn.textContent || nameColumn.innerText;
                const emailText = emailColumn.textContent || emailColumn.innerText;

                if (nameText.toLowerCase().indexOf(filterValue) > -1 || emailText.toLowerCase().indexOf(filterValue) > -1) {
                    rows[i].style.display = '';
                    foundUsers = true;
                } else {
                    rows[i].style.display = 'none';
                }
            }
        }

        // Show/hide the "No users found" message based on filter results
        if (foundUsers) {
            noUsersFoundRow.style.display = 'none';
        } else {
            noUsersFoundRow.style.display = '';
        }
    });
</script>

@endsection
