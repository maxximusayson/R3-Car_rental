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
use Illuminate\Support\Facades\Http;

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
        'email' => ['required', 'string', 'email', 'max:255'],
        'phone' => ['required', 'string', 'regex:/^[9]\d{9}$/'], // Validate 10 digits starting with 9
        'password' => ['required', 'string', 'min:8', 'confirmed']
    ]);
}


    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
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
        
            return response()->json([
                'message' => 'OTP sent successfully.',
                'semaphore_response' => $response,
            ]);
        } catch (Exception $e) {
            // Handle exceptions during the SMS sending process
            return response()->json([
                'error' => 'Failed to send OTP: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric',
        ]);

        $inputOtp = $request->input('otp');
        $sessionOtp = Session::get('otp');
        $registerData = Session::get('register_data');

        if ($inputOtp == $sessionOtp) {
            // Create user
            $this->create($registerData);
            Session::forget(['otp', 'register_data']);
            return redirect($this->redirectPath())->with('success', 'Registration successful!');
        } else {
            return back()->withErrors(['otp' => 'Invalid OTP. Please try again.']);
        }
    }
}
