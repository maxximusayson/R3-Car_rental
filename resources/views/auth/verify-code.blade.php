@extends('layouts.myapp3')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-200">
    <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-md">
        <h2 class="text-3xl font-semibold text-center mb-6 text-gray-800">Verification Code</h2>
        @if (session('message'))
            <div class="bg-green-100 text-green-700 p-4 rounded mb-4">{{ session('message') }}</div>
        @endif
        <form method="POST" action="{{ route('password.verify.code') }}">
            @csrf
            <div class="mb-4">
            <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number</label>
            <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" required autofocus
            class="mt-2 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="mb-4">
                <label for="reset_code" class="block text-sm font-medium text-gray-700">Verification Code</label>
                <input type="text" id="reset_code" name="reset_code" placeholder="Enter the code you received"
                    class="mt-2 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                @error('reset_code')
                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit"
                class="w-full px-4 py-3 rounded-lg bg-blue-500 text-white font-semibold hover:bg-blue-600 focus:outline-none focus:bg-blue-300 transition ease-in-out duration-150">
                Verify Code
            </button>
        </form>
    </div>
</div>
@endsection
