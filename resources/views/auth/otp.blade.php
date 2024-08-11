@extends('layouts.myapp')

@section('content')
<div class="flex justify-center items-center min-h-screen bg-gray-100">
    <div class="bg-white border border-gray-300 rounded-lg shadow-lg p-8 max-w-md w-full">
        <h2 class="text-2xl font-semibold mb-6 flex justify-center">Verify OTP</h2>
        <form id="otp-form" method="POST" action="{{ route('verify.otp') }}">
            @csrf
            <div class="mb-4">
                <label for="otp" class="block text-sm font-medium text-gray-700 mb-2">Enter OTP:</label>
                <input type="text" id="otp" name="otp" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-blue-400">
                @error('otp')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit" 
                class="w-full bg-blue-500 hover:bg-blue-600 focus:ring focus:ring-blue-400 text-white font-medium rounded-lg text-sm py-2 px-4">
                Verify OTP
            </button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('otp-form');

    form.addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission

        const otp = document.getElementById('otp').value;

        fetch('{{ route('verify.otp') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ otp })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('OTP verified successfully!');
                window.location.href = '/success-page'; // Redirect on success
            } else {
                alert('Verification failed: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred during verification.');
        });
    });
});
</script>
@endsection
