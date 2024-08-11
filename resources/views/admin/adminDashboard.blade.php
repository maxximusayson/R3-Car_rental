@extends('layouts.myapp1')

@section('content')



@php
    $totalCars = \App\Models\Car::count();
    $totalUsedCars = \App\Models\Reservation::where('status', 'Active')->count();
    $totalNotUsedCars = $totalCars - $totalUsedCars;
@endphp

<div class="container mx-auto mt-8">
    <div class="grid grid-cols-3 gap-4">
        <div class="bg-gray-100 p-4 rounded-lg border border-gray-200 shadow-md flex items-center">
            <div>
                <svg class="w-10 h-10 text-gray-600 dark:text-gray-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M14 3a2 2 0 012 2v9a2 2 0 01-2 2H6a2 2 0 01-2-2V5a2 2 0 012-2h8zm0 1H6a1 1 0 00-1 1v9a1 1 0 001 1h8a1 1 0 001-1V5a1 1 0 00-1-1z" clip-rule="evenodd" />
                    <path fill-rule="evenodd" d="M8 13a1 1 0 100-2 1 1 0 000 2zM12 13a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-4">
                <div class="text-lg font-semibold text-gray-600 dark:text-gray-300">
                    Total Cars
                </div>
                <div class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                    {{ $totalCars }}
                </div>
            </div>
        </div>
        <div class="bg-gray-100 p-4 rounded-lg border border-gray-200 shadow-md flex items-center">
            <div>
                <svg class="w-10 h-10 text-gray-600 dark:text-gray-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a1 1 0 01-1-1V8H4a1 1 0 01-1-1V5a1 1 0 011-1h2a1 1 0 011-1h6a1 1 0 011 1h2a1 1 0 011 1v2a1 1 0 01-1 1h-4v9a1 1 0 01-1 1h-2zm-1-10h2v8h-2v-8zm-3-3h6v1H6V5zm8 0h1v1h-1V5zm-8 3h6v1H6V8zm8 0h1v1h-1V8z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-4">
                <div class="text-lg font-semibold text-gray-600 dark:text-gray-300">
                    Total Cars in Use
                </div>
                <div class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                    {{ $totalUsedCars }}
                </div>
            </div>
        </div>
        <div class="bg-gray-100 p-4 rounded-lg border border-gray-200 shadow-md flex items-center">
            <div>
                <svg class="w-10 h-10 text-gray-600 dark:text-gray-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.293 5.293a1 1 0 011.414 0L10 8.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    <path fill-rule="evenodd" d="M4 11a1 1 0 011-1h10a1 1 0 010 2H5a1 1 0 01-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-4">
                <div class="text-lg font-semibold text-gray-600 dark:text-gray-300">
                    Total Cars Not in Use
                </div>
                <div class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                    {{ $totalNotUsedCars }}
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Clock -->
    <div id="clock" class="text-gray-900 dark:text-gray-300 text-lg font-semibold absolute top-4 right-4">
        <span id="time"></span>
    </div>
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


<!-- Admin Notification Section -->
<div class="container mx-auto mt-8">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <h2 class="px-6 py-4 text-lg font-semibold text-gray-700 dark:text-gray-300">Notifications</h2>
        <div class="overflow-x-auto">
            <div class="min-w-full overflow-y-auto" style="max-height: 300px;"> <!-- Adjust max-height to your desired value -->
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

<script>
document.querySelectorAll('.notification-row').forEach(row => {
    row.addEventListener('click', function() {
        const notificationId = this.getAttribute('data-id');

        fetch(`/notifications/${notificationId}/mark-as-seen`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ id: notificationId })
        }).then(response => response.json()).then(data => {
            if (data.success) {
                this.querySelector('.bg-green-500').classList.remove('bg-green-500');
            }
        });
    });
});
</script>






<!-- Chart -->
<div class="container px-4 mx-auto mt-8">
    <h2 class="my-4 text-lg font-semibold text-gray-600 dark:text-gray-300">
        Reservations per Month
    </h2>
    <div class="w-full p-6 bg-white border rounded-lg shadow-md">
        <canvas id="reservationsChart" width="600" height="300"></canvas>
    </div>
</div>



<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    var ctx = document.getElementById('reservationsChart').getContext('2d');
    var reservationsData = {!! json_encode($reservationsData) !!};

    var reservationsChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: reservationsData.labels,
            datasets: [{
                label: 'Reservations',
                data: reservationsData.data,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
</script>

<!-- Booking History -->    
<div class="container mx-auto mt-8">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <h2 class="px-6 py-4 text-lg font-semibold text-gray-700 dark:text-gray-300">Booking History</h2>
        <div class="overflow-x-auto">
            <div class="min-w-full overflow-y-auto" style="max-height: 200px;"> <!-- Adjust max-height to your desired value -->
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
                        @foreach($bookingHistory->take(5) as $index => $reservation) <!-- Limiting to 5 rows -->
                        <tr class="text-gray-700 dark:text-gray-400 @if($index == 0) bg-yellow-200 @endif"> <!-- Highlighting the first row -->
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
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <h2 class="px-6 py-4 text-lg font-semibold text-gray-700 dark:text-gray-300">Upcoming Bookings</h2>
        <div class="overflow-x-auto">
            <div class="min-w-full overflow-y-auto" style="max-height: 200px;"> <!-- Adjust max-height to your desired value -->
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
                        @forelse($upcomingBookings->take(5)->reverse() as $index => $reservation) <!-- Limiting to 5 rows and then reversing the array -->
                        <tr class="text-gray-700 dark:text-gray-400 @if($reservation->status == 'Active') bg-yellow-200 @endif"> <!-- Highlighting the active bookings -->
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




<!-- Ensure you have FontAwesome included -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-XXXXX" crossorigin="anonymous" />

<!-- Current Users -->
<div class="container mx-auto mt-8">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <h2 class="px-6 py-4 text-lg font-semibold text-gray-700 dark:text-gray-300">Current Users</h2>
        
        <!-- Filter/Search Input with Icon -->
        <div class="px-6 py-4 flex items-center">
            <div class="mr-3">
                <i class="fas fa-search text-gray-400"></i>
            </div>
            <input type="text" id="userFilter" placeholder="Search users..." class="w-40 sm:w-64 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-indigo-200 focus:border-indigo-500">
        </div>

        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead>
                    <tr class="text-left font-semibold text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700">
                        <th class="px-6 py-3">Name</th>
                        <th class="px-6 py-3">Email</th>
                        <th class="px-6 py-3">Status</th>
                    </tr>
                </thead>
                <tbody id="userTableBody" class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($currentUsers as $user)
                    <tr class="text-gray-700 dark:text-gray-400">
                        <td class="px-6 py-4">{{ $user->name }}</td>
                        <td class="px-6 py-4">{{ $user->email }}</td>
                        <td class="px-6 py-4 flex items-center">
                            <div class="h-4 w-4 rounded-full mr-2 @if($user->active) bg-green-500 @else bg-red-500 @endif"></div>
                            @if($user->active)
                                <span class="text-green-500">Active</span>
                            @else
                                <span class="text-red-500">Not Active</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @if ($currentUsers->isEmpty())
                    <tr class="text-gray-700 dark:text-gray-400" id="noUsersFound">
                        <td colspan="3" class="text-center py-4">No current users found.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
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






<!-- Reservation Table -->
<div class="container mx-auto mt-8">
    <main class="h-full overflow-y-auto">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
            <h2 class="px-6 py-4 text-lg font-semibold text-gray-700 dark:text-gray-300">RESERVATIONS</h2>
            <div class="overflow-x-auto">
                <div class="min-w-full overflow-y-auto" style="max-height: 200px;"> <!-- Adjust max-height to your desired value -->
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
                            @forelse ($reservations->take(5) as $index => $reservation) <!-- Limiting to 5 rows -->
                            <tr class="text-gray-700 dark:text-gray-400 @if($reservation->status == 'Active') bg-yellow-200 @endif"> <!-- Highlighting the active reservations -->
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
    </main>
</div>



           
@endsection
