<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SmsService
{
    protected $apiKey;
    protected $senderName;

    public function __construct()
    {
        $this->apiKey = env('SEMAPHORE_API_KEY');
        $this->senderName = env('SEMAPHORE_SENDER_NAME', 'SEMAPHORE');
    }

    public function sendSms($recipient, $message)
    {
        // Ensure the API key is set
        if (empty($this->apiKey)) {
            throw new \Exception('API key is not set.');
        }

        // Prepare the data for the POST request
        $postData = [
            'apikey' => $this->apiKey,
            'number' => $recipient,
            'message' => $message,
            'sendername' => $this->senderName,
        ];

        // Initialize cURL
        $ch = curl_init('https://semaphore.co/api/v4/messages');

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute cURL request
        $response = curl_exec($ch);

        // Check for cURL errors
        if ($response === false) {
            throw new \Exception('cURL Error: ' . curl_error($ch));
        }

        // Close cURL resource
        curl_close($ch);

        // Decode the JSON response
        $responseData = json_decode($response, true);

        // Check for API errors
        if (isset($responseData['error'])) {
            throw new \Exception('API Error: ' . $responseData['error']);
        }

        return $responseData;
    }

    public function send($phoneNumber, $message)
    {
        Http::post($this->apiUrl, [
            'apikey' => $this->apiKey,
            'to' => $phoneNumber,
            'message' => $message,
        ]);
    }
}
