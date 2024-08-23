<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class PaymentController extends Controller
{
    public function createSource(Request $request)
    {
        $client = new Client();

        $response = $client->request('POST', 'https://api.paymongo.com/v1/sources', [
            'body' => json_encode([
                'data' => [
                    'attributes' => [
                        'amount' => 10000,
                        'redirect' => [
                            'success' => route('paymongo.success'),
                            'failed' => route('paymongo.failed'),
                        ],
                        'type' => 'gcash',
                        'currency' => 'PHP',
                    ]
                ]
            ]),
            'headers' => [
                'accept' => 'application/json',
                'authorization' => 'Basic c2tfdGVzdF9hRG9oQkRkdHpQaG9NeG9uaGRaeHNKd206cGtfdGVzdF8zMTM5am52eGNFVzNpRVVUaUx4cHFHTlU=',
                'content-type' => 'application/json',
            ],
        ]);

        // Handle the response as needed
        return $response->getBody();
    }

    
}
