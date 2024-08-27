<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SmsService;
use App\Models\Otp;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class OTPController extends Controller
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|numeric|digits:10',
        ]);

        $phone = '63' . $request->input('phone'); // Adding +63 prefix
        $otp = rand(100000, 999999); // Generate random OTP

        // Store OTP in the database
        Otp::updateOrCreate(
            ['phone' => $phone],
            ['otp' => $otp]
        );

        try {
            // Send the OTP via SMS Service
            $this->smsService->sendOtp($phone, $otp);

            // Store the phone number in the session
            session(['phone' => $phone]);

            // Redirect to the OTP verification page with a success message
            return redirect()->route('verify.otp')->with('success', 'OTP sent successfully.');
        } catch (Exception $e) {
            // Redirect back with an error message
            return back()->with('error', 'Failed to send OTP: ' . $e->getMessage());
        }
    }

    public function verifyOtp(Request $request)
    {
        // Validate the OTP input
        $request->validate([
            'otp' => 'required|numeric|digits:6',
        ]);
    
        $phone = session('register_data.phone');
        $otp = $request->input('otp');
        $sessionOtp = Session::get('otp');
    
        if ($otp == $sessionOtp) {
            // OTP is valid, proceed with user creation and login
            $registerData = session('register_data');
    
            // Create the user account
            $user = User::create([
                'name' => $registerData['name'],
                'email' => $registerData['email'],
                'phone' => $registerData['phone'],
                'password' => Hash::make($registerData['password']),
            ]);
    
            // Log the user in
            Auth::login($user);
    
            // Clear session data
            Session::forget(['otp', 'register_data']);
    
            // Redirect to the home page or dashboard
            return redirect()->route('home')->with('success', 'Account created and OTP verified successfully. You are now logged in.');
        } else {
            // Handle invalid OTP
            return back()->with('error', 'Invalid OTP. Please try again.');
        }
    }
    

}

