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
use Illuminate\Support\Facades\Http;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Validate the registration data.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:11', 'regex:/^09\d{9}$/'], // Ensure phone number is in a valid format
            'password' => ['required', 'string', 'min:8', 'confirmed']
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * Handle the request to send an OTP to the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

        // Send OTP to user's phone using Semaphore
        $response = Http::asForm()->post('https://api.semaphore.co/api/v4/messages', [
            'apikey' => env('SEMAPHORE_API_KEY'),
            'number' => $data['phone'],
            'message' => "Your OTP code is $otp",
            'sendername' => env('SEMAPHORE_SENDER_NAME')
        ]);

        // Check if the response indicates success
        if ($response->successful()) {
            return view('auth.otp'); // Show OTP input form
        } else {
            // Provide a more detailed error message if available
            $errorMessage = $response->json('error') ?? 'Failed to send OTP. Please try again.';
            return back()->with('error', $errorMessage);
        }
    }

    /**
     * Verify the OTP and complete registration.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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
