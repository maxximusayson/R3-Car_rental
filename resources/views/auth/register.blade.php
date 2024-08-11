@extends('layouts.myapp')

@section('content')
<div class="flex justify-center items-center min-h-screen bg-gray-100">
    <div class="bg-white border border-gray-300 rounded-lg shadow-lg p-8 max-w-md w-full">
        <div class="flex justify-center mb-4">
            <img src="/images/logos/logor3.png" alt="Logo" class="h-40">
        </div>
        <h2 class="text-2xl font-semibold mb-6 flex justify-center">Register</h2>
        <form id="registerForm" method="POST" action="{{ route('request.otp') }}">
            @csrf

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name:</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-blue-400">
                @error('name')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address:</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-blue-400">
                @error('email')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number:</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-blue-400">
                @error('phone')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password:</label>
                <input type="password" id="password" name="password"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-blue-400">
                @error('password')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password-confirm" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password:</label>
                <input type="password" id="password-confirm" name="password_confirmation"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-blue-400">
            </div>

            <div class="mb-4">
                <div id="captcha" class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.sitekey') }}"></div>
                <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
                @error('g-recaptcha-response')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" id="registerButton" 
                class="w-full bg-blue-500 hover:bg-blue-600 focus:ring focus:ring-blue-400 text-white font-medium rounded-lg text-sm py-2 px-4">
                Register
            </button>
        </form>
    </div>
</div>

<script src="https://www.google.com/recaptcha/api.js?onload=onRecaptchaLoad&render=explicit" async defer></script>
<script>
    var captchaWidget;

    function onRecaptchaLoad() {
        captchaWidget = grecaptcha.render('captcha', {
            'sitekey': '{{ config('services.recaptcha.sitekey') }}'
        });
    }

    document.getElementById('registerForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const captchaResponse = grecaptcha.getResponse(captchaWidget);
        document.getElementById('g-recaptcha-response').value = captchaResponse;

        if (captchaResponse.length === 0) {
            alert('Please complete the CAPTCHA.');
            return;
        }

        this.submit();
    });
</script>
@endsection
