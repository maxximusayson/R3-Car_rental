@extends('layouts.myapp2')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<!-- Header Section -->
<header class="text-center bg-gray-100 py-6 relative w-full">
    <!-- Welcome Message -->
    <div class="
        absolute top-5 left-5 
        text-xs md:text-sm lg:text-lg 
        font-medium 
        bg-white p-1 md:p-2 lg:p-4 
        rounded-lg 
        shadow-md 
        flex items-center
    ">
        <i class="fas fa-robot text-xs md:text-lg lg:text-2xl mr-2 text-gray-700"></i>
        Welcome, {{ Auth::user()->name }}!
    </div>

    <!-- Dashboard Title -->
    <h1 class="
        text-xl md:text-3xl lg:text-5xl xl:text-6xl 
        font-bold 
        bg-gradient-to-r from-blue-500 to-blue-700 
        text-transparent bg-clip-text
    ">
        User Dashboard
    </h1>

    <!-- Real-time Clock -->
    <div id="clock" class="
        absolute top-5 right-5 
        text-xs md:text-sm lg:text-lg 
        font-light 
        bg-white p-1 md:p-2 lg:p-4 
        rounded-lg 
        shadow-md 
        text-right
    "></div>
</header>

<!-- Main Content Section -->
<div class="container mx-auto px-4 py-6 flex-grow">
    <div class="flex flex-col md:flex-row md:space-x-6 space-y-6 md:space-y-0">
        <!-- Profile Section -->
        <div class="md:w-1/3 space-y-6">
            <div class="bg-white rounded-lg shadow-md p-4 md:p-6">
                <h2 class="text-lg md:text-xl font-semibold mb-4">User Profile</h2>
                <div class="flex flex-col items-center">
                    <img src="{{ Auth::user()->profile_picture_url }}" alt="Profile Picture" class="w-16 h-16 md:w-24 md:h-24 rounded-full mb-4">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mt-4">
                            <label for="name" class="block text-left">Name:</label>
                            <input type="text" id="name" name="name" value="{{ Auth::user()->name }}" class="mt-1 block w-full">
                        </div>
                        <div class="mt-4">
                            <label for="email" class="block text-left">Email:</label>
                            <input type="email" id="email" name="email" value="{{ Auth::user()->email }}" class="mt-1 block w-full">
                        </div>
                        <div class="mt-4">
                            <label for="phone_number" class="block text-left">Phone Number:</label>
                            <input type="text" id="phone_number" name="phone_number" value="{{ Auth::user()->phone_number }}" class="mt-1 block w-full">
                        </div>
                        <div class="mt-4">
                            <label for="profile_picture" class="block text-left">Profile Picture:</label>
                            <input type="file" id="profile_picture" name="profile_picture" class="mt-1 block w-full">
                        </div>
                        <button type="submit" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Save</button>
                    </form>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 md:p-6">
                <h2 class="text-lg md:text-xl font-semibold mb-4">Reservation Stats</h2>
                <div class="grid grid-cols-1 gap-4">
                    @foreach(['Active', 'Pending', 'Completed', 'Canceled'] as $status)
                        <div class="flex justify-between">
                            <span class="font-medium">{{ $status }} Reservations:</span>
                            <span class="font-bold">{{ Auth::user()->reservations->where('status', $status)->count() }}</span>
                        </div>
                    @endforeach
                    <div class="flex justify-between mt-4">
                        <span class="font-medium">Total Reservations:</span>
                        <span class="font-bold">{{ Auth::user()->reservations->count() }}</span>
                    </div>
                </div>
            </div>


            <!-- upload id -->
            <div class="bg-white rounded-lg shadow-md p-4 md:p-6">
                <h1 class="text-lg md:text-xl font-semibold mb-4">Upload Your Driver License and Proof of Billing</h1>
                <form id="uploadForm" action="{{ route('uploadFiles') }}" method="POST" enctype="multipart/form-data">        
                    @csrf
                    <div class="mt-4">
                        <label for="valid_id" class="block text-left">Upload Driver's License:</label>
                        <input type="file" id="valid_id" name="valid_id" accept="image/*" class="mt-1 block w-full">
                    </div>
                    <div class="mt-4">
                        <label for="proof_of_billing" class="block text-left">Upload Proof of Billing:</label>
                        <input type="file" id="proof_of_billing" name="proof_of_billing" accept="image/*" class="mt-1 block w-full">
                    </div>
                    <button type="submit" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Upload</button>
                </form>

                @if(session('validIdImageUrl'))
                    <div class="mt-4">
                        <h2 class="text-lg md:text-xl font-semibold">Uploaded Driver's License:</h2>
                        <img src="{{ session('validIdImageUrl') }}" alt="Uploaded Valid ID" class="mt-2 max-w-full h-auto rounded-md shadow-md">
                    </div>
                @endif
                @if(session('proofOfBillingImageUrl'))
                    <div class="mt-4">
                        <h2 class="text-lg md:text-xl font-semibold">Uploaded Proof of Billing:</h2>
                        <img src="{{ session('proofOfBillingImageUrl') }}" alt="Uploaded Proof of Billing" class="mt-2 max-w-full h-auto rounded-md shadow-md">
                    </div>
                @endif
            </div>
        </div>

        <!-- Main Content Section -->
        <div class="md:w-2/3 space-y-6">
            <div class="bg-white rounded-lg shadow-md p-4 md:p-6 h-80 overflow-y-auto">
                <h2 class="text-2xl md:text-3xl font-semibold text-gray-800 mb-4">Notifications</h2>
                @if(Auth::user()->alerts->isEmpty())
                    <div class="text-center py-20 text-gray-500">
                        <p class="text-lg">No alerts available.</p>
                    </div>
                @else
                    <table class="w-full text-left border-separate border-spacing-2 md:border-spacing-4">
                        <thead>
                            <tr>
                                <th class="pb-3 text-left">Message</th>
                                <th class="pb-3 text-left">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(Auth::user()->alerts as $alert)
                                <tr class="border-t">
                                    <td class="py-2 md:py-3">{{ $alert->message }}</td>
                                    <td class="py-2 md:py-3">{{ $alert->created_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

           <!-- My Recent Bookings -->
            <div class="bg-white rounded-lg shadow-md p-4 md:p-6 h-80 overflow-y-auto">
                <h2 class="text-2xl md:text-3xl font-semibold text-gray-800 mb-4">My Recent Bookings</h2>
                <table class="w-full text-left border-separate border-spacing-2 md:border-spacing-4">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="pb-3 text-left px-2">Car</th>
                            <th class="pb-3 text-left px-2">Started At</th>
                            <th class="pb-3 text-left px-2">End At</th>
                            <th class="pb-3 text-left px-2">Duration</th>
                            <th class="pb-3 text-left px-2">Remaining Days</th>
                            <th class="pb-3 text-right px-2">Price</th>
                            <th class="pb-3 text-left px-2">Payment Status</th>
                            <th class="pb-3 text-left px-2">Status</th>
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
                            <tr class="border-t @if($reservation->status === 'Active') bg-yellow-100 @endif">
                                <td class="py-2 md:py-3 px-2">{{ $reservation->car->brand }} {{ $reservation->car->model }}</td>
                                <td class="py-2 md:py-3 px-2">{{ $start_date->format('Y-m-d') }}</td>
                                <td class="py-2 md:py-3 px-2">{{ $end_date->format('Y-m-d') }}</td>
                                <td class="py-2 md:py-3 px-2">{{ $duration }} days</td>
                                <td class="py-2 md:py-3 px-2">{{ $remaining_days }} days</td>
                                <td class="py-2 md:py-3 text-right px-2">{{ $reservation->total_price }} <span class="text-black">₱</span></td>
                                <td class="py-2 md:py-3 text-left text-green-500 px-2">{{ $reservation->payment_status }}</td>
                                <td class="py-2 md:py-3 text-left px-2">{{ $reservation->status }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-3 text-center">There are no reservations yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
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
    html, body {
        height: 100%;
        margin: 0;
    }

    .container {
        min-height: calc(100vh - 4rem); /* Adjust based on your header/footer height */
        display: flex;
        flex-direction: column;
    }
    table {
        border-collapse: separate;
        border-spacing: 0 10px;
    }

    th, td {
        padding: 12px 15px;
        text-align: left;
    }

    thead th {
        background-color: #f3f4f6;
        font-weight: bold;
    }

    tbody tr {
        background-color: #ffffff;
        border-bottom: 1px solid #e5e7eb;
    }

    tbody tr:last-child {
        border-bottom: none;
    }

    tbody tr.bg-yellow-100 {
        background-color: #fef3c7;
    }
</style>
@endsection
