@extends('layouts.myapp3')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-center mb-6 text-gray-800">Two-Factor Authentication</h2>
        <p class="text-sm text-center text-gray-500 mb-6">
            Select how you would like to receive one-time passwords (OTPs):
        </p>

        <!-- Step 1: Select Method (SMS or Email) -->
        <div id="select-method">
            <form id="method-form" method="POST" action="{{ route('2fa.send') }}">
                @csrf
                <div class="mb-6 space-y-4">
                    <div class="relative border border-gray-300 rounded-lg p-4 cursor-pointer hover:border-blue-500 transition">
                        <input type="radio" id="sms" name="2fa_method" value="sms" class="absolute top-4 right-4" checked>
                        <label for="sms" class="block text-sm font-medium text-gray-700">
                            <span class="block font-semibold text-gray-800">SMS</span>
                            <span class="block text-sm text-gray-500">Receive OTP via SMS</span>
                        </label>
                    </div>
                    <div class="relative border border-gray-300 rounded-lg p-4 cursor-pointer hover:border-blue-500 transition">
                        <input type="radio" id="email" name="2fa_method" value="email" class="absolute top-4 right-4">
                        <label for="email" class="block text-sm font-medium text-gray-700">
                            <span class="block font-semibold text-gray-800">Email</span>
                            <span class="block text-sm text-gray-500">Receive OTP via email</span>
                        </label>
                    </div>
                </div>

                <button type="submit" id="method-submit"
                    class="w-full py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                    Continue
                </button>
            </form>
        </div>

        <!-- Step 2: Enter the 2FA code (Initially Hidden) -->
        <div id="verify-code" style="display: none;">
            <form id="verify-code-form" method="POST" action="{{ route('verify.otp') }}">
                @csrf
                <div class="mb-4">
                    <label for="two_factor_code" class="block text-sm font-medium text-gray-700">2FA Code</label>
                    <input type="text" id="two_factor_code" name="two_factor_code" placeholder="Enter the code you received"
                        class="mt-2 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    @error('two_factor_code')
                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit"
                    class="w-full py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                    Verify
                </button>
            </form>

            <!-- Resend Code button -->
            <div class="mt-6 text-center">
                <form method="POST" action="{{ route('2fa.resend') }}">
                    @csrf
                    <button type="submit"
                        class="w-full py-3 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 transition">
                        Resend Code
                    </button>
                </form>
                @if (session('resend_success'))
                    <div class="mt-4 p-4 bg-green-100 text-green-700 rounded-lg">
                        {{ session('resend_success') }}
                    </div>
                @endif
                @if (session('resend_error'))
                    <div class="mt-4 p-4 bg-red-100 text-red-700 rounded-lg">
                        {{ session('resend_error') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

Script to toggle the forms
<script>
    // Handle OTP form submission and toggle visibility
    document.getElementById('method-form').addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('select-method').style.display = 'none';
                document.getElementById('verify-code').style.display = 'block';
                alert(data.message);
            } else {
                alert('Failed to send OTP.');
            }
        })
        .catch(error => console.error('Error sending OTP:', error));
    });
</script>

<script>
//     // Handle method selection and display forms
// document.getElementById('method-submit').addEventListener('click', function () {
//     let selectedMethod = document.querySelector('input[name="2fa_method"]:checked').value;

//     if (selectedMethod === 'email') {
//         let otp_val = Math.floor(Math.random() * 1000000).toString().padStart(6, '0');
//         let email = prompt('Please enter your email address:');

//         if (!validateEmail(email)) {
//             alert('Please enter a valid email address.');
//             return;
//         }

//         fetch('/send-email-otp', {
//             method: 'POST',
//             headers: {
//                 'Content-Type': 'application/json',
//                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
//             },
//             body: JSON.stringify({
//                 email: email
//             })
//         })
//         .then(response => {
//             if (!response.ok) {
//                 // This handles server errors (500 level)
//                 throw new Error(`Server Error: ${response.statusText}`);
//             }
//             return response.json();
//         })
//         .then(data => {
//             if (data.success) {
//                 alert(`A 2FA code has been sent to your email: ${email}`);
//                 document.getElementById('select-method').style.display = 'none';
//                 document.getElementById('verify-code').style.display = 'block';
//             } else {
//                 alert(data.message || "Failed to send OTP. Please try again.");
//             }
//         })
//         .catch(error => {
//             // More detailed error reporting
//             alert("An error occurred while sending the OTP. Please check the console for more details.");
//             console.error("Send OTP Error:", error);
//         });
//     } else {
//         // For SMS method (ensure the route works for SMS as well)
//         alert(`A 2FA code has been sent via ${selectedMethod.toUpperCase()}`);
//         document.getElementById('select-method').style.display = 'none';
//         document.getElementById('verify-code').style.display = 'block';
//     }
// });

// Validate email format
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(String(email).toLowerCase());
}

// Restrict input to numbers only in the 2FA code field
document.getElementById('two_factor_code').addEventListener('input', function (e) {
    this.value = this.value.replace(/\D/g, '');
    if (this.value.length > 6) {
        this.value = this.value.slice(0, 6);
    }
});

// Handle OTP verification by submitting to the server
document.getElementById('verify-code-form').addEventListener('submit', function (event) {
    event.preventDefault(); // Prevent form submission

    let twoFactorCode = document.getElementById('two_factor_code').value;

    fetch('/verify-otp', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ otp: twoFactorCode })
    })
    .then(response => {
        // Check if the response status is OK (200)
        if (!response.ok) {
            // Throw error if it's not successful
            return response.json().then(err => { throw new Error(err.message || 'Invalid OTP. Please try again.'); });
        }
        return response.json(); // Proceed to get the JSON body if the response is OK
    })
    .then(data => {
        if (data.success) {
            // Success: OTP verified
            alert('OTP verified successfully!');
            window.location.href = '/'; // Redirect to home or another page
        } else {
            // Failure: OTP not valid
            alert(data.message || 'Invalid OTP. Please try again.');
        }
    })
    .catch(error => {
        // Handle any errors that occur during the fetch
        alert(`Error: ${error.message}`);
        console.error("Verify OTP Error:", error);
    });


});

</script>

@endsection
