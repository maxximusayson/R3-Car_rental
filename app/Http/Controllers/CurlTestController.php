<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CurlTestController extends Controller
{
    public function testCurl()
    {
        $ch = curl_init();
        $parameters = array(
            'apikey' => config('services.semaphore.api_key'),
            'number' => '09567227776', // Replace with a valid phone number
            'message' => 'Test message from Semaphore',
            'sendername' => 'SEMAPHORE'
        );

        curl_setopt($ch, CURLOPT_URL, 'https://semaphore.co/api/v4/messages');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameters));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $output = curl_exec($ch);
        if ($output === false) {
            $error = curl_error($ch);
            curl_close($ch);
            return response()->json(['error' => "cURL Error: $error"], 500);
        }

        curl_close($ch);
        return response()->json(['response' => $output]);
    }
}
