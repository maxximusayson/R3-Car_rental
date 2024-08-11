@extends('layouts.myapp')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="bg-white shadow-md rounded-lg p-8 max-w-md w-full">
        <div class="flex justify-center mb-4">
            <img src="/images/logos/logor3.png" alt="Logo" class="h-40">
        </div>
        <h2 class="text-2xl font-semibold text-center mb-6">LOGIN</h2>

        <form id="loginForm" method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Enter your email"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                @error('email')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                @error('password')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4 flex items-center">
                <input id="remember" type="checkbox" name="remember" class="form-checkbox">
                <label for="remember" class="ml-2 text-sm font-medium text-gray-700">Remember me</label>
            </div>

            <!-- <div class="flex items-center mb-4">
                <input id="remember" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <label for="remember" class="ml-2 block text-sm text-gray-700">Remember me</label>
            </div> -->

            <div class="mb-4">
                <div id="captcha" class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.sitekey') }}"></div>
                <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
                @error('g-recaptcha-response')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <button id="loginButton" type="submit" disabled
                class="w-full px-4 py-2 rounded-md bg-blue-500 text-white hover:bg-blue-600 focus:outline-none focus:bg-blue-300">Login</button>

            @if (Route::has('password.request'))
            <div class="text-sm mt-2">
                <a href="{{ route('password.request') }}" class="text-blue-600 hover:underline">Forgot Your Password?</a>
            </div>
            @endif
        </form>

        <div class="mt-4 text-sm">
            Don't have an account? <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Register here</a>
        </div>
    </div>
</div>

<script src="https://www.google.com/recaptcha/api.js?onload=onRecaptchaLoad&render=explicit" async defer></script>
<script>
    var captchaWidget;

    function onRecaptchaLoad() {
        captchaWidget = grecaptcha.render('captcha', {
            'sitekey': '{{ config('services.recaptcha.sitekey') }}',
            'callback': onCaptchaSuccess,
            'expired-callback': onCaptchaExpired
        });
    }

    function onCaptchaSuccess(response) {
        document.getElementById('g-recaptcha-response').value = response;
        document.getElementById('loginButton').removeAttribute('disabled');
    }

    function onCaptchaExpired() {
        document.getElementById('g-recaptcha-response').value = '';
        document.getElementById('loginButton').setAttribute('disabled', 'disabled');
    }

    function onLoginSubmit(event) {
        if (document.getElementById('g-recaptcha-response').value === '') {
            event.preventDefault(); // Prevent form submission if reCAPTCHA not completed
            alert('Please complete the reCAPTCHA to proceed.');
        }
    }

    document.getElementById('loginForm').addEventListener('submit', onLoginSubmit);
</script>
@endsection
