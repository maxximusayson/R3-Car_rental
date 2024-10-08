@extends('layouts.myapp3')
@section('title', 'R3 Garage Car Rental | Register')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-200">
    <div class="flex flex-col md:flex-row bg-white shadow-xl rounded-lg overflow-hidden max-w-4xl w-full">
        <!-- Left Side with Image -->
        <div class="bg-blue-100 p-8 md:w-1/2 w-full flex items-center justify-center">
            <img src="/images/logos/r3.jpg" alt="Illustration" class="max-w-full rounded-lg shadow-md">
        </div>

        <!-- Right Side with Form -->
        <div class="p-8 w-full md:w-1/2 flex flex-col justify-center">
            <div class="flex justify-center mb-6">
                <!-- Logo can go here if needed -->
            </div>
            <h2 class="text-4xl font-extrabold text-center mb-6 text-gray-800" style="font-family: 'Century Gothic', sans-serif;">Create Your Account</h2>

            <form id="registerForm" method="POST" action="{{ route('request.otp') }}">
                @csrf

                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700" style="font-family: 'Century Gothic', sans-serif;">Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}"placeholder="Enter your full name"
                        class="mt-2 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        style="font-family: 'Century Gothic', sans-serif;">
                    @error('name')
                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Enter your email"
                        class="mt-2 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        placeholder="" required>
                    @error('email')
                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="username" class="block text-sm font-medium text-gray-700" style="font-family: 'Century Gothic', sans-serif;">Username</label>
                    <input type="text" id="username" name="username" value="{{ old('username') }}" placeholder="Enter your username"
                        class="mt-2 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        style="font-family: 'Century Gothic', sans-serif;">
                    @error('username')
                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                        <label for="phone_number" class="block text-sm font-medium text-gray-700" style="font-family: 'Century Gothic', sans-serif;">Phone Number</label>
                        <div class="flex">
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-100 text-gray-600 sm:text-sm">
                                +63
                            </span>
                            <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" placeholder="Enter your phone number"
                                class="mt-2 block w-full px-4 py-3 border border-gray-300 rounded-r-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                style="font-family: 'Century Gothic', sans-serif;"
                                placeholder="Enter phone number without leading 0"
                                pattern="\d*" 
                                inputmode="numeric"
                                maxlength="10"
                                oninput="validatePhoneNumber(this)"
                                onkeypress="return isNumberKey(event)">
                        </div>
                        @error('phone_number')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>


                <script>
                    function validatePhoneNumber(input) {
                        input.value = input.value.replace(/[^0-9]/g, '');
                    }

                    function isNumberKey(event) {
                        const charCode = event.which ? event.which : event.keyCode;
                        if (charCode !== 8 && charCode !== 9 && charCode !== 37 && charCode !== 39 && (charCode < 48 || charCode > 57)) {
                            event.preventDefault();
                            return false;
                        }
                        return true;
                    }
                </script>

                <div class="mb-6 relative">
                    <label for="password" class="block text-sm font-medium text-gray-700" style="font-family: 'Century Gothic', sans-serif;">Password</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" placeholder="Enter your password"
                            class="mt-2 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
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

                <div class="password-requirements mt-2 mb-6">
                    <p class="text-sm text-gray-700">Password must include:</p>
                    <ul class="list-none pl-0 space-y-1">
                        <li id="uppercase" class="password-requirement">At least one uppercase letter</li>
                        <li id="number" class="password-requirement">At least one number</li>
                        <li id="special" class="password-requirement">At least one special character</li>
                        <li id="length" class="password-requirement">Minimum 8 characters</li>
                    </ul>
                </div>

                <div class="mb-6 relative">
                    <label for="password-confirm" class="block text-sm font-medium text-gray-700" style="font-family: 'Century Gothic', sans-serif;">Confirm Password</label>
                    <div class="relative">
                        <input type="password" id="password-confirm" name="password_confirmation" placeholder="re-type your password"
                            class="mt-2 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            style="font-family: 'Century Gothic', sans-serif;">
                        <button type="button" id="togglePasswordConfirm" 
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-600" aria-label="Toggle Password Visibility">
                            <svg id="eyeIconConfirm" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.522 5 12 5c4.478 0 8.268 2.943 9.542 7-.174.735-.42 1.441-.732 2.108M15.732 19.646A9.98 9.98 0 0112 19c-4.478 0-8.268-2.943-9.542-7-.175-.735-.421-1.441-.733-2.108" />
                            </svg>
                        </button>
                    </div>
                </div>

                <script>
                    document.getElementById('togglePasswordConfirm').addEventListener('click', function() {
                        const passwordField = document.getElementById('password-confirm');
                        const type = passwordField.type === 'password' ? 'text' : 'password';
                        passwordField.type = type;

                        // Toggle the eye icon based on the visibility state
                        const eyeIcon = document.getElementById('eyeIconConfirm');
                        eyeIcon.classList.toggle('text-gray-600');
                        eyeIcon.classList.toggle('text-blue-600');
                    });
                </script>

                <div class="mb-6">
                    <div id="captcha" class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.sitekey') }}"></div>
                    <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
                    @error('g-recaptcha-response')
                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" id="registerButton" 
                    class="w-full bg-blue-600 hover:bg-blue-700 focus:ring-2 focus:ring-blue-400 text-white font-semibold rounded-lg text-sm py-3 px-4 transition ease-in-out duration-200"
                    style="font-family: 'Century Gothic', sans-serif;">
                    Register
                </button>
            </form>
        </div>
    </div>
</div>

<style>
    /* Password requirement styles */
    .password-requirement {
        transition: opacity 0.5s ease, transform 0.5s ease;
        font-size: 0.875rem; /* Small text */
        color: red; /* Default color is red */
    }

    .password-requirement.valid {
        opacity: 0; /* Fade out */
        transform: translateY(-10px); /* Optional: Slight upward movement */
        color: green; /* Turns green when valid */
        display: none; /* Hide when valid */
    }

    #eyeIcon {
        cursor: pointer;
    }
    .password-requirements {
    transition: opacity 0.5s ease, transform 0.5s ease;
}

.password-requirements.hidden {
    opacity: 0; /* Fade out */
    transform: translateY(-10px); /* Optional: Slight upward movement */
}
.password-requirement {
    transition: opacity 0.5s ease, transform 0.5s ease;
}

.password-requirement.valid {
    opacity: 0; /* Fade out */
    transform: translateY(-10px); /* Optional: Slight upward movement */
}

    /* Responsive Layout for smaller screens */
    @media (max-width: 768px) {
        .flex-col {
            flex-direction: column;
        }

        .p-8 {
            padding: 16px;
        }

        .max-w-4xl {
            max-width: 100%;
        }

        .w-full {
            width: 100%;
        }

        .md:w-1/2 {
            width: 100%;
        }
    }
</style>

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

    // Regular expressions to check each requirement
    const uppercase = /[A-Z]/.test(password);
    const number = /\d/.test(password);
    const special = /[^A-Za-z0-9]/.test(password);
    const length = password.length >= 8;

    // Toggle classes based on password validation
    toggleValidation('uppercase', uppercase);
    toggleValidation('number', number);
    toggleValidation('special', special);
    toggleValidation('length', length);

    // Check if all requirements are met
    if (uppercase && number && special && length) {
        document.getElementById('passwordRequirements').classList.add('hidden');
    } else {
        document.getElementById('passwordRequirements').classList.remove('hidden');
    }
});

function toggleValidation(elementId, isValid) {
    const element = document.getElementById(elementId);
    if (isValid) {
        element.classList.add('valid');
    } else {
        element.classList.remove('valid');
    }
}

    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordField = document.getElementById('password');
        const type = passwordField.type === 'password' ? 'text' : 'password';
        passwordField.type = type;
    });
</script>

@endsection
