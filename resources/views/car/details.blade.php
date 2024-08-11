@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $car->brand }} {{ $car->model }}</h1>
        <p>Engine: {{ $car->engine }}</p>
        <p>Price per day: ₱{{ number_format($car->price_per_day, 2) }}</p>
        <img src="{{ $car->image }}" alt="Car image">
    </div>
@endsection