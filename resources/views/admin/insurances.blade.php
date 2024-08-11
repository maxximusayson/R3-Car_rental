@extends('layouts.myapp1')

@section('content')

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

<div class="flex h-screen bg-gray-50">
    <div class="flex flex-wrap justify-between items-center mx-auto max-w-screen-xl w-full">
        <div class="flex flex-col flex-1 w-full">
            <main class="h-full overflow-y-auto">
                <div class="container px-6 mx-auto grid gap-6 mb-32 mt-16">

                    <!-- Cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                        <!-- Card -->
                        <div class="flex items-center p-4 bg-white rounded-lg shadow-md">
                            <div class="p-3 mr-4 bg-blue-400 rounded-full text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                                    <path d="M135.2 117.4L109.1 192H402.9l-26.1-74.6C372.3 104.6 360.2 96 346.6 96H165.4c-13.6 0-25.7 8.6-30.2 21.4zM39.6 196.8L74.8 96.3C88.3 57.8 124.6 32 165.4 32H346.6c40.8 0 77.1 25.8 90.6 64.3l35.2 100.5c23.2 9.6 39.6 32.5 39.6 59.2V400v48c0 17.7-14.3 32-32 32H448c-17.7 0-32-14.3-32-32V400H96v48c0 17.7-14.3 32-32 32H32c-17.7 0-32-14.3-32-32V400 256c0-26.7 16.4-49.6 39.6-59.2zM128 288a32 32 0 1 0 -64 0 32 32 0 1 0 64 0zm288 32a32 32 0 1 0 0-64 32 32 0 1 0 0 64z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-gray-500">
                                    Total Cars:
                                </p>
                                <p class="font-semibold">
                                    {{ $cars->count() }}
                                </p>
                            </div>
                        </div>

                        <!-- Card -->
                        <div class="flex items-center p-4 bg-white rounded-lg shadow-md">
                            <div class="p-3 mr-4 bg-green-400 rounded-full text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512">
                                    <path d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-gray-500">
                                    Assured Cars
                                </p>
                                <p class="font-semibold">
                                    {{ $cars->where('insu_id', '!=', null)->count() }}
                                </p>
                            </div>
                        </div>

                        <!-- Card -->
                        <div class="flex items-center p-4 bg-white rounded-lg shadow-md">
                            <div class="p-3 mr-4 bg-red-500 rounded-full text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                                    <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zm0-384c13.3 0 24 10.7 24 24V264c0 13.3-10.7 24-24 24s-24-10.7-24-24V152c0-13.3 10.7-24 24-24zM224 352a32 32 0 1 1 64 0 32 32 0 1 1 -64 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-gray-500">
                                    Non Assured Cars
                                </p>
                                <p class="font-semibold">
                                    {{ $cars->where('insu_id', null)->count() }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Call to Action Button -->
                    <div class="mx-auto max-w-screen-xl">
                        <a href="{{ route('insurances.create') }}">
                            <button class="flex justify-center items-center bg-blue-500 p-3 rounded-md text-white hover:bg-blue-600">
                                <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                    <path d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z"/>
                                </svg>
                                <span class="font-bold">Assure a new car</span>
                            </button>
                        </a>
                    </div>

                    <!-- Section Divider -->
                    <div class="flex justify-center my-8">
                        <hr class="w-1/4 border-t-2 border-blue-500">
                        <p class="mx-4 font-semibold text-gray-600">Cars</p>
                        <hr class="w-1/4 border-t-2 border-blue-500">
                    </div>

                    <!-- Table -->
                    <div class="w-full overflow-hidden rounded-lg shadow-md">
                        <div class="w-full overflow-x-auto">
                            <table class="w-full whitespace-no-wrap">
                                <thead>
                                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase bg-gray-50">
                                        <th class="px-4 py-3">Car</th>
                                        <th class="px-4 py-3">Insurance Company</th>
                                        <th class="px-4 py-3">Type</th>
                                        <th class="px-4 py-3">Start Date</th>
                                        <th class="px-4 py-3">End Date</th>
                                        <th class="px-4 py-3">Duration</th>
                                        <th class="px-4 py-3">Price</th>
                                        <th class="px-4 py-3">Status</th>
                                        <th class="px-4 py-3">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y">
                                    @foreach ($insurances as $insurance)
                                        <tr class="text-gray-700">
                                            <td class="px-4 py-3">
                                                <div class="flex items-center">
                                                    <div class="hidden w-8 h-8 mr-3 rounded-full md:block">
                                                        <img class="object-cover w-full h-full" src="{{ $insurance->car->image }}" alt="{{ $insurance->car->brand }} {{ $insurance->car->model }}">
                                                    </div>
                                                    <div>
                                                        <p class="font-semibold">{{ $insurance->car->brand }} {{ $insurance->car->model }}</p>
                                                        <p class="text-xs text-gray-600">{{ $insurance->car->engine }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-sm">
                                                {{ $insurance->company }}
                                            </td>
                                            <td class="px-4 py-3 text-sm">
                                                {{ $insurance->type }}
                                            </td>
                                            <td class="px-4 py-3 text-sm">
                                                {{ $insurance->start_date }}
                                            </td>
                                            <td class="px-4 py-3 text-sm">
                                                {{ $insurance->end_date }}
                                            </td>
                                            <td class="px-4 py-3 text-sm">
                                                @if ($insurance->end_date > $insurance->start_date)
                                                    {{ \Carbon\Carbon::parse($insurance->end_date)->diffInDays(\Carbon\Carbon::parse($insurance->start_date)) }} days
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-sm">
                                                {{ $insurance->price }} ₱
                                            </td>
                                            <td class="px-4 py-3 text-sm">
                                                <span class="rounded-lg px-2 py-1 {{ $insurance->status === 'Active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                                    {{ $insurance->status }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-sm">
                                                <form action="{{ route('insurances.destroy', ['insurance' => $insurance->id]) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:underline">Remove</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div class="flex justify-center my-8">
                        {{ $insurances->links('pagination::tailwind') }}
                    </div>

                </div>
            </main>
        </div>
    </div>
</div>
@endsection
