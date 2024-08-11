@extends('layouts.myapp1')
@section('content')

  <!-- Clock -->
  <div id="clock" class="text-gray-900 dark:text-gray-300 text-lg font-semibold absolute top-4 right-4">
        <span id="time"></span>
    </div>

<div class="mx-auto max-w-screen-xl">
    {{-- clients --}}
    <div id="reservations" class="mt-12">
        <div class="flex items-center justify-center">
            <p class="my-2 mx-8 p-2 font-car font-bold text-gray-600 text-lg">CUSTOMERS</p>
        </div>
    </div>

    <div class="w-full overflow-hidden rounded-lg shadow-xs mb-12">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap overflow-scroll table-auto text-center">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <th class="text-center px-4 py-3">Customer</th>
                        <th class="text-center px-4 py-3 w-48">Name</th>
                        <th class="text-center px-4 py-3 w-24">Email</th>
                        <th class="text-center px-4 py-3 w-24">Joined at</th>
                        <th class="text-center w-56 px-4 py-3">Reservations</th>
                        <th class="text-center px-4 py-3 w-26">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    @forelse ($clients as $client)
                        <tr class="text-gray-700 dark:text-gray-400">
                            <td class="px-4 py-3">Customer {{ $loop->iteration }}</td>
                            <td class="px-4 py-3 name-cell" data-full-name="{{ $client->name }}">
                                {{ $client->name }}
                            </td>
                            <td class="px-4 py-3">{{ $client->email }}</td>
                            <td class="px-4 py-3">{{ $client->created_at->format('Y-m-d') }}</td>
                            <td class="px-4 py-3">{{ $client->reservations_count }}</td>
                            <td class="px-4 py-3">
                                <a href="{{ route('clients.show', $client->id) }}" class="text-blue-500 hover:text-blue-700">Details</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">
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


