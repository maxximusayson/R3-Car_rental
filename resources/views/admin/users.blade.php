@extends('layouts.myapp1')

@section('content')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCDg7pLs7iesp74vQ-KSEjnFJW3BKhVq7k"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<!-- Clock -->
<div id="clock" class="text-gray-900 dark:text-gray-300 text-lg font-semibold absolute top-4 right-4">
    <span id="time"></span>
</div>

<div class="mx-auto max-w-screen-xl">
    {{-- Flash message for deletion success --}}
    @if (session('success'))
        <div class="bg-green-500 text-white p-4 rounded-lg mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- Clients Section --}}
    <div id="reservations" class="mt-12">
        <div class="flex items-center justify-center">
            <p class="my-2 mx-8 p-2 font-car font-bold text-gray-600 text-lg dark:text-gray-300">Customer Users</p>
        </div>
    </div>

    <div class="w-full overflow-hidden rounded-lg shadow-md mb-12">
        <div class="w-full overflow-x-auto">
           
            <table class="w-full table-auto text-center bg-white dark:bg-gray-800 rounded-lg">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-gray-700 uppercase bg-gray-100 dark:bg-gray-900 dark:text-gray-300">
                        <th class="px-6 py-4">Profile</th>
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">Joined At</th>
                        <th class="px-6 py-4">Reservations</th>
                        <th class="px-6 py-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($clients as $client)
                        <tr class="text-gray-900 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                            <!-- Profile Picture -->
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center">
                                    <img src="{{ asset($client->profile_picture_url ?: 'images/default-profile.png') }}" alt="{{ $client->name }}" class="h-10 w-10 rounded-full object-cover">
                                </div>
                            </td>
                            <!-- Name -->
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-200">
                                    {{ $client->name }}
                                </div>
                            </td>
                            <!-- Email -->
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $client->email }}
                            </td>
                            <!-- Joined Date -->
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $client->created_at->format('Y-m-d') }}
                            </td>
                            <!-- Reservations -->
                            <td class="px-6 py-4 text-sm">
                                {{ $client->reservations_count > 0 ? $client->reservations_count : 'No reservations' }}
                            </td>
                            <!-- Actions -->
                            <td class="px-6 py-4">
                                <div class="flex justify-center space-x-4">
                                    <a href="{{ route('clients.edit', $client->id) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200">
                                        Edit
                                    </a>
                                    <form action="{{ route('clients.destroy', $client->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-200" onclick="return confirm('Are you sure you want to delete this client?');">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                No customers found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
