<?php

namespace App\Http\Controllers\AdminAuth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/admin/dashboard'; // Adjust the admin dashboard route as per your project's setup

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login'); 
    }

    protected function credentials(Request $request)
    {
        return array_merge($request->only($this->username(), 'password'), ['role' => 'admin']);
    }

    public function username()
    {
        return 'email'; // Adjust the admin login field (e.g., email, username) as per your project's setup
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        // Check for too many login attempts
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        // Attempt to log in the user
        if ($this->attemptLogin($request)) {
            $user = Auth::user();

            // Bypass 2FA for admin users
            if ($user->role === 'admin') {
                return $this->sendLoginResponse($request);
            }

            // Generate 2FA code for non-admin users
            $user->two_factor_code = rand(100000, 999999);
            $user->two_factor_expires_at = now()->addMinutes(10);
            $user->save();

            // Send the 2FA code via SMS or other means
            $this->sendSms($user->phone_number, $user->two_factor_code);

            // Store the user's ID in the session for 2FA verification
            Session::put('two_factor:user:id', $user->id);

            // Log out the user to force 2FA verification
            Auth::logout();

            // Redirect to the 2FA verification page
            return redirect()->route('2fa.verify');
        }

        // If login attempt fails, increment login attempts and send response
        $this->incrementLoginAttempts($request);
        return $this->sendFailedLoginResponse($request);
    }

    protected function sendSms($phoneNumber, $twoFactorCode)
    {
        // Implement your SMS sending logic here
        // For example, you might use a service like Twilio, Nexmo, or Semaphore
        // Example: Sending an SMS via Twilio (You need to set up Twilio in your environment)
        // $twilio = new \Twilio\Rest\Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));
        // $twilio->messages->create($phoneNumber, [
        //     'from' => env('TWILIO_PHONE_NUMBER'),
        //     'body' => "Your 2FA code is: $twoFactorCode"
        // ]);
    }

    public function verify2FA(Request $request)
    {
        $request->validate([
            'two_factor_code' => 'required|numeric|digits:6',
        ]);

        $userId = Session::get('two_factor:user:id');
        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('login')->withErrors(['email' => 'Invalid session. Please login again.']);
        }

        if ($user->two_factor_code === $request->two_factor_code && now()->lessThan($user->two_factor_expires_at)) {
            // Clear the 2FA code and expiration time
            $user->two_factor_code = null;
            $user->two_factor_expires_at = null;
            $user->save();

            // Log the user in manually after successful 2FA
            Auth::login($user);

            // Clear the session
            Session::forget('two_factor:user:id');

            // Redirect to the intended page or the default path
            return redirect()->intended($this->redirectPath());
        }

        return back()->withErrors(['two_factor_code' => 'The verification code is incorrect or expired.']);
    }

    public function show2FAVerifyForm()
    {
        return view('auth.2fa-verify'); // Ensure this view exists
    }

    public function resend2FA(Request $request)
    {
        // Validate the user's session or authentication status if necessary
        $request->validate([
            'user_id' => 'required|exists:users,id', // Validate user ID in request if necessary
        ]);
    
        // Retrieve the user from the session or based on the provided user ID
        $userId = Session::get('two_factor:user:id', $request->input('user_id'));
        $user = User::find($userId);
    
        if (!$user || !$user->phone_number) {
            return response()->json(['success' => false, 'message' => 'Invalid user or phone number.']);
        }
    
        // Generate a new OTP
        $otp = $this->generateOtp();
    
        // Send OTP using your sendOtp method
        try {
            $this->sendOtp($user->phone_number, $otp);
            
            // Store the OTP in the session or database for verification
            session(['otp' => $otp]); // Store OTP for later verification
    
            return response()->json(['success' => true, 'message' => 'OTP has been resent successfully.']);
        } catch (\Exception $e) {
            Log::error('Failed to resend OTP: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to resend OTP.']);
        }
    }
    
    
    private function generateOtp()
    {
        // Logic to generate the OTP (e.g., random number)
        return rand(100000, 999999); // Example: returns a random 6-digit number
    }

    private function sendOtp($phoneNumber, $otp)
{
    // Your Semaphore API key
    $apiKey = env('SEMAPHORE_API_KEY');

    // Semaphore API endpoint for sending SMS
    $url = 'https://semaphore.co/api/v4/sms/send';

    // Prepare the request payload
    $data = [
        'apikey' => $apiKey,
        'number' => $phoneNumber,
        'message' => 'Your OTP code is: ' . $otp,
    ];

    // Initialize cURL
    $ch = curl_init($url);

    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

    // Execute the request
    $response = curl_exec($ch);

    // Check for errors
    if (curl_errno($ch)) {
        Log::error('Semaphore error: ' . curl_error($ch));
        throw new \Exception('Unable to send OTP via SMS.');
    }

    // Close cURL
    curl_close($ch);

    // Optionally, decode the response to check for success
    $result = json_decode($response, true);
    if (isset($result['status']) && $result['status'] !== 'success') {
        Log::error('Failed to send SMS: ' . $result['message']);
        throw new \Exception('Unable to send OTP via SMS.');
    }
}

}
