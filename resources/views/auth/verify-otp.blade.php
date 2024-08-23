@extends('layouts.myapp')

@section('content')
<div class="flex justify-center items-center min-h-screen bg-gray-100">
    <div class="bg-white border border-gray-300 rounded-lg shadow-lg p-8 max-w-md w-full">
        <h2 class="text-2xl font-semibold mb-6 flex justify-center">Verify OTP</h2>
        
        <!-- Display a success message if the OTP was sent successfully -->
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif
        
        <!-- Display an error message if something went wrong -->
        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('verify.otp') }}">
            @csrf

            <!-- Hidden field to pass the phone number for verification -->
            <input type="hidden" name="phone" value="{{ old('phone', session('phone')) }}">

            <div class="mb-4">
                <label for="otp" class="block text-sm font-medium text-gray-700 mb-2">Enter OTP:</label>
                <input type="text" id="otp" name="otp" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-blue-400"
                    placeholder="Enter your OTP">
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
@endsection
