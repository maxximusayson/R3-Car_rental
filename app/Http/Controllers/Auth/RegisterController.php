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
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'regex:/^[9]\d{9}$/'], // Validate 10 digits starting with 9
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function requestOtp(Request $request)
    {
        $data = $request->all();
    
        // Validate user input
        $validator = $this->validator($data);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
    
        // Save user data temporarily in session
        Session::put('register_data', $data);
    
        // Generate OTP
        $otp = rand(100000, 999999);
        Session::put('otp', $otp);
    
        try {
            // Use the sendOtp method from SmsService
            $response = $this->smsService->sendOtp($data['phone'], $otp);
            
            // Optionally store the phone number in the session for verification
            session(['phone' => $data['phone']]);
    
            // Pass the response and any other data to the view
            return view('verify-otp', [
                'message' => 'OTP sent successfully.',
                'semaphore_response' => $response,
            ]);
        } catch (Exception $e) {
            // Handle exceptions during the SMS sending process
            return view('verify-otp', [
                'error' => 'Failed to send OTP: ' . $e->getMessage(),
            ]);
        }
    }
    
    public function verifyOtp(Request $request)
    {
        $otp = $request->input('otp');
        $sessionOtp = Session::get('otp');
        $data = Session::get('register_data');
    
        if ($otp == $sessionOtp) {
            // Create user account
            $user = User::create([
                'name' => $data['name'],
                'username' => $data['username'],
                'phone' => $data['phone'],
                'password' => Hash::make($data['password']),
            ]);
    
            // Automatically log the user in
            Auth::login($user);
    
            // Clear the session data
            Session::forget('otp');
            Session::forget('register_data');
    
            // Redirect to a success page or dashboard
            return redirect()->route('dashboard')->with('success', 'Account created and logged in successfully.');
        } else {
            // Handle OTP mismatch
            return back()->withErrors(['otp' => 'The OTP entered is incorrect.'])->withInput();
        }
    }
}
