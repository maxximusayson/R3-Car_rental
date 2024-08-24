@extends('layouts.myapp1')

@section('content')

<!-- Clock -->
<div id="clock" class="text-gray-900 dark:text-gray-300 text-lg font-semibold absolute top-4 right-4">
    <span id="time"></span>
</div>

<div class="mx-auto max-w-screen-xl">
    {{-- Clients Section --}}
    <div id="reservations" class="mt-12">
        <div class="flex items-center justify-center">
            <p class="my-2 mx-8 p-2 font-car font-bold text-gray-600 text-lg dark:text-gray-300">Customer Users</p>
        </div>
    </div>

    <div class="w-full overflow-hidden rounded-lg shadow-md mb-12">
        <div class="w-full overflow-x-auto">
            <div class="flex justify-between items-center mb-4">
                <!-- Export Button -->
                <form action="{{ route('clients.export') }}" method="GET">
    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg shadow hover:bg-blue-700">
        Export to CSV
    </button>
</form>
                <!-- Import Form -->
                <form action="{{ route('clients.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <label for="csv_file" class="px-4 py-2 bg-green-500 text-white rounded-lg shadow hover:bg-green-700 cursor-pointer">
                        Import CSV
                    </label>
                    <input type="file" name="csv_file" id="csv_file" accept=".csv" class="hidden">
                    <button type="submit" class="hidden">Upload</button>
                </form>
            </div>
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
                                    @if($client->profile_picture_url)
                                        <img src="{{ asset($client->profile_picture_url) }}" alt="{{ $client->name }}" class="h-10 w-10 rounded-full object-cover">
                                    @else
                                        <img src="{{ asset('images/default-profile.png') }}" alt="Default Profile Picture" class="h-10 w-10 rounded-full object-cover">
                                    @endif
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
                                    @if ($client->reservations_count > 0)
                                        {{ $client->reservations_count }}
                                    @else
                                        <span class="text-gray-500">No reservations</span>
                                    @endif
                                </td>

                            <!-- Actions -->
                            <td class="px-6 py-4">
                                <div class="flex justify-center space-x-4">
                                    <a href="" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200">
                                        Edit
                                    </a>
                                    <form action="" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-200">
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
