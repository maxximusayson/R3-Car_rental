@extends('layouts.myapp')

@section('content')
<div class="container">
    <h2 class="text-2xl font-bold mb-4">Payments</h2>


    <div class="container">
    <div class="stepper-container">
        <div class="stepper-item active">
            <div class="step-counter">1</div>
            <div class="step-name">Fill up Information</div>
        </div>
        <div class="stepper-item">
            <div class="step-counter">2</div>
            <div class="step-name">Payments</div>
        </div>
        <div class="stepper-item">
            <div class="step-counter">3</div>
            <div class="step-name">Review</div>
        </div>
        <div class="stepper-item">
            <div class="step-name">Done!</div>
        </div>
    </div>

    <!-- GCash Payment Form -->
    <div id="gcash-payment-form" class="form-container" style="display: none;">
        <p>Redirecting to PayMongo...</p>
        <a href="https://paymongo.com/gcash" class="button button-primary">Go to PayMongo</a>
    </div>

    <!-- Cash Payment Form -->
    <div id="cash-payment-form" class="form-container" style="display: none;">
        <div class="form-group">
            <label for="cash_amount">Amount Paid</label>
            <input type="text" name="cash_amount" id="cash_amount" class="form-input">
        </div>
        <div class="mt-8 flex flex-col md:flex-row gap-4">
            <button type="button" id="proceedToReviewButton" class="button button-primary" onclick="proceedToReview()">Proceed to Review</button>
        </div>
    </div>
</div>
@endsection