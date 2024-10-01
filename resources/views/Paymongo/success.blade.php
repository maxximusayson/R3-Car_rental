@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Payment Successful</h1>
        <p>Your payment was successful. Thank you for your payment.</p>
    </div>

    <a href="{{ route('reservations.show', ['id' => 109]) }}" class="btn btn-primary">Go Back to Reservation</a>

    
    
    <?php
header('Content-Type: application/json');

// Get the raw POST data
$request = file_get_contents('php://input');

// Decode the JSON payload
$payload = json_decode($request, true);

// Check if payload is valid
if (isset($payload['data']['attributes']['type'])) {
    $type = $payload['data']['attributes']['type'];

    // If event type is 'source.chargeable', call the createPayment API
    if ($type === 'source.chargeable') {
        $amount = $payload['data']['attributes']['data']['attributes']['amount'];
        $id = $payload['data']['attributes']['data']['id'];
        $description = "GCash Payment Description";

        $curl = curl_init();

        $fields = array(
            "data" => array(
                "attributes" => array(
                    "amount" => $amount,
                    "source" => array(
                        "id" => $id,
                        "type" => "source"
                    ),
                    "currency" => "PHP",
                    "description" => $description
                )
            )
        );

        $jsonFields = json_encode($fields);

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.paymongo.com/v1/payments",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $jsonFields,
            CURLOPT_HTTPHEADER => [
                "Accept: application/json",
                // Input your encoded API keys below for authorization
                "Authorization: Bearer YOUR_API_KEY",
                "Content-Type: application/json"
            ],
        ]);

        $response = curl_exec($curl);

        // Log the response
        file_put_contents('test.log', $response, FILE_APPEND);

        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
            // Log the error
            file_put_contents('test.log', $err, FILE_APPEND);
        } else {
            echo $response;
        }
    } else {
        echo "Event type not handled.";
    }
} else {
    echo "Invalid payload.";
}
?>


@endsection