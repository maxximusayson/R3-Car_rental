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

        <!-- Display the uploaded IDs -->
        <div class="mt-6">
            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Uploaded Documents</h3>
            <div class="flex gap-4 mt-4">
                <div>
                    <p class="text-gray-600 dark:text-gray-400 font-medium">Driver's License:</p>
                    @if($reservation->driver_license)
                        <img src="{{ asset($reservation->driver_license) }}" alt="Driver's License" class="w-48 h-auto border border-gray-300 dark:border-gray-600 rounded-md shadow-sm cursor-pointer" onclick="openModal('{{ asset($reservation->driver_license) }}')">
                    @else
                        <p class="text-gray-500 dark:text-gray-400">No driver's license uploaded.</p>
                    @endif
                </div>
                <div>
                    <p class="text-gray-600 dark:text-gray-400 font-medium">Valid ID:</p>
                    @if($reservation->valid_id)
                        <img src="{{ asset($reservation->valid_id) }}" alt="Valid ID" class="w-48 h-auto border border-gray-300 dark:border-gray-600 rounded-md shadow-sm cursor-pointer" onclick="openModal('{{ asset($reservation->valid_id) }}')">
                    @else
                        <p class="text-gray-500 dark:text-gray-400">No valid ID uploaded.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Full-Screen Image -->
<div id="imageModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-75 flex items-center justify-center">
    <span class="absolute top-4 right-4 text-white text-2xl cursor-pointer" onclick="closeModal()">&times;</span>
    <img id="modalImage" src="" class="max-w-full max-h-full">
</div>

<script>
function openModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('imageModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('imageModal').classList.add('hidden');
    document.getElementById('modalImage').src = ''; // Clear the image source when closing the modal
}
</script>

@endsection
