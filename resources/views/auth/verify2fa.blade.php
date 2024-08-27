@extends('layouts.myapp3')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-200">
    <div class="bg-white shadow-lg rounded-lg p-8">
        <h2 class="text-2xl font-semibold text-center mb-6">Enter 2FA Code</h2>
        <form method="POST" action="{{ route('verify2fa.post') }}">
            @csrf
            <div class="mb-6">
                <label for="two_factor_code" class="block text-sm font-medium text-gray-700">2FA Code</label>
                <input type="text" id="two_factor_code" name="two_factor_code" placeholder="Enter the 6-digit code"
                    class="mt-2 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                @error('two_factor_code')
                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit"
                class="w-full px-4 py-3 rounded-lg bg-blue-500 text-white font-semibold hover:bg-blue-600 focus:outline-none focus:bg-blue-300 transition ease-in-out duration-150">Verify</button>
        </form>
    </div>
</div>
@endsection
