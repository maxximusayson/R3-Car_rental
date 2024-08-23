@extends('layouts.myapp1')

@section('content')
<div class="mx-auto max-w-screen-xl p-4">
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
        <div class="flex items-center mb-4">
            @if($client->profile_picture_url)
                <img src="{{ asset($client->profile_picture_url) }}" alt="{{ $client->name }}" class="h-20 w-20 rounded-full object-cover">
            @else
                <img src="{{ asset('images/default-profile.png') }}" alt="Default Profile Picture" class="h-20 w-20 rounded-full object-cover">
            @endif
            <div class="ml-4">
                <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ $client->name }}</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $client->email }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400">Joined: {{ $client->created_at->format('Y-m-d') }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400">Reservations: {{ $client->reservations_count }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    <strong>Driver's License:</strong>
                    @if($client->driver_license)
                        <a href="{{ asset($client->driver_license) }}" class="text-indigo-500 hover:text-indigo-700" target="_blank">View Document</a>
                    @else
                        Not provided
                    @endif
                </p>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    <strong>Proof of Billing:</strong>
                    @if($client->proof_of_billing)
                        <a href="{{ asset($client->proof_of_billing) }}" class="text-indigo-500 hover:text-indigo-700" target="_blank">View Document</a>
                    @else
                        Not provided
                    @endif
                </p>
            </div>
        </div>

        <div>
            <a href="{{ route('clients.index') }}" class="text-indigo-500 hover:text-indigo-700">
                Back to List
            </a>
        </div>
    </div>
</div>
@endsection