@extends('layouts.myapp1')

@section('content')
<div class="mx-auto max-w-screen-xl">    
    <div class="bg-white rounded-md p-6 flex md:flex-row flex-col my-12">
        <div class="md:w-1/4 md:border-r border-gray-900 flex flex-col gap-8">
            <div class="text-center md:text-start">
                <h2 class="text-lg font-medium text-gray-900 mb-2"><span class="text-lg font-car font-normal text-gray-500">Name:</span> {{ $user->name }}</h2>
                <h2 class="text-lg font-medium text-gray-900 mb-2"><span class="text-lg font-car font-normal text-gray-500">Email:</span> {{ $user->email }}</h2>
                <!-- I-dagdag ang mga uploaded image dito -->
                <div id="valid_id_image">
                    @isset($validIdImageUrl)
                        <h2>Uploaded Driver's License:</h2>
                        <img src="{{ $validIdImageUrl }}" alt="Uploaded Valid ID">
                    @endisset
                </div>
                <div id="proof_of_billing_image">
                    @isset($proofOfBillingImageUrl)
                        <h2>Uploaded Proof of Billing:</h2>
                        <img src="{{ $proofOfBillingImageUrl }}" alt="Uploaded Proof of Billing">
                    @endisset
                </div>
            </div>
        </div>
        <div class="md:w-3/4 mt-8 md:mt-0">
            @foreach ($user->reservations as $reservation)
                <div class="flex justify-center md:m-6 mb-4 rounded-lg bg-gray-200">
                    <div class="w-[350px] h-[250px] overflow-hidden p-1 hidden md:block m-3 rounded-md">
                        <img loading="lazy" class="w-full h-full object-cover overflow-hidden rounded-md" src="{{ $reservation->car->image }}" alt="">
                    </div>
                    <div class="m-3 p-1 md:w-[500px]">
                        <h2 class="mt-2 font-car text-gray-800 text-2xl font-medium">{{ $reservation->car->brand }} {{ $reservation->car->model }} {{ $reservation->car->engine }}</h2>
                        <div class="mt-4 flex justify-start md:gap-10 gap-5">
                            <div class="flex gap-2 items-center">
                                <p class="text-lg font-medium">From:</p>
                                <p class="text-pr-600 font-semibold text-lg">{{ Carbon\Carbon::parse($reservation->start_date)->format('Y-m-d') }}</p>
                            </div>
                            <div class="flex gap-2 items-center">
                                <p class="text-lg font-medium">To:</p>
                                <p class="text-pr-600 font-semibold text-lg">{{ Carbon\Carbon::parse($reservation->end_date)->format('Y-m-d') }}</p>
                            </div>
                            <div class="flex gap-2 items-center">
                                <p class="text-lg font-medium">Price:</p>
                                <p class="text-pr-600 font-semibold text-lg">{{ $reservation->total_price }} <span class="text-black">₱</span> </p>
                            </div>
                        </div>
                        <div class="mt-8 flex justify-start md:gap-16 gap-6">
                            <div class="flex md:gap-2 items-center">
                                <p class="text-lg font-medium">Payment:</p>
                                <div class="px-4 py-3 text-sm">
                                    @if ($reservation->payment_status == 'Pending')
                                        <span class="p-2 text-white rounded-md bg-yellow-300">{{ $reservation->payment_status }}</span>
                                    @elseif ($reservation->payment_status == 'Canceled')
                                        <span class="p-2 text-white rounded-md bg-red-500">{{ $reservation->payment_status }}</span>
                                    @elseif ($reservation->payment_status == 'Paid')
                                        <span class="p-2 text-white rounded-md bg-green-500 px-5">{{ $reservation->payment_status }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex gap-2 items-center">
                                <p class="text-lg font-medium">Reservation:</p>
                                <div class="px-4 py-3 text-sm">
                                    @if ($reservation->status == 'Pending')
                                        <span class="p-2 text-white rounded-md bg-yellow-300">{{ $reservation->status }}</span>
                                    @elseif ($reservation->status == 'Ended')
                                        <span class="p-2 text-white rounded-md bg-black">{{ $reservation->status }}</span>
                                    @elseif ($reservation->status == 'Active')
                                        <span class="p-2 text-white rounded-md bg-green-500">{{ $reservation->status }}</span>
                                    @elseif ($reservation->status == 'Canceled')
                                        <span class="p-2 text-white rounded-md bg-red-500">{{ $reservation->status }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- I-add ang JavaScript na kailangan sa pagkuha ng mga URL ng imahe mula sa local storage -->
<script>
    function getImageUrls() {
        var validIdImageUrl = localStorage.getItem('validIdImageUrl');
        var proofOfBillingImageUrl = localStorage.getItem('proofOfBillingImageUrl');

        if (validIdImageUrl) {
            document.getElementById('valid_id_image').innerHTML = '<h2>Uploaded Driver\'s License:</h2><img src="' + validIdImageUrl + '" alt="Uploaded Valid ID">';
        }

        if (proofOfBillingImageUrl) {
            document.getElementById('proof_of_billing_image').innerHTML = '<h2>Uploaded Proof of Billing:</h2><img src="' + proofOfBillingImageUrl + '" alt="Uploaded Proof of Billing">';
        }
    }

    window.onload = function() {
        getImageUrls();
    };
</script>
@endsection
