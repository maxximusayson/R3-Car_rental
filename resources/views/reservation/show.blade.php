@extends('layouts.myapp1')

@section('content')
<div class="container mx-auto mt-8">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Reservation Details</h2>
        <div class="mt-4">
            <p class="text-gray-600 dark:text-gray-400"><strong>User:</strong> {{ $reservation->user->name }}</p>
            <p class="text-gray-600 dark:text-gray-400"><strong>Email:</strong> {{ $reservation->user->email }}</p>
            <p class="text-gray-600 dark:text-gray-400"><strong>Car:</strong> {{ $reservation->car->brand }} {{ $reservation->car->model }}</p>
            <p class="text-gray-600 dark:text-gray-400"><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($reservation->start_date)->format('Y-m-d') }}</p>
            <p class="text-gray-600 dark:text-gray-400"><strong>End Date:</strong> {{ \Carbon\Carbon::parse($reservation->end_date)->format('Y-m-d') }}</p>
            <p class="text-gray-600 dark:text-gray-400"><strong>Status:</strong> {{ $reservation->status }}</p>
        </div>
    </div>
</div>
@endsection