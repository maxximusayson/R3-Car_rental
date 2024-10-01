@extends('layouts.myapp')

@section('content')

<div class="container">
    <div class="stepper-container">
        <div class="stepper-item" id="step-1">
            <div class="step-counter">1</div>
            <div class="step-name">Fill up Information and Payments</div>
        </div>
        <div class="stepper-item active" id="step-2">
            <div class="step-counter">2</div>
            <div class="step-name">Review</div>
        </div>
        <div class="stepper-item" id="step-3">
            <div class="step-counter">3</div>
            <div class="step-name">Done!</div>
        </div>
    </div>

    <div class="flex flex-col md:flex-row bg-white rounded-md p-6" id="review-section">
        <div class="md:w-2/3 md:border-r border-gray-800 pr-4 text-center">
            <div class="w-32 mt-10 mx-auto">
                <img loading="lazy" src="/images/logos/R3LOGO.jpg" alt="R3 Logo">
            </div>
            <h1 class="font-bold text-gray-900 text-4xl mt-4">Review Your Information and Payment Details.</h1>
            <p class="text-gray-600 mt-2">Thank you for choosing and trusting our car company.</p>

            <div class="summary-container">
                <div class="summary-item">
                    <label>Full Name:</label>
                    <span>{{ $reservation['full_name'] }}</span>
                </div>
                <div class="summary-item">
                    <label>Email:</label>
                    <span>{{ $reservation['email'] }}</span>
                </div>
                <div class="summary-item">
                    <label>Start Date:</label>
                    <span>{{ $reservation['start_date'] }}</span>
                </div>
                <div class="summary-item">
                    <label>End Date:</label>
                    <span>{{ $reservation['end_date'] }}</span>
                </div>
                <div class="summary-item">
                    <label>Start Time:</label>
                    <span>{{ $reservation['start_time'] }}</span>
                </div>
                <div class="summary-item">
                    <label>End Time:</label>
                    <span>{{ $reservation['end_time'] }}</span>
                </div>
                <div class="summary-item">
                    <label>Car:</label>
                    <span>{{ $reservation['car_brand'] }} {{ $reservation['car_model'] }}</span>
                </div>
                <div class="summary-item">
                    <label>Price per Day:</label>
                    <span>{{ $reservation['price_per_day'] }} ₱</span>
                </div>
                <div class="summary-item">
                    <label>Mode of Payment:</label>
                    <span>{{ $reservation['payment_method'] }}</span>
                </div>
                <div class="summary-item">
                    <label>Remaining Balance to Pay:</label>
                    <span>{{ $reservation['remaining_balance'] }} ₱</span>
                </div>
                <div class="summary-item">
                    <label>Driver's License:</label>
                    @if(isset($reservation['driver_license']) && !empty($reservation['driver_license']))
                        <a href="{{ $reservation['driver_license'] }}" target="_blank" class="text-blue-600 hover:underline">View Document</a>
                    @else
                        <span class="text-gray-600">Not provided</span>
                    @endif
                </div>
                <div class="summary-item">
                    <label>Valid ID:</label>
                    @if(isset($reservation['valid_id']) && !empty($reservation['valid_id']))
                        <a href="{{ $reservation['valid_id'] }}" target="_blank" class="text-blue-600 hover:underline">View Document</a>
                    @else
                        <span class="text-gray-600">Not provided</span>
                    @endif
                </div>
            </div>

            <div class="button-container">
                <!-- Confirm Button -->
                <button class="button button-confirm px-4 py-2 rounded-md" id="confirm-button">Confirm</button>
                
                <!-- Edit Button - Redirect to create.blade.php with car_id -->
                <a href="{{ route('reservation.create', ['car_id' => $reservation['car_id']]) }}" class="button button-edit px-4 py-2 rounded-md">Edit</a>
            </div>
        </div>
    </div>

    <div class="done-container" id="done-section" style="display: none;">
        <h1>Pending Reservation!</h1>
        <p>Thank you for your reservation. We will contact you soon with further details.</p>
    </div>
</div>


<script>
    document.getElementById('confirm-button').addEventListener('click', function() {
        document.getElementById('review-section').style.display = 'none';
        document.getElementById('done-section').style.display = 'block';

        // Update stepper to mark "Done" as active
        document.getElementById('step-2').classList.remove('active');
        document.getElementById('step-3').classList.add('active');

        // Optionally, you can make an AJAX request to update the reservation status or perform other actions
        // Example:
        // fetch('/reservation/confirm', { method: 'POST', body: JSON.stringify({ reservation_id: {} }) })
        //     .then(response => response.json())
        //     .then(data => console.log(data));
    });
</script>
<style>
    .stepper-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding: 10px;
        background-color: #f9f9f9;
        border-bottom: 2px solid #e0e0e0;
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
        color: black;
    }

    .stepper-item:first-child::before,
    .stepper-item:last-child::before {
        display: none;
    }

    .step-counter {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background-color: white;
        border: 2px solid #e0e0e0;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 5px;
    }

    .stepper-item.active .step-counter {
        border-color: black;
        background-color: #3f8ebf;
        color: white;
    }

    .step-name {
        font-size: 12px;
        color: gray;
    }

    .stepper-item.active .step-name {
        color: black;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 16px;
    }

    .button {
        padding: 12px;
        border-radius: 4px;
        color: white;
        font-weight: bold;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .button-primary {
        background-color: #3B8CBF;
    }

    .button:hover {
        background-color: #2a6a97;
    }

    .payment-button {
        background-color: white;
        border: 1px solid #ddd;
        padding: 8px 16px;
        border-radius: 4px;
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: background-color 0.3s, border-color 0.3s;
    }

    .payment-button.active {
        background-color: #f0f0f0;
        border-color: #ccc;
    }

    .form-container {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-group label {
        font-size: 0.875rem;
        color: #333;
    }

    .form-group input {
        padding: 8px;
        border-radius: 4px;
        border: 1px solid #ccc;
    }

    .summary-container {
        display: flex;
        flex-direction: column;
        gap: 16px;
        background-color: #f9f9f9;
        padding: 16px;
        border-radius: 8px;
        margin-top: 16px;
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
    }

    .summary-item label {
        font-weight: bold;
    }

    .button-container {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
    }

    .button-edit {
        background-color: #f44336;
    }

    .button-edit:hover {
        background-color: #d32f2f;
    }

    .button-confirm {
        background-color: #4CAF50;
    }

    .button-confirm:hover {
        background-color: #45a049;
    }

    .done-container {
        text-align: center;
        padding: 50px;
    }

    .done-container h1 {
        font-size: 3rem;
        color: #4CAF50;
        margin-bottom: 20px;
    }

    .done-container p {
        font-size: 1.5rem;
        color: #333;
    }
</style>
@endsection
