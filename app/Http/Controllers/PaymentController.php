<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function pay()
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.paymongo.com/v1/links",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                'data' => [
                    'attributes' => [
                        'amount' => 10000, // Amount in centavos (e.g., 10000 = 100 PHP)
                        'description' => 'Deposit Payment',
                        'remarks' => 'Thank you for choosing us!',
                        'redirect' => [
                            'success' => 'https://yourwebsite.com/success', // Replace with your actual success URL
                            'failed' => 'https://yourwebsite.com/failed' // Replace with your actual failure URL
                        ]
                    ]
                ]
            ]),
            CURLOPT_HTTPHEADER => [
                "accept: application/json",
                "authorization: Basic c2tfdGVzdF9hRG9oQkRkdHpQaG9NeG9uaGRaeHNKd206", // Replace with your actual API key
                "content-type: application/json"
            ],
        ]);
 
        $response = curl_exec($curl);
        $decoded = json_decode($response, true);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            // Handle the error if cURL fails
            echo "cURL Error #:" . $err;
        } else {
            // Check if the checkout URL is present in the response
            if (isset($decoded['data']['attributes']['checkout_url'])) {
                $checkoutUrl = $decoded['data']['attributes']['checkout_url'];

                // Redirect to the checkout URL
                return redirect()->away($checkoutUrl);
            } else {
                // Handle the case where the checkout URL is missing
                echo "Checkout URL not found in the response.";
            }
        }
    }

    public function paymentSuccess()
    {
        // Handle the logic after successful payment here
        return view('payment.success');
    }

    public function paymentFailed()
    {
        // Handle the logic after failed payment here
        return view('payment.failed');
    }
}
