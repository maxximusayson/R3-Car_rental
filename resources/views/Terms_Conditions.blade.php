@extends('layouts.myapp')
@section('content')
    <div class="flex flex-col justify-center items-center gap-12 my-8 mx-auto max-w-screen-xl px-4">
        <img src="/images/logos/R3LOGO.png" alt="logo" class="w-[150px] mb-6">
        <h2 class="text-5xl font-semibold text-center text-gray-800 mb-8">Terms & Conditions</h2>
        <div class="text-start flex flex-col gap-6 md:w-2/3 w-full bg-white p-6 rounded-lg shadow-md">

            <h3 class="text-2xl font-semibold text-gray-700">Acceptance of Terms:</h3>
            <p class="text-gray-600 mb-4">By renting a car from R3 Garage Car Rental, the customer agrees to abide by the terms and conditions outlined in this agreement.</p>

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
@endsection
