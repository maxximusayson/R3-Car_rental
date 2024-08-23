@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Payment Successful</h1>
        <p>Your payment was successful. Thank you for your payment.</p>
    </div>

    <a href="{{ route('reservations.show', ['id' => 109]) }}" class="btn btn-primary">Go Back to Reservation</a>

@endsection
