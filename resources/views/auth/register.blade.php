@extends('layouts.myapp')

@section('content')
<div class="flex justify-center items-center min-h-screen bg-gradient-to-r from-blue-100 to-blue-300">
    <div class="bg-white shadow-lg rounded-lg p-8 max-w-md w-full">
        <div class="flex justify-center mb-6">
            <img src="/images/logos/R3Logo.png" alt="Logo" class="h-36">
        </div>
        <h2 class="text-3xl font-semibold text-center mb-6 text-gray-800" style="font-family: 'Century Gothic', sans-serif;">Register</h2>

        <form id="registerForm" method="POST" action="{{ route('request.otp') }}">
            @csrf

            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700" style="font-family: 'Century Gothic', sans-serif;">Name</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}"
                    class="mt-2 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    style="font-family: 'Century Gothic', sans-serif;">
                @error('name')
                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="email" class="block text-sm font-medium text-gray-700" style="font-family: 'Century Gothic', sans-serif;">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                    class="mt-2 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    style="font-family: 'Century Gothic', sans-serif;">
                @error('email')
                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

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

            <!-- Password Field with Show/Hide Functionality -->
            <div class="mb-6 relative">
                <label for="password" class="block text-sm font-medium text-gray-700" style="font-family: 'Century Gothic', sans-serif;">Password</label>
                <div class="relative">
                    <input type="password" id="password" name="password"
                        class="mt-2 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        style="font-family: 'Century Gothic', sans-serif;">
                    <button type="button" id="togglePassword" 
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-600" aria-label="Toggle Password Visibility">
                        <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.522 5 12 5c4.478 0 8.268 2.943 9.542 7-.174.735-.42 1.441-.732 2.108M15.732 19.646A9.98 9.98 0 0112 19c-4.478 0-8.268-2.943-9.542-7-.175-.735-.421-1.441-.733-2.108" />
                        </svg>
                    </button>
                </div>
                @error('password')
                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="password-requirements mt-2">
                <p class="text-sm">Password must include:</p>
                <ul class="list-disc pl-5 text-sm">
                    <li id="uppercase" class="text-gray-600">At least one uppercase letter <span class="status-icon"></span></li>
                    <li id="number" class="text-gray-600">At least one number <span class="status-icon"></span></li>
                    <li id="special" class="text-gray-600">At least one special character <span class="status-icon"></span></li>
                    <li id="length" class="text-gray-600">Minimum 8 characters <span class="status-icon"></span></li>
                </ul>
            </div>

            <style>
                .status-icon::before {
                    content: '✖';
                    color: red;
                    margin-left: 10px;
                }
                .status-icon.valid::before {
                    content: '✔';
                    color: green;
                }
                #eyeIcon {
                    cursor: pointer;
                }
            </style>

            <div class="mb-6">
                <label for="password-confirm" class="block text-sm font-medium text-gray-700" style="font-family: 'Century Gothic', sans-serif;">Confirm Password</label>
                <input type="password" id="password-confirm" name="password_confirmation"
                    class="mt-2 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    style="font-family: 'Century Gothic', sans-serif;">
            </div>

            <div class="mb-6">
                <div id="captcha" class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.sitekey') }}"></div>
                <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
                @error('g-recaptcha-response')
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

    // Password validation script
    document.getElementById('password').addEventListener('input', function() {
        const password = this.value;
        const uppercase = /[A-Z]/.test(password);
        const number = /[0-9]/.test(password);
        const special = /[!@#$%^&*(),.?":{}|<>]/.test(password);
        const length = password.length >= 8;
        document.getElementById('uppercase').querySelector('.status-icon').classList.toggle('valid', uppercase);

        document.getElementById('number').classList.toggle('text-green-500', number);
        document.getElementById('number').classList.toggle('text-gray-600', !number);
        document.getElementById('number').querySelector('.status-icon').classList.toggle('valid', number);

        document.getElementById('special').classList.toggle('text-green-500', special);
        document.getElementById('special').classList.toggle('text-gray-600', !special);
        document.getElementById('special').querySelector('.status-icon').classList.toggle('valid', special);

        document.getElementById('length').classList.toggle('text-green-500', length);
        document.getElementById('length').classList.toggle('text-gray-600', !length);
        document.getElementById('length').querySelector('.status-icon').classList.toggle('valid', length);
    });

    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordField = document.getElementById('password');
        const type = passwordField.type === 'password' ? 'text' : 'password';
        passwordField.type = type;
        this.querySelector('svg').classList.toggle('h-6', type === 'password');
        this.querySelector('svg').classList.toggle('h-6', type === 'text');
    });
</script>
@endsection

