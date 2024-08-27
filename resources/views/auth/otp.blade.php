@extends('layouts.myapp3')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-r from-blue-100 to-blue-300">
    <div class="flex bg-white shadow-lg rounded-lg overflow-hidden max-w-4xl w-full">
        <!-- Left Side with Image -->
        <div class="bg-blue-100 p-8 w-1/2 flex items-center justify-center">
            <img src="/images/logos/r3.jpg" alt="Illustration" class="max-w-full">
        </div>

        <!-- Right Side with Form -->
        <div class="p-8 w-1/2">
            <h2 class="text-3xl font-semibold text-center mb-6 text-gray-800" style="font-family: 'Century Gothic', sans-serif;">Create Your Account</h2>

            @if (session('success'))
                <div class="alert alert-success text-green-600">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger text-red-600">
                    {{ session('error') }}
                </div>
            @endif

            <form id="registerForm" method="POST" action="{{ route('request.otp') }}">
                @csrf

                <div class="mb-6">
                    <label for="phone" class="block text-sm font-medium text-gray-700" style="font-family: 'Century Gothic', sans-serif;">Phone Number</label>
                    <div class="flex">
                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-100 text-gray-600 sm:text-sm">
                            +63
                        </span>
                        <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                            class="mt-2 block w-full px-4 py-3 border border-gray-300 rounded-r-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            style="font-family: 'Century Gothic', sans-serif;" placeholder="Enter phone number without leading 0">
                    </div>
                    @error('phone')
                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" id="registerButton" 
                    class="w-full bg-blue-500 hover:bg-blue-600 focus:ring focus:ring-blue-400 text-white font-medium rounded-lg text-sm py-3 px-4 transition ease-in-out duration-150"
                    style="font-family: 'Century Gothic', sans-serif;">
                    Register
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
