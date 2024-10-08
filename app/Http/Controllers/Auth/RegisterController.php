<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Services\SmsService;
use Exception;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = RouteServiceProvider::HOME;
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->middleware('guest');
        $this->smsService = $smsService;
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'phone_number' => ['required', 'string', 'regex:/^(\+63)?9\d{9}$/'], // Adjust regex for phone number
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'username' => $data['username'],
            'phone_number' => $data['phone_number'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function requestOtp(Request $request)
    {
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        
        Session::put('register_data', $request->all());
        $otp = rand(100000, 999999);
        Session::put('otp', $otp);
        
        try {
            $response = $this->smsService->sendOtp($request->phone_number, $otp);
            return view('verify-otp', [
                'message' => 'OTP sent successfully.',
                'semaphore_response' => $response,
            ]);
        } catch (Exception $e) {
            return back()->withErrors(['otp' => 'Failed to send OTP: ' . $e->getMessage()])->withInput();
        }
    }
    

    public function resendOtp(Request $request)
{
    // Retrieve phone number from session
    $phoneNumber = session('phone');

    // Check if phone number is present in the session
    if (!$phoneNumber) {
        return back()->withErrors(['otp' => 'No phone number found.']);
    }

    // Generate a new OTP
    $otp = rand(100000, 999999);
    Session::put('otp', $otp); // Update session with new OTP

    try {
        // Attempt to send the OTP using your SMS service
        $response = $this->smsService->sendOtp($phoneNumber, $otp);
        // Set a success message in the session
        return back()->with('success', 'OTP has been resent successfully.');
    } catch (Exception $e) {
        // Handle any exceptions that occur when sending the OTP
        return back()->withErrors(['otp' => 'Failed to resend OTP: ' . $e->getMessage()]);
    }
}


    public function verifyOtp(Request $request)
    {
        $otp = $request->input('otp');
        $sessionOtp = Session::get('otp');
        $data = Session::get('register_data');

        if ($otp == $sessionOtp) {
            $user = $this->create($data);
            Auth::login($user);
            Session::forget('otp');
            Session::forget('register_data');

            // Set a flash message for successful account creation
            return redirect()->route('dashboard')->with([
                'success' => 'Account created and logged in successfully.',
                'account_created' => 'You have successfully created your account!'
            ]);
        } else {
            return back()->withErrors(['otp' => 'The OTP entered is incorrect.'])->withInput();
        }
    }
}
