@extends('layouts.myapp1')

@section('content')

<div class="mx-auto max-w-screen-xl mt-12">
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
        <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-200 mb-4">Client Details</h2>
        
        <!-- Client Information -->
        <div class="mb-6">
            <div class="flex items-center">
                <div>
                    @if($client->profile_picture_url)
                        <img src="{{ asset($client->profile_picture_url) }}" alt="{{ $client->name }}" class="h-16 w-16 rounded-full object-cover">
                    @else
                        <img src="{{ asset('images/default-profile.png') }}" alt="Default Profile Picture" class="h-16 w-16 rounded-full object-cover">
                    @endif
                </div>
                <div class="ml-4">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-200">{{ $client->name }}</h3>
                    <p class="text-gray-600 dark:text-gray-400">{{ $client->email }}</p>
                    <p class="text-gray-600 dark:text-gray-400">Joined: {{ $client->created_at->format('Y-m-d') }}</p>
                </div>
            </div>
        </div>

        <!-- Client Reservations -->
        <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-200 mb-4">Reservations</h3>
        @if($client->reservations->isEmpty())
            <p class="text-gray-500 dark:text-gray-400">No reservations found.</p>
        @else
            <div class="w-full overflow-hidden rounded-lg shadow-lg mb-6">
                <div class="w-full overflow-x-auto">
                    <table class="w-full whitespace-no-wrap table-auto text-center bg-white dark:bg-gray-800 shadow-md rounded-lg">
                        <thead>
                            <tr class="text-xs font-semibold tracking-wide text-gray-600 uppercase bg-indigo-100 dark:bg-indigo-900 dark:text-indigo-300">
                                <th class="px-6 py-4">Car</th>
                                <th class="px-6 py-4">Start Date</th>
                                <th class="px-6 py-4">End Date</th>
                                <th class="px-6 py-4">Price</th>
                                <th class="px-6 py-4">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($client->reservations as $reservation)
                                <tr class="text-gray-700 dark:text-gray-400 hover:bg-indigo-50 dark:hover:bg-indigo-700">
                                    <td class="px-6 py-4">
                                        {{ $reservation->car->brand }} {{ $reservation->car->model }}
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        {{ $reservation->start_date->format('Y-m-d') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        {{ $reservation->end_date->format('Y-m-d') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        {{ $reservation->total_price }} ₱
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        {{ $reservation->status }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <a href="{{ route('clients.index') }}" class="text-blue-500 hover:text-blue-700">
            Back to Clients
        </a>
    </div>
</div>

@endsection
