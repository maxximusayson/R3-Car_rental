@extends('layouts.myapp3')
@section('title', 'R3 Garage Car Rental | Login')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-200">
    <div class="flex flex-col md:flex-row bg-white shadow-lg rounded-lg overflow-hidden max-w-4xl w-full">
        <!-- Left Side with Image -->
        <div class="bg-blue-100 p-8 md:w-1/2 flex items-center justify-center">
            <img src="/images/logos/r3.jpg" alt="Illustration" class="max-w-full">
        </div>

        <!-- Right Side with Form -->
        <div class="p-8 w-full md:w-1/2">
            <div class="flex justify-center mb-6">
                <!-- <img src="/images/logos/R3Logo.png" alt="Logo" class="h-24"> -->
            </div>
            <h2 class="text-3xl font-semibold text-center mb-6 text-gray-800" style="font-family: 'Century Gothic', sans-serif;">Sign in to Your Account</h2>

            <form id="loginForm" method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-6">
                    <label for="username" class="block text-sm font-medium text-gray-700" style="font-family: 'Century Gothic', sans-serif;">Username</label>
                    <input type="text" id="username" name="username" value="{{ old('username') }}" placeholder="Enter your username"
                        class="mt-2 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        style="font-family: 'Century Gothic', sans-serif;">
                    @error('username')
                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6 relative">
                    <label for="password" class="block text-sm font-medium text-gray-700" style="font-family: 'Century Gothic', sans-serif;">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password"
                        class="mt-2 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        style="font-family: 'Century Gothic', sans-serif;">
                    <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-600">
                        <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12A3 3 0 119 12a3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.522 5 12 5s8.268 2.943 9.542 7C20.268 16.057 16.478 19 12 19s-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                    @error('password')
                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6 flex items-center justify-between">
                    <div>
                        <input id="remember" type="checkbox" name="remember" class="form-checkbox h-5 w-5 text-blue-600" {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember" class="ml-2 text-sm font-medium text-gray-700" style="font-family: 'Century Gothic', sans-serif;">Remember me</label>
                    </div>
                    <div class="text-sm">
                        <a href="{{ route('password.request') }}" class="text-blue-600 hover:underline" style="font-family: 'Century Gothic', sans-serif;">Forgot Password?</a>
                    </div>
                </div>

                <div class="mb-6">
                    <div id="captcha" class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.sitekey') }}"></div>
                    <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
                    @error('g-recaptcha-response')
                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <button id="loginButton" type="submit" disabled
                    class="w-full px-4 py-3 rounded-lg bg-blue-500 text-white font-semibold hover:bg-blue-600 focus:outline-none focus:bg-blue-300 transition ease-in-out duration-150"
                    style="font-family: 'Century Gothic', sans-serif;">Login</button>
            </form>

            <div class="mt-6 text-sm text-center">
                Don't have an account? <a href="{{ route('register') }}" class="text-blue-600 hover:underline" style="font-family: 'Century Gothic', sans-serif;">Register here</a>
            </div>
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

    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function () {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825a10.05 10.05 0 01-1.875.175C7.477 19 3.732 16.057 2.458 12c.548-1.748 1.655-3.283 3.107-4.408m6.068-2.592c.356-.086.72-.153 1.091-.199M12 9a3 3 0 013 3M12 9a3 3 0 10-3 3M12 9V5m0 0l1.42-1.42M12 5l-1.42-1.42M2.458 12C3.732 7.943 7.522 5 12 5s8.268 2.943 9.542 7-4.268 7-9.542 7z" />
                </svg>
            `; // Change icon to "eye-off"
        } else {
            passwordInput.type = 'password';
            eyeIcon.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12A3 3 0 119 12a3 3 0 016 0zM2.458 12C3.732 7.943 7.522 5 12 5c4.478 0 8.268 2.943 9.542 7-.956 3.05-3.491 5.438-6.701 5.938M12 5v.01" />
                </svg>
            `; // Change icon back to "eye"
        }
    });

    // Remember Me functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Get the username and password from cookies if available
        const username = getCookie('remembered_username');
        const password = getCookie('remembered_password');

        // If the cookies exist, fill in the fields and refresh the cookies
        if (username && password) {
            document.getElementById('username').value = username;
            document.getElementById('password').value = atob(password); // Decode the password
            document.getElementById('remember').checked = true;
            // Refresh the cookies to extend the expiration time
            setCookie('remembered_username', username, 10);
            setCookie('remembered_password', password, 10); // Re-encode to base64 for security
        }

        // On form submit, set the cookies if "Remember Me" is checked
        document.getElementById('loginForm').addEventListener('submit', function() {
            if (document.getElementById('remember').checked) {
                setCookie('remembered_username', document.getElementById('username').value, 10);
                setCookie('remembered_password', btoa(document.getElementById('password').value), 10); // Encode the password
            } else {
                // Clear cookies if "Remember Me" is unchecked
                deleteCookie('remembered_username');
                deleteCookie('remembered_password');
            }
        });
    });

    // Function to set a cookie
    function setCookie(name, value, minutes) {
        const date = new Date();
        date.setTime(date.getTime() + (minutes * 60 * 1000));
        const expires = "; expires=" + date.toUTCString();
        document.cookie = name + "=" + (value || "") + expires + "; path=/";
    }

    // Function to get a cookie
    function getCookie(name) {
        const nameEQ = name + "=";
        const ca = document.cookie.split(';');
        for(let i=0; i<ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) === ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }

    // Function to delete a cookie
    function deleteCookie(name) {
        document.cookie = name + "=; Max-Age=-99999999;";
    }
</script>

@endsection
