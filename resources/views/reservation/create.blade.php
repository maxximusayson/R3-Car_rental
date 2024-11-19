@extends('layouts.myapp')

@section('content')
@section('title', 'R3 Garage Car Rental | Reservation')

<!-- Stepper and Form Container -->
<div class="container mx-auto px-4 py-8">
<div class="stepper-container mb-6 flex justify-between">
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
<style>
    body {
    font-family: "Century Gothic", sans-serif;
}

.stepper-container {
    font-family: "Century Gothic", sans-serif;
    background-color: white; /* White background */
    padding: 20px; /* Optional padding for spacing */
    border-radius: 10px; /* Optional rounded corners */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Optional shadow for a subtle effect */
}

.stepper-item {
    font-family: "Century Gothic", sans-serif;
}

.step-counter {
    font-family: "Century Gothic", sans-serif;
}

.step-name {
    font-family: "Century Gothic", sans-serif;
}

</style>

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

            <!-- Form Starts -->
            <form id="reservation_form" action="{{ route('car.reservationStore', ['car' => $car->id]) }}" method="POST" enctype="multipart/form-data" class="mt-8">
                @csrf

                <div class="form-group mb-6">
                    <label for="full-name">Full Name</label>
                    <input type="text" name="full-name" id="full-name" value="{{ old('full-name', $user->name) }}" class="form-input" required>
                    @error('full-name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group mb-6">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" class="form-input" required>
                    @error('username')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>


             <!-- Date and Time Section -->
<div class="form-group mb-6">
    <label for="start_date">Start Date</label>
    <input type="text" name="start_date" id="start_date" class="form-input custom-date-input" required>
    @error('start_date')
        <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror
</div>

<div class="form-group mb-6">
    <label for="start_time">Start Time</label>
    <input type="time" name="start_time" id="start_time" class="form-input" required>
    @error('start_time')
        <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror
</div>

<div class="form-group mb-6">
    <label for="end_date">End Date</label>
    <input type="text" name="end_date" id="end_date" class="form-input custom-date-input" required disabled>
    @error('end_date')
        <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror
</div>

<div class="form-group mb-6">
    <label for="end_time">End Time</label>
    <input type="time" name="end_time" id="end_time" class="form-input" required>
    @error('end_time')
        <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const startTimeInput = document.getElementById('start_time');
        const endTimeInput = document.getElementById('end_time');

        startTimeInput.addEventListener('click', function() {
            // Clear end time when start time is clicked
            endTimeInput.value = '';
        });

        startTimeInput.addEventListener('change', function() {
            const startTime = startTimeInput.value;
            if (startTime) {
                // Convert the start time to a Date object
                const start = new Date();
                const [hours, minutes] = startTime.split(':').map(Number);
                start.setHours(hours, minutes, 0, 0);

                // Add 24 hours to the start time
                const end = new Date(start.getTime() + 24 * 60 * 60 * 1000);
                const endHours = String(end.getHours()).padStart(2, '0');
                const endMinutes = String(end.getMinutes()).padStart(2, '0');

                // Set the end time in 24-hour format
                endTimeInput.value = `${endHours}:${endMinutes}`;
            }
        });
    });
</script>



                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
                    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">


                <!-- File Uploads -->
                <div class="form-group mb-6">
                    <label for="driver_license">Upload Driver's License ID</label>
                    <input type="file" name="driver_license" id="driver_license" class="form-input" required>
                    @error('driver_license')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group mb-6">
                    <label for="valid_id">Upload Another Valid ID</label>
                    <input type="file" name="valid_id" id="valid_id" class="form-input" required>
                    @error('valid_id')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Mode of Payment -->
                <div class="form-group mb-6">
                    <label for="payment_method">Mode of Payment</label>
                    <div class="flex gap-4 mt-2">
                    <button type="button" class="payment-button {{ !env('GCASH_ENABLED', true) ? 'disabled-gcash' : '' }}" data-method="gcash" {{ !env('GCASH_ENABLED', true) ? 'disabled' : '' }}>
                        <img src="{{ asset('images/icons/gcash-icon.png') }}" alt="GCash" class="w-8 h-8"> <span>GCash</span>
                    </button>

                        <button type="button" class="payment-button" data-method="cash">
                            <img src="{{ asset('images/icons/cash.png') }}" alt="Cash" class="w-8 h-8"> <span>Cash</span>
                        </button>
                    <style>
                    /* Style for the GCash button */
.payment-button {
    position: relative;
    display: inline-block;
    padding: 10px 20px;
    background-color: #4CAF50; /* Default color */
    border: none;
    color: white;
    font-size: 14px;
    cursor: pointer;
    border-radius: 5px;
}

/* Style for the GCash button when it is disabled */
.disabled-gcash {
    background-color: #d3d3d3; /* Light gray color */
    cursor: not-allowed;
    opacity: 0.6;
}

/* Tooltip message that appears on hover (above the button) */
.payment-button.disabled-gcash:hover::after {
    content: "Under Maintenance"; /* The error message */
    position: absolute;
    bottom: 100%; /* Position the message above the button */
    left: 50%;
    transform: translateX(-50%); /* Center the message horizontally */
    background-color: #f44336; /* Red background */
    color: white;
    padding: 5px 10px;
    border-radius: 5px;
    font-size: 12px;
    white-space: nowrap;
    z-index: 10;
    margin-bottom: 5px; /* Space between the button and the message */
}

                    </style>
                    </div>

                    <!-- paypal codes -->
                    <script src="https://www.paypal.com/sdk/js?client-id=AXaOPwR8AQL8wrQch1mwDSfNQmqwfcqdeJlx2XAxVS5WRcoHQEwhD6B_P3Lh3ehSVuda7yB9InLP61iC&currency=PHP"></script>
                    <div id="paypal-button-container"></div>

                    <script>
    function getRentalPrice() {
        const pricePerDay = parseFloat('{{ $car->price_per_day }}');
        const duration = parseFloat(document.getElementById('duration').innerText);
        const totalPrice = pricePerDay * duration;
        console.log(`Rental Price (PHP): ${totalPrice.toFixed(2)}`);
        return totalPrice.toFixed(2);
    }

    paypal.Buttons({
        style: {
            layout: 'vertical',
            color: 'gold',
            shape: 'rect',
            label: 'paypal'
        },
        fundingSource: paypal.FUNDING.PAYPAL, // Restricts the payment options to PayPal only
        createOrder: function(data, actions) {
            console.log('Creating PayPal Order...');
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        currency_code: 'PHP', // Set the currency to Philippine Peso
                        value: getRentalPrice()
                    }
                }]
            });
        },
        onApprove: function(data, actions) {
            return actions.order.capture().then(function(details) {
                alert('Transaction completed by ' + details.payer.name.given_name);
                $('#gcash-status').val('completed');
            });
        },
        onError: function(err) {
            console.error('PayPal Checkout Error: ', err);
            alert(`An error occurred: ${err.message}. Please try again.`);
        }
    }).render('#paypal-button-container');
</script>

</div>


                <!-- Note Section -->
                <div class="note mb-6">
                    <p class="note-title font-bold">Note:</p>
                    <ul class="list-disc ml-6">
                        <li class="note-item"><strong>First Payment:</strong> Pay the downpayment of 1k per day.</li>
                        <li class="note-item"><strong>On the Day of the Rent:</strong> Pay the remaining balance.</li>
                        <li class="note-item"><strong>Additional Charges:</strong> If the car goes outside of Metro Manila, there is an additional charge of 500 - 1000.</li>
                    </ul>
                </div>





               <!-- Price Summary Section -->
            <!-- Price Summary Section -->
            <div id="price-summary" class="mt-4 p-4 border rounded-md w-full hidden"> <!-- Add the hidden class initially -->
                <div class="flex justify-between items-center">
                    <p class="font-bold">Duration:</p>
                    <p id="duration"><span></span></p>
                </div>
                <div class="flex justify-between items-center">
                    <p class="font-bold">Total Price:</p>
                    <p id="total-price"><span></span></p>
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
         
                       
                    <button
                        id="proceed-to-paymongo"
                        class="button button-primary mt-4 py-2 px-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                        type="button"
                        onclick="window.location.href='{{ url('paid') }}'">
                        Proceed to Gcash
                    </button>

                    </div>

                    <!-- Cash Amount Input -->
                    <div id="cash-details" class="hidden">
                    <div class="form-group mb-6">
                        <p class="font-bold text-xl">Amount of Downpayment</p> <!-- Adjusted font size -->
                        <p id="downpayment" class="text-xl"><span></span></p> <!-- Adjusted font size -->
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

                <div class="flex justify-between mt-6">
                    <button type="button" id="cancel-reservation" class="button button-secondary py-2 px-4 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">Cancel</button>
                    <button type="submit" class="button button-primary py-2 px-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition" id="confirm-reservation">Confirm Reservation</button>
                </div>
            </form>

            <!-- Confirmation Modal -->
            <div id="confirmModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 z-50 hidden">
                <div class="flex items-center justify-center min-h-screen">
                    <div class="bg-white p-6 rounded-lg shadow-lg">
                        <p>Are you sure you want to cancel the reservation?</p>
                        <div class="flex justify-end gap-4 mt-4">
                            <button id="confirmYes" class="button button-primary bg-red-600 text-white py-2 px-4 rounded-lg">Yes</button>
                            <button id="confirmNo" class="button button-secondary bg-gray-300 text-black py-2 px-4 rounded-lg">No</button>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                document.getElementById('cancel-reservation').addEventListener('click', function() {
                    document.getElementById('confirmModal').classList.remove('hidden');
                });

                document.getElementById('confirmNo').addEventListener('click', function() {
                    document.getElementById('confirmModal').classList.add('hidden');
                });

                document.getElementById('confirmYes').addEventListener('click', function() {
                    window.location.href = "{{ route('home') }}";
                });
            </script>
        </div>





   <!-- Car Specifications and Description Section -->
   <div class="mt-0 md:w-full bg-white p-6 rounded-lg shadow-md">
    <!-- Car Specifications -->
    <h3 class="text-2xl font-bold text-gray-800 mb-2">Car Specifications</h3>
    <ul class="space-y-4">
        <li class="flex items-center">
            <strong class="text-gray-700">Brand:</strong> <span class="ml-2">{{ $car->brand }}</span>
        </li>
        <li class="flex items-center">
            <strong class="text-gray-700">Model:</strong> <span class="ml-2">{{ $car->model }}</span>
        </li>
        <li class="flex items-center">
            <strong class="text-gray-700">Engine:</strong> <span class="ml-2">{{ $car->engine }}</span>
        </li>
    </ul>

    <!-- Car Description -->
<div class="mt-8">
    <h4 class="text-xl font-semibold text-gray-800 mb-4">Description</h4>
    <p class="text-gray-600 leading-relaxed text-justify">
        {{ $car->description }}
    </p>
</div>
<!-- Container for Car Images and Specifications -->
<div class="container mx-auto px-4 py-8">
    <!-- Car Images Section -->
    <div class="md:w-full">
        <h3 class="text-xl font-semibold mb-1">Car Images</h3>
        <div class="grid grid-cols-2 gap-4">
            @foreach($car->images as $image)
                <div class="car-image-container">
                    <img 
                        loading="lazy" 
                        class="object-cover w-full h-auto rounded-lg shadow-md" 
                        src="{{ asset($image->image_path) }}" 
                        srcset="{{ asset($image->image_path) }} 1x, {{ asset($image->image_path) }} 2x" 
                        alt="Image of {{ $car->make }} {{ $car->model }}" />
                </div>
            @endforeach
        </div>
    </div>
</div>
</div>
</div>
</div>

<!-- Include jQuery and Flatpickr -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<script>
$(document).ready(function() {
    // Array of already booked dates, passed from the backend
    var bookedDates = @json($bookedDates);

    // Function to save form data to local storage
    function saveFormData() {
        localStorage.setItem('fullName', $('#full-name').val());
        localStorage.setItem('username', $('#username').val());
        localStorage.setItem('startDate', $('#start_date').val());
        localStorage.setItem('startTime', $('#start_time').val());
        localStorage.setItem('endDate', $('#end_date').val());
        localStorage.setItem('endTime', $('#end_time').val());
        localStorage.setItem('cashAmount', $('#cash-amount').val());
        // Store additional inputs if needed
    }

    // Function to load form data from local storage
    function loadFormData() {
        $('#full-name').val(localStorage.getItem('fullName') || '');
        $('#username').val(localStorage.getItem('username') || '');
        $('#start_date').val(localStorage.getItem('startDate') || '');
        $('#start_time').val(localStorage.getItem('startTime') || '');
        $('#end_date').val(localStorage.getItem('endDate') || '');
        $('#end_time').val(localStorage.getItem('endTime') || '');
        $('#cash-amount').val(localStorage.getItem('cashAmount') || '');
    }

      // Call `calculateTotals` whenever relevant inputs change
      $('#start_date, #end_date, #start_time, #end_time, #cash-amount, #full-name, #username').on('input', function() {
        calculateTotals();
        updatePriceSummaryVisibility();
        saveFormData(); // Save data to local storage whenever inputs change
    });

    // Initialize Flatpickr on both fields
    function initializeFlatpickr() {
        var today = new Date();
        var minDate = new Date();
        minDate.setDate(today.getDate() + 3); // Set date to 3 days from today

        flatpickr("#start_date", {
            minDate: minDate,
            dateFormat: "Y-m-d", // Set format to match backend expectations
            disable: bookedDates, // Disable booked dates
            onChange: function(selectedDates) {
                updateEndDate(selectedDates[0]);
                $('#end_date').prop('disabled', false); // Enable end date input when start date is selected
                calculateTotals();
            }
        });

        flatpickr("#end_date", {
            dateFormat: "Y-m-d", // Set format to match backend expectations
            disable: bookedDates, // Disable booked dates
            allowInput: true, // Allow manual input of dates
            onChange: calculateTotals // Recalculate totals on date change
        });
    }

    // Set the minimum end date to one day after the selected start date
    function updateEndDate(startDate) {
        var endPicker = flatpickr("#end_date");
        if (startDate) {
            var minEndDate = new Date(startDate.getTime());
            minEndDate.setDate(startDate.getDate() + 1); // End date must be at least one day after start date
            endPicker.set('minDate', minEndDate);
        }
    }

    function calculateTotals() {
    var startDate = new Date($('#start_date').val());
    var endDate = new Date($('#end_date').val());
    var startTime = $('#start_time').val();
    var endTime = $('#end_time').val();

    if (startDate && endDate && startTime && endTime && startDate <= endDate) {
        // Calculate the duration in days
        var durationInDays = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24));

        // If start and end date are the same, consider it 1 full day
        if (durationInDays < 1) {
            durationInDays = 1;
        }

        // Display the duration in the duration field
        $('#duration span').text(durationInDays + ' days');

        var pricePerDay = parseFloat('{{ $car->price_per_day }}'); // Price per day from backend
        var totalPrice = durationInDays * pricePerDay; // Total price for the rental period

        // Fixed downpayment of 1000 PHP
        var downpaymentFixed = 1000; 
        var totalDownpayment = downpaymentFixed; // Total downpayment is fixed to 1000 PHP

        // Amount paid by the user (input from the form)
        var cashPaid = parseFloat($('#cash-amount').val()) || 0; 

        // Calculate remaining balance
        var remainingBalance = totalPrice - totalDownpayment - cashPaid; // Remaining balance calculation

        // Ensure the remaining balance is at least zero
        remainingBalance = Math.max(remainingBalance, 0);

        // Update the UI with calculated values
        $('#total-price span').text(totalPrice.toFixed(2) + ' PHP'); // Total price display
        $('#downpayment span').text(totalDownpayment.toFixed(2) + ' PHP'); // Downpayment display
        $('#grand-total span').text((totalPrice - totalDownpayment).toFixed(2) + ' PHP'); // Grand total display
        $('#remaining-balance').text(remainingBalance.toFixed(2) + ' PHP'); // Remaining balance display

        // Add a note if there's a remaining balance
        if (remainingBalance > 0) {
            $('#balance-note').text('May natitirang balanse ka na ' + remainingBalance.toFixed(2) + ' ₱.');
        } else {
            $('#balance-note').text(''); // Clear balance note if no remaining balance
        }
    } else {
        // Reset values if dates or times are not properly selected
        $('#duration span').text(''); // Reset the duration
        $('#total-price span').text('₱'); // Reset total price
        $('#downpayment span').text('₱'); // Reset downpayment
        $('#grand-total span').text('₱'); // Reset grand total
        $('#remaining-balance').text('0.00 PHP'); // Reset remaining balance
        $('#balance-note').text(''); // Clear balance note
    }

    validateForm(); // Call your validation function
}


   // Handle the payment button click functionality
   $('.payment-button').click(function() {
        var selectedMethod = $(this).data('method');
        var isActive = $(this).hasClass('active');

        // Remove the "active" class from all payment buttons
        $('.payment-button').removeClass('active');
        $('#gcash-details').addClass('hidden'); // Hide GCash details initially
        $('#cash-details').addClass('hidden'); // Hide cash input field initially
        
        if (isActive) {
            // If the clicked button is already active, deselect it
            $(this).removeClass('active');
            $('#price-summary').addClass('hidden'); // Hide price summary when deselected
        } else {
            // Add the "active" class to the clicked button
            $(this).addClass('active');
            $('#selected-payment-method').val(selectedMethod);  // Store the payment method in a hidden input

            // Show/hide the appropriate fields based on the selected payment method
            if (selectedMethod === 'gcash') {
                $('#gcash-details').removeClass('hidden'); // Show GCash proceed button
            } else if (selectedMethod === 'cash') {
                $('#cash-details').removeClass('hidden'); // Show cash input field
                $('#price-summary').removeClass('hidden'); // Show price summary section
            }

            // Update the price summary visibility
            updatePriceSummaryVisibility();
        }
    });

        // Handle the form submission
        $('#reservation_form').submit(function(e) {
        var gcashStatus = $('#gcash-status').val();
        var paymentMethod = $('.payment-button.active').data('method');

        if (paymentMethod === 'gcash' && gcashStatus === 'pending') {
            e.preventDefault();
            alert('You need to complete the payment in the GCash app.');
        } else {
            // Clear local storage after successful submission
            localStorage.clear();
        }
    });
    // Call updatePriceSummaryVisibility on page load
    updatePriceSummaryVisibility();

    // Call `calculateTotals` whenever relevant inputs change
    $('#start_date, #end_date, #start_time, #end_time, #cash-amount').on('input', function() {
        calculateTotals();
        updatePriceSummaryVisibility(); // Update visibility after calculating totals
    });
 // Initialize visibility based on payment method
 function updatePriceSummaryVisibility() {
        var selectedMethod = $('.payment-button.active').data('method');
        if (!selectedMethod) {
            $('#price-summary').addClass('hidden'); // Hide if no method is selected
        } else if (selectedMethod === 'gcash') {
            $('#price-summary').addClass('hidden'); // Hide for GCash
        } else if (selectedMethod === 'cash') {
            $('#price-summary').removeClass('hidden'); // Show for Cash
        }
    }
    // Validate the form to ensure all fields are correctly filled
    function validateForm() {
        var startDate = $('#start_date').val();
        var endDate = $('#end_date').val();
        var startTime = $('#start_time').val();
        var endTime = $('#end_time').val();
        var paymentMethod = $('.payment-button.active').data('method');
        var cashAmount = parseFloat($('#cash-amount').val()) || 0;
        var isTermsChecked = $('#agree').is(':checked'); // Check if terms are agreed

        // Check if start date, end date, start time, end time, payment method, and terms are agreed
        var isValidDates = startDate && endDate && startTime && endTime;
        var isValidPayment = paymentMethod && (paymentMethod === 'cash' ? cashAmount >= 0 : true); // Allow partial payment
        var enableButton = isValidDates && isValidPayment && isTermsChecked;

        $('#confirm-reservation').prop('disabled', !enableButton);
    }

    // Call `calculateTotals` whenever relevant inputs change
    $('#start_date, #end_date, #start_time, #end_time, #cash-amount').on('input', calculateTotals);

    // Initialize the form with the current values
    $(document).ready(calculateTotals);

    // Calculate totals when times are selected
    $('#start_time, #end_time').on('change', calculateTotals);

    // Handle proceed to PayMongo button click
    $('#proceed-to-paymongo').click(function () {
        // Change the GCash status to indicate payment is pending
        $('#gcash-status').val('pending');

        // Make an AJAX request to your backend to create a PayMongo source
        $.ajax({
            url: '/paymongo/create-source',  // Ensure this route matches the one in your routes/web.php
            type: 'POST',
            data: {
                // You can pass any additional data needed here
                _token: $('meta[name="csrf-token"]').attr('content') // Include CSRF token if necessary
            },
            success: function (response) {
                // Parse the response to get the PayMongo URL
                var responseData = JSON.parse(response);
                var paymongoUrl = responseData.data.attributes.redirect.checkout_url;

                // Redirect the user to the PayMongo checkout page
                window.location.href = paymongoUrl;
            },
            error: function (xhr, status, error) {
                // Handle errors here
                console.error('Error creating PayMongo source:', error);
                alert('There was an error processing your payment. Please try again.');
            }
        });
    });

    // Handle the form submission
    $('#reservation_form').submit(function (e) {
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

    // Handle the cancel button click
    $('#cancel-reservation').click(function() {
        // Show the confirmation modal
        $('#confirmModal').removeClass('hidden');
    });

    // Handle Yes button click
    $('#confirmYes').click(function() {
        // Redirect to cars.blade.php or home page
        window.location.href = '/cars'; // Adjust this path if your route to cars.blade.php is different
    });

    // Handle No button click
    $('#confirmNo').click(function() {
        // Hide the confirmation modal
        $('#confirmModal').addClass('hidden');
    });

    // Optional: Hide the modal when clicking outside of it
    $('#confirmModal').click(function(event) {
        if (event.target === this) {
            $(this).addClass('hidden');
        }
    });

    // Initialize Flatpickr and validate form
    initializeFlatpickr();
    validateForm();

    // Validate form when terms checkbox changes
    $('#agree').change(function() {
        validateForm();
    });
    
});
</script>

<style>
    .hidden {
    display: none; /* This will hide the element */
}
.stepper-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding: 15px;
    background-color: #f0f4f8; /* Light blue background */
    border-radius: 12px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.stepper-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    flex: 1;
    position: relative;
    text-align: center;
    transition: all 0.3s ease-in-out;
}

.stepper-item::before {
    content: '';
    position: absolute;
    width: 100%;
    height: 2px;
    background-color: #d1e3f8; /* Light blue line */
    top: 50%;
    left: 50%;
    z-index: -1;
}

.stepper-item:first-child::before,
.stepper-item:last-child::before {
    display: none;
}

.step-counter {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background-color: #ffffff;
    border: 2px solid #d1e3f8; /* Light blue border */
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 10px;
    font-size: 20px;
    color: #1d4ed8; /* Dark blue color */
    font-weight: bold;
}

.stepper-item.active .step-counter {
    border-color: #1d4ed8; /* Dark blue border for active step */
    background-color: #1d4ed8; /* Dark blue background for active step */
    color: #ffffff; /* White text for active step */
}

.step-name {
    font-size: 16px;
    color: #4b5563; /* Dark gray color for step names */
    font-weight: 600;
}

.stepper-item.active .step-name {
    color: #1d4ed8; /* Dark blue color for active step names */
}

.payment-button {
    background-color: #ffffff;
    border: 1px solid #d1e3f8; /* Light blue border */
    padding: 12px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    gap: 12px;
    cursor: pointer;
    transition: background-color 0.3s, border-color 0.3s;
    width: 100%;
    color: #1d4ed8; /* Dark blue text */
}

.payment-button.active {
    background-color: #f0f4f8; /* Light blue background when active */
    border-color: #b0c4de; /* Slightly darker blue border when active */
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
    border-color: #1d4ed8; /* Dark blue border on focus */
    outline: none;
}

.button {
    padding: 12px;
    border-radius: 6px;
    color: #ffffff;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s;
}

.button:disabled {
    background-color: #ddd;
    cursor: not-allowed;
}

.button-primary {
    background-color: #1d4ed8; /* Dark blue button */
}

.button-danger {
    background-color: #f44336; /* Red button for danger actions */
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
    border-color: #1d4ed8; /* Dark blue border on focus */
    outline: none;
}

.payment-section img {
    max-width: 100%;
    height: auto;
}

.payment-info,
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

.car-image-container {
    border: 2px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2), 0 6px 20px rgba(0, 0, 0, 0.19);
    padding: 10px;
    background-color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: 16px;
}

.car-image-container img {
    max-width: 100%;
    height: auto;
    border-radius: 4px;
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
/* Modal Background */
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000; /* Make sure the modal appears on top of other content */
}

/* Hidden class to hide the modal */
.hidden {
    display: none;
}

/* Modal Content */
.modal-content {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    text-align: center;
    width: 300px; /* Adjust width as needed */
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Optional: add shadow for better visibility */
}

/* Buttons */
.button {
    padding: 10px 20px;
    margin: 5px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.button-primary {
    background-color: #007bff;
    color: #fff;
}

.button-primary:hover {
    background-color: #0056b3;
}

.button-secondary {
    background-color: #6c757d;
    color: #fff;
}

.button-secondary:hover {
    background-color: #5a6268;
}




</style>

@endsection
