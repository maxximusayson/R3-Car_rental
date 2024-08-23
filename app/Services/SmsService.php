<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SmsService
{
    protected $apiKey;
    protected $senderName;
    protected $apiURL = 'https://api.semaphore.co/api/v4/otp';

    public function __construct()
    {
        $this->apiKey = env('SEMAPHORE_API_KEY');
        $this->senderName = env('SEMAPHORE_SENDER_NAME', 'SEMAPHORE');
    }

    public function sendOtp($recipient, $otp)
    {
        // Ensure the API key is set
        if (empty($this->apiKey)) {
            throw new \Exception('API key is not set.');
        }

        // Prepare the message with OTP
        $message = "Your OTP code is: $otp. Please use it within 5 minutes.";

        // Prepare the data for the POST request
        $postData = [
            'apikey' => $this->apiKey,
            'number' => $recipient,
            'message' => $message,
            'sendername' => $this->senderName,
            'code' => $otp,  // The OTP code to be sent
        ];

        // Send request to Semaphore
        $response = Http::asForm()->post($this->apiURL, $postData);

        // Log and handle the response
        \Log::info('Semaphore OTP Response:', $response->json());

        if ($response->failed()) {
            \Log::error('Failed to send OTP: ' . $response->body());
            throw new \Exception('Failed to send OTP: ' . $response->body());
        }

        return $response->json();
    }
}
