<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SmsService;
use App\Models\Otp;
use Exception;

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
            // Send the OTP via Semaphore
            $this->smsService->sendOtp($phone, $otp);
    
            // Store the phone number in the session
            session(['phone' => $phone]);
    
            // Redirect to the OTP verification page
            return redirect()->route('verify.otp')->with('success', 'OTP sent successfully.');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to send OTP: ' . $e->getMessage());
        }
    }
    

public function verifyOtp(Request $request)
{
    $request->validate([
        'otp' => 'required|numeric|digits:6',
    ]);

    $phone = session('phone');
    $otp = $request->input('otp');

    $storedOtp = Otp::where('phone', $phone)->first();

    if ($storedOtp && $storedOtp->otp === $otp) {
        // OTP is valid, proceed with user verification
        $storedOtp->delete(); // Optionally, delete the OTP after verification

        return redirect()->route('home')->with('success', 'OTP verified successfully.');
    } else {
        return back()->with('error', 'Invalid OTP. Please try again.');
    }
}

}

