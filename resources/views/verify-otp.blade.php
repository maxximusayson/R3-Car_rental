@extends('layouts.myapp')

@section('content')

@section('title', 'R3 Garage Car Rental | Verify')

<div class="min-h-screen flex items-center justify-center bg-gradient-to-r from-blue-100 to-blue-300">
    <div class="flex bg-white shadow-lg rounded-lg overflow-hidden max-w-4xl w-full">
        <!-- Left Side with Image -->
        <div class="bg-blue-100 p-8 w-1/2 flex items-center justify-center">
            <img src="/images/logos/r3.jpg" alt="Illustration" class="max-w-full">
        </div>

        <!-- Right Side with OTP Form -->
        <div class="p-8 w-1/2">
            <div class="flex justify-center mb-6">
                <!-- Placeholder for Logo if needed -->
            </div>
            <h2 class="text-3xl font-semibold text-center mb-6 text-gray-800" style="font-family: 'Century Gothic', sans-serif;">SMS Verification Code</h2>

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
            
            <!-- Display a success message after account creation -->
            @if(session('account_created'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                    {{ session('account_created') }}
                </div>
            @endif

            <form action="{{ route('register.verify-otp') }}" method="POST">
                @csrf

                <!-- Hidden field to pass the phone number for verification -->
                <input type="hidden" name="phone" value="{{ old('phone', session('phone')) }}">

                <div class="mb-4">
                    <label for="otp" class="block text-sm font-medium text-gray-700 mb-2" style="font-family: 'Century Gothic', sans-serif;">Enter OTP:</label>
                    <input type="text" id="otp" name="otp" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-blue-400"
                        placeholder="Enter your OTP">
                    @error('otp')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" 
                    class="w-full bg-blue-500 hover:bg-blue-600 focus:ring focus:ring-blue-400 text-white font-medium rounded-lg text-sm py-2 px-4"
                    style="font-family: 'Century Gothic', sans-serif;">
                    Verify OTP
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

