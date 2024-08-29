@extends('layouts.myapp1')

@section('content')
<div class="container mx-auto mt-10">
    <div class="bg-white dark:bg-gray-900 rounded-lg shadow-lg p-8">
        <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-6">Reservation Details</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <p class="text-lg text-gray-700 dark:text-gray-300"><strong>User:</strong> {{ $reservation->user->name }}</p>
                <p class="text-lg text-gray-700 dark:text-gray-300"><strong>Car:</strong> {{ $reservation->car->brand }} {{ $reservation->car->model }}</p>
            </div>
            <div class="space-y-4">
                <p class="text-lg text-gray-700 dark:text-gray-300"><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($reservation->start_date)->format('Y-m-d') }}</p>
                <p class="text-lg text-gray-700 dark:text-gray-300"><strong>End Date:</strong> {{ \Carbon\Carbon::parse($reservation->end_date)->format('Y-m-d') }}</p>
                <p class="text-lg text-gray-700 dark:text-gray-300"><strong>Status:</strong> 
                    <span class="inline-block px-3 py-1 rounded-full text-white 
                        @if($reservation->status === 'Confirmed') 
                            bg-green-500
                        @elseif($reservation->status === 'Pending')
                            bg-yellow-500
                        @elseif($reservation->status === 'Rejected')
                            bg-red-500
                        @else
                            bg-gray-500
                        @endif">
                        {{ $reservation->status }}
                    </span>
                </p>
                <p class="text-lg text-gray-700 dark:text-gray-300"><strong>Payment Mode:</strong> {{ $reservation->payment_method }}</p>
            </div>
        </div>

        <!-- Admin Action Buttons -->
        @if($reservation->status === 'Pending')
        <div class="mt-8 flex justify-start space-x-4">
            <form action="{{ route('reservations.approve', $reservation->id) }}" method="POST">
                @csrf
                @method('PUT')
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-500 transition duration-300">Approve</button>
            </form>
            <form action="{{ route('reservations.reject', $reservation->id) }}" method="POST">
                @csrf
                @method('PUT')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg shadow hover:bg-red-500 transition duration-300">Reject</button>
            </form>
        </div>
        @endif

        <!-- Display the uploaded IDs -->
        <div class="mt-8">
            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Uploaded Documents</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <p class="text-md text-gray-700 dark:text-gray-300 font-medium">Driver's License:</p>
                    @if($reservation->driver_license)
                        <img src="{{ asset($reservation->driver_license) }}" alt="Driver's License" class="w-full h-auto max-w-xs border border-gray-300 dark:border-gray-700 rounded-lg shadow-md transition-transform transform hover:scale-105 cursor-pointer" onclick="openModal('{{ asset($reservation->driver_license) }}')">
                    @else
                        <p class="text-gray-500 dark:text-gray-400">No driver's license uploaded.</p>
                    @endif
                </div>
                <div>
                    <p class="text-md text-gray-700 dark:text-gray-300 font-medium">Valid ID:</p>
                    @if($reservation->valid_id)
                        <img src="{{ asset($reservation->valid_id) }}" alt="Valid ID" class="w-full h-auto max-w-xs border border-gray-300 dark:border-gray-700 rounded-lg shadow-md transition-transform transform hover:scale-105 cursor-pointer" onclick="openModal('{{ asset($reservation->valid_id) }}')">
                    @else
                        <p class="text-gray-500 dark:text-gray-400">No valid ID uploaded.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Full-Screen Image -->
<div id="imageModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-80 flex items-center justify-center transition-opacity duration-300">
    <span class="absolute top-4 right-4 text-white text-3xl cursor-pointer transition-transform transform hover:scale-125" onclick="closeModal()">&times;</span>
    <img id="modalImage" src="" class="max-w-full max-h-full rounded-lg shadow-lg">
</div>

<script>
function openModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('imageModal').classList.remove('hidden');
    document.getElementById('imageModal').classList.add('flex');
}

function closeModal() {
    document.getElementById('imageModal').classList.add('hidden');
    document.getElementById('imageModal').classList.remove('flex');
    document.getElementById('modalImage').src = ''; // Clear the image source when closing the modal
}
</script>

@endsection
