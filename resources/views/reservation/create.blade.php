@extends('layouts.myapp')

@section('content')

<style>
    .stepper-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding: 10px;
        background-color: #f4f4f4;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .stepper-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
        position: relative;
        text-align: center;
    }

    .stepper-item::before {
        content: '';
        position: absolute;
        width: 100%;
        height: 2px;
        background-color: #e0e0e0;
        top: 50%;
        left: 50%;
        z-index: -1;
    }

    .stepper-item.active .step-counter,
    .stepper-item.active .step-name {
        font-weight: bold;
        color: #3b82f6;
    }

    .stepper-item:first-child::before {
        display: none;
    }

    .stepper-item:last-child::before {
        display: none;
    }

    .step-counter {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #fff;
        border: 2px solid #e0e0e0;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 5px;
        font-size: 18px;
        color: #3b82f6;
    }

    .stepper-item.active .step-counter {
        border-color: #3b82f6;
        background-color: #3b82f6;
        color: #fff;
    }

    .step-name {
        font-size: 14px;
        color: #6b7280;
    }

    .stepper-item.active .step-name {
        color: #3b82f6;
    }

    .payment-button {
        background-color: #fff;
        border: 1px solid #e5e7eb;
        padding: 12px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 12px;
        cursor: pointer;
        transition: background-color 0.3s, border-color 0.3s;
        width: 100%;
    }

    .payment-button.active {
        background-color: #f0f0f0;
        border-color: #ddd;
    }

    .form-container {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-group label {
        font-size: 0.875rem;
        color: #4b5563;
        font-weight: 500;
    }

    .form-input {
        padding: 10px;
        border-radius: 6px;
        border: 1px solid #d1d5db;
        font-size: 1rem;
        background-color: #ffffff;
    }

    .form-input:focus {
        border-color: #4f46e5;
        outline: none;
    }

    .button {
        padding: 12px;
        border-radius: 6px;
        color: #fff;
        font-weight: bold;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .button:disabled {
        background-color: #ddd;
        cursor: not-allowed;
    }

    .button-primary {
        background-color: #3b82f6;
    }

    .button-danger {
        background-color: #f44336;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 16px;
    }

    .custom-date-input {
        border: 1px solid #d1d5db;
        border-radius: 6px;
        padding: 0.5rem;
        font-size: 1rem;
        background-color: #ffffff;
        transition: border-color 0.2s ease-in-out;
    }

    .custom-date-input:focus {
        border-color: #4f46e5;
        outline: none;
    }

    .payment-section img {
        max-width: 100%;
        height: auto;
    }

    .payment-info {
        margin-top: 1rem;
        font-size: 1rem;
        color: #333;
    }

    .instructions {
        margin-top: 1rem;
        font-size: 1rem;
        color: #333;
    }

    .instructions ol {
        padding-left: 20px;
    }

    .terms-modal-content {
        max-height: 80vh;
        overflow-y: auto;
    }

    /* Additional styles for the right-side image */
    .car-image-container {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 16px;
    }

    .car-image {
        width: 100%;
        height: auto;
        max-width: 500px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .note {
        background-color: #fffbeb;
        border-left: 4px solid #fbbf24;
        padding: 16px;
        border-radius: 4px;
        margin-top: 16px;
        font-size: 14px;
        color: #b45309;
    }

    .note-title {
        font-weight: bold;
        margin-bottom: 8px;
    }

    .note-item {
        margin-left: 20px;
        margin-bottom: 4px;
    }
</style>

<!-- Stepper and Form Container -->
<div class="container">
    <div class="stepper-container">
        <div class="stepper-item active" id="step-1">
            <div class="step-counter">1</div>
            <div class="step-name">Fill up Information and Payments</div>
        </div>
        <div class="stepper-item" id="step-2">
            <div class="step-counter">2</div>
            <div class="step-name">Review</div>
        </div>
        <div class="stepper-item" id="step-3">
            <div class="step-counter">3</div>
            <div class="step-name">Done!</div>
        </div>
    </div>

    <!-- Informations Form -->
    <div class="flex flex-col md:flex-row bg-white rounded-lg shadow-lg p-8">
        <div class="md:w-2/3 md:border-r border-gray-300 pr-6" id="information-section">
            <!-- Car Information -->
            <h2 class="text-3xl font-semibold text-gray-800 mb-4">{{ $car->brand }} {{ $car->model }} {{ $car->engine }}</h2>

            <div class="mt-4 flex items-center">
                <h3 class="text-gray-600 text-lg">Price:</h3>
                <p class="ml-3 text-xl font-bold text-blue-600 border border-blue-600 px-3 py-1 rounded-md">{{ $car->price_per_day }} ₱</p>
            </div>

            <div class="mt-6 flex items-center justify-between">
                <div class="w-1/4 h-[1px] bg-gray-300"></div>
                <p class="text-md text-gray-600">Informations</p>
                <div class="w-1/4 h-[1px] bg-gray-300"></div>
            </div>

            <form id="reservation_form" action="{{ route('car.reservationStore', ['car' => $car->id]) }}" method="POST" class="mt-8">
                @csrf

                <div class="form-group mb-6">
                    <label for="full-name">Full Name</label>
                    <input type="text" name="full-name" id="full-name" value="{{ $user->name }}" class="form-input" required>
                    @error('name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group mb-6">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" value="{{ $user->email }}" class="form-input" required>
                    @error('email')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Date start-end -->
                <div class="form-group mb-6">
                    <label for="start_date">Start at</label>
                    <input type="text" name="start_date" id="start_date" class="form-input custom-date-input" required>
                    @error('start_date')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group mb-6">
                    <label for="end_date">End at</label>
                    <input type="text" name="end_date" id="end_date" class="form-input custom-date-input" required>
                    @error('end_date')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Mode of Payment -->
                <div class="form-group mb-6">
                    <label for="payment_method">Mode of Payment</label>
                    <div class="flex gap-4 mt-2">
                        <button type="button" class="payment-button" data-method="gcash">
                            <img src="{{ asset('images/gcash-icon.png') }}" alt="GCash" class="w-8 h-8"> <span>GCash</span>
                        </button>
                        <button type="button" class="payment-button" data-method="cash">
                            <img src="{{ asset('images/cash.png') }}" alt="Cash" class="w-8 h-8"> <span>Cash</span>
                        </button>
                    </div>
                </div>

                <!-- Note Section -->
                <div class="note">
                    <p class="note-title">Note:</p>
                    <ul>
                        <li class="note-item"><strong>First Payment:</strong> Pay the downpayment of 1k.</li>
                        <li class="note-item"><strong>On the Day of the Rent:</strong> Pay the remaining balance.</li>
                        <li class="note-item"><strong>Additional Charges:</strong> If the car goes outside of Metro Manila, there is an additional charge of 500 - 1000.</li>
                    </ul>
                </div>

                <!-- Price Summary Section -->
                <div class="mt-4 p-4 border rounded-md w-full">
                    <div class="flex justify-between items-center">
                        <p class="font-bold">Duration:</p>
                        <p id="duration"><span></span></p>
                    </div>
                    <div class="flex justify-between items-center">
                        <p class="font-bold">Total Price:</p>
                        <p id="total-price"><span></span></p>
                    </div>
                    <div class="flex justify-between items-center">
                        <p class="font-bold">Downpayment:</p>
                        <p id="downpayment"><span>₱1000</span></p>
                    </div>
                    <div class="flex justify-between items-center font-bold">
                        <p class="font-bold">Grand Total:</p>
                        <p id="grand-total"><span></span></p>
                    </div>
                </div>

                <!-- Payment Details -->
                <div id="payment-details" class="mt-6">
                    <!-- QR Code for GCash -->
                    <div id="gcash-details" class="payment-info hidden">
                        <p class="text-gray-700 mb-4">Follow these instructions to complete your payment:</p>
                        <ol class="list-decimal list-inside text-gray-600 mb-4">
                            <li>Open the GCash app on your mobile device.</li>
                            <li>Tap on the "Pay QR" option from the main menu.</li>
                            <li>Scan the QR code below.</li>
                            <li>Enter the amount and confirm the payment.</li>
                        </ol>
                        <p class="text-gray-700 mb-4">Once the payment is made, you will be redirected to the PayMongo dashboard to complete the reservation.</p>
                        <img src="{{ asset('images/gcash-qrcode.png') }}" alt="GCash QR Code" class="w-1/2 mt-2 mx-auto">
                        <button id="proceed-to-paymongo" class="button button-primary mt-4 py-2 px-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition" type="button">Proceed to PayMongo</button>
                    </div>

                    <!-- Cash Amount Input -->
                    <div id="cash-details" class="hidden">
                        <div class="form-group mb-6">
                            <label for="cash-amount">Amount in PHP</label>
                            <input type="number" name="cash_amount" id="cash-amount" class="form-input" placeholder="Enter amount" min="0" step="0.01">
                            @error('cash_amount')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Modal background -->
                <div id="termsModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 z-50 hidden">
                    <!-- Modal content -->
                    <div class="flex items-center justify-center min-h-screen">
                        <div class="relative bg-white p-6 rounded-lg shadow-lg w-full md:w-2/3 lg:w-1/2">
                            <!-- Close button -->
                            <button id="closeModal" class="absolute top-2 right-2 text-gray-600 hover:text-gray-900 text-2xl font-bold">&times;</button>
                            <h2 class="text-3xl font-semibold mb-4">Terms & Conditions</h2>
                            <!-- Terms and conditions content -->
                            <p class="mb-4">By renting a car from R3 Garage Car Rental, the customer agrees to abide by the terms and conditions outlined in this agreement.</p>
                            <h3 class="text-2xl font-semibold text-gray-700">1. Rental Period:</h3>
                            <p class="text-gray-600 mb-2 ms-4">- The rental period begins on the date and time specified in the rental agreement and ends on the agreed return date and time. Any extension of the rental period must be approved by R3 Garage Car Rental and may be subject to additional charges.</p>
                            <p class="text-gray-600 mb-4 ms-4">- Customers must leave one valid ID for the duration of the rental period.</p>

                            <h3 class="text-2xl font-semibold text-gray-700">2. Authorized Drivers:</h3>
                            <p class="text-gray-600 mb-2 ms-4">- Only drivers listed in the rental agreement and meeting the minimum age and valid driver's license requirements are authorized to operate the rented vehicle.</p>
                            <p class="text-gray-600 mb-4 ms-4">- Foreigners must present and attach their international driver's license and passport to the rental agreement.</p>

                            <h3 class="text-2xl font-semibold text-gray-700">3. Use of Vehicle:</h3>
                            <p class="text-gray-600 mb-4 ms-4">- The rented vehicle is to be used solely for lawful and personal transportation purposes. It must not be used for illegal activities, racing, towing, off-road driving, or transporting hazardous materials.</p>

                            <h3 class="text-2xl font-semibold text-gray-700">4. Vehicle Condition:</h3>
                            <p class="text-gray-600 mb-4 ms-4">- The customer acknowledges receiving the rented vehicle in good condition, with all necessary documents, spare tire, and accessories. Any pre-existing damages must be documented in the rental agreement to avoid disputes upon return.</p>

                            <h3 class="text-2xl font-semibold text-gray-700">5. Fuel Policy:</h3>
                            <p class="text-gray-600 mb-4 ms-4">- The vehicle must be returned with the same fuel level as when rented. Failure to do so will result in refueling charges based on prevailing rates.</p>

                            <h3 class="text-2xl font-semibold text-gray-700">6. Charges and Payments:</h3>
                            <p class="text-gray-600 mb-2 ms-4">- Rental charges, additional services, fines (e.g., traffic violations), and damages are payable upon return of the vehicle. R3 Garage Car Rental reserves the right to charge the customer's credit card on file for outstanding amounts.</p>
                            <p class="text-gray-600 mb-2 ms-4">- A security deposit of 1,000 PHP is required for every car reserved.</p>
                            <p class="text-gray-600 mb-4 ms-4">- Inside Metro Manila rates are listed on the board, with an additional charge of 500 PHP if the vehicle is used outside Metro Manila.</p>

                            <h3 class="text-2xl font-semibold text-gray-700">7. Bookings:</h3>
                            <p class="text-gray-600 mb-4 ms-4">- The nearest booking can be made for the next day, provided that the vehicle is available.</p>

                            <h3 class="text-2xl font-semibold text-gray-700">8. Cancellations:</h3>
                            <p class="text-gray-600 mb-4 ms-4">- Cancellations made within 48 hours of the rental start time may incur cancellation fees.</p>

                            <h3 class="text-2xl font-semibold text-gray-700">9. Dispute Resolution:</h3>
                            <p class="text-gray-600 mb-4 ms-4">- Any disputes arising from this rental agreement shall be resolved amicably through negotiation between the parties. In case of unresolved disputes, legal remedies may be pursued according to applicable laws and jurisdictions.</p>
                        </div>
                    </div>
                </div>
                <!-- Terms and Conditions -->
                <div class="form-group mb-6 flex items-center">
                    <input type="checkbox" id="agree" class="mr-2" required>
                    <label for="agree">I agree to the <a href="#" id="openModal" class="text-blue-600 hover:underline">terms and conditions</a>.</label>
                </div>


                <script>
                    document.getElementById('openModal').addEventListener('click', function(event) {
                        event.preventDefault(); // Prevent the default link behavior
                        document.getElementById('termsModal').classList.remove('hidden');
                    });

                    document.getElementById('closeModal').addEventListener('click', function() {
                        document.getElementById('termsModal').classList.add('hidden');
                    });

                    // Optional: Close the modal when clicking outside of it
                    document.getElementById('termsModal').addEventListener('click', function(event) {
                        if (event.target === this) {
                            this.classList.add('hidden');
                        }
                    });
                </script>


                <!-- Hidden field to track GCash status -->
                <input type="hidden" id="gcash-status" name="gcash_status" value="pending">

                <div class="flex justify-end mt-6">
                    <button type="submit" class="button button-primary py-2 px-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition" id="confirm-reservation">Confirm Reservation</button>
                </div>
            </form>
        </div>

        <!-- Car Image on the Right Side -->
        <div class="md:w-1/3 flex items-center justify-center mt-6 md:mt-0 car-image-container">
            <img src="{{ asset('images/' . $car->image) }}" alt="{{ $car->brand }} {{ $car->model }}" class="car-image">
        </div>
    </div>
</div>

<!-- Include jQuery and Flatpickr -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<script>
$(document).ready(function() {
    // Initialize Flatpickr on both fields
    function initializeFlatpickr() {
        var today = new Date();
        var minDate = new Date();
        minDate.setDate(today.getDate() + 3); // Set date to 3 days from today

        flatpickr("#start_date", {
            minDate: minDate,
            dateFormat: "Y-m-d", // Set format to match backend expectations
            onChange: function(selectedDates) {
                updateEndDate(selectedDates[0]);
                $('#end_date').prop('disabled', false); // Enable end date input when start date is selected
                calculateTotals();
            }
        });

        flatpickr("#end_date", {
            minDate: minDate,
            dateFormat: "Y-m-d", // Set format to match backend expectations
            allowInput: true, // Allow manual input of dates
            enable: false // Initially disable end date picker
        });
    }

    function updateEndDate(startDate) {
        var endPicker = flatpickr("#end_date");
        if (startDate) {
            var minEndDate = new Date(startDate.getTime());
            minEndDate.setDate(startDate.getDate() + 1); // End date must be at least one day after start date

            endPicker.set('minDate', minEndDate.toISOString().split('T')[0]);
        }
    }

    function calculateTotals() {
        var startDate = new Date($('#start_date').val());
        var endDate = new Date($('#end_date').val());

        if (startDate && endDate && startDate <= endDate) {
            var duration = Math.max(1, Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24))); // Minimum 1 day
            var pricePerDay = {{ $car->price_per_day }}; // 3000 PHP per day
            var totalPrice = duration * pricePerDay;
            var downpayment = 1000; // Fixed downpayment amount
            var grandTotal = totalPrice - downpayment; // Total amount due minus downpayment

            $('#duration span').text(duration + ' days');
            $('#total-price span').text(totalPrice + ' ₱');
            $('#downpayment span').text(downpayment + ' ₱');
            $('#grand-total span').text(grandTotal + ' ₱');
        } else {
            $('#duration span').text('days');
            $('#total-price span').text('₱');
            $('#downpayment span').text('₱');
            $('#grand-total span').text('₱');
        }

        validateForm();
    }

    function validateForm() {
        var startDate = $('#start_date').val();
        var endDate = $('#end_date').val();
        var paymentMethod = $('.payment-button.active').data('method');
        var cashAmount = $('#cash-amount').val();
        var isTermsChecked = $('#agree').is(':checked'); // Check if terms are agreed

        // Check if start date, end date, payment method, and terms are agreed
        var isValidDates = startDate && endDate;
        var isValidPayment = paymentMethod && (paymentMethod === 'cash' ? cashAmount > 0 : true);
        var enableButton = isValidDates && isValidPayment && isTermsChecked;

        $('#confirm-reservation').prop('disabled', !enableButton);
    }

    // Handle form changes to calculate total price and duration
    $('#start_date, #end_date').change(function() {
        calculateTotals();
    });

    // Handle payment method selection
    $('.payment-button').click(function() {
        var paymentMethod = $(this).data('method');
        $('#selected_payment_method').val(paymentMethod);

        // Update UI based on selected payment method
        $('.payment-button').removeClass('active');
        $(this).addClass('active');

        // Show the payment details section
        $('#payment-details').show();
        $('#gcash-details').toggle(paymentMethod === 'gcash');
        $('#cash-details').toggle(paymentMethod === 'cash');

        // Update hidden field for GCash status based on selected payment method
        $('#gcash-status').val(paymentMethod === 'cash' ? 'paid' : 'pending');

        calculateTotals();
    });

    // Handle proceed to PayMongo button click
    $('#proceed-to-paymongo').click(function() {
        // Change the GCash status to indicate payment is pending
        $('#gcash-status').val('pending');

        // Redirect to the PayMongo dashboard URL (replace with your actual URL)
        window.location.href = 'https://dashboard.paymongo.com/';
    });

    // Handle the form submission
    $('#reservation_form').submit(function(e) {
        var gcashStatus = $('#gcash-status').val();
        var paymentMethod = $('.payment-button.active').data('method');

        if (paymentMethod === 'gcash' && gcashStatus === 'pending') {
            e.preventDefault();
            alert('You need to complete the payment in the GCash app.');
        }
    });

    // Handle modal open and close
    $('#openModal').click(function() {
        $('#termsModal').removeClass('hidden');
    });

    $('#closeModal').click(function() {
        $('#termsModal').addClass('hidden');
    });

    // Optional: Close the modal when clicking outside of it
    $('#termsModal').click(function(event) {
        if (event.target === this) {
            $(this).addClass('hidden');
        }
    });

    // Initialize Flatpickr
    initializeFlatpickr();

    // Validate form when terms checkbox changes
    $('#agree').change(function() {
        validateForm();
    });
});
</script>

@endsection