<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Semaphore\SemaphoreClient;
use Illuminate\Support\Facades\Log; // Correctly import the Log facade
use App\Models\Otp;
use Exception;

class OTPController extends Controller
{
    public function sendOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|numeric|digits:11',
        ]);

        $phone = $request->input('phone');

        // Generate a random 6-digit OTP
        $otp = rand(100000, 999999);

        // Store OTP in the database
        Otp::updateOrCreate(
            ['phone' => $phone],
            ['otp' => $otp]
        );

        $message = "Thanks for registering. Your OTP Code is $otp.";

        // Retrieve API key and sender name from environment variables
        $apiKey = env('SEMAPHORE_API_KEY');
        $senderName = env('SEMAPHORE_SENDER_NAME', 'SEMAPHORE');

        // Check if the API key is set
        if (empty($apiKey)) {
            return response()->json([
                'error' => 'API key is not set.',
            ], 500);
        }

        try {
            // Initialize the SemaphoreClient with the API key
            $client = new SemaphoreClient($apiKey);

            // Send the message
            $response = $client->send($phone, $message, $senderName);

            // Return the response along with the data
            return response()->json([
                'message' => 'OTP sent successfully.',
                'semaphore_response' => $response,
            ]);
        } catch (Exception $e) {
            // Handle exceptions during the SMS sending process
            return response()->json([
                'error' => 'Failed to send message: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function verifyOtp(Request $request)
    {
        // Validate input
        $request->validate([
            'phone' => 'required|numeric|digits:11',
            'otp' => 'required|numeric|digits:6',
        ]);
    
        $phone = $request->input('phone');
        $otp = $request->input('otp');
    
        try {
            // Retrieve stored OTP for the phone number
            $storedOtp = Otp::where('phone', $phone)->first();
    
            // Check if the OTP exists for the phone number
            if (!$storedOtp) {
                return response()->json(['error' => 'Phone number not found.'], 404);
            }
    
            // Check if the provided OTP matches the stored OTP
            if ($otp === $storedOtp->otp) {
                // Optionally, delete the OTP after successful verification
                $storedOtp->delete();
    
                return response()->json(['message' => 'OTP verified successfully.']);
            } else {
                return response()->json(['error' => 'Invalid OTP.'], 400);
            }
        } catch (Exception $e) {
            // Log the exception message for debugging
            Log::error('OTP verification error: ' . $e->getMessage());
    
            return response()->json(['error' => 'An error occurred during verification.'], 500);
        }
    }
}
