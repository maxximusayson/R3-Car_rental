<?php

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected function redirectTo()
    {
        if (Auth::check()) {
            return $this->getRedirectPath(Auth::user()->role);
        }

        return RouteServiceProvider::HOME; // Fallback
    }

    protected function getRedirectPath($role)
    {
        switch ($role) {
            case 'admin':
                return RouteServiceProvider::ADMIN;
            case 'user':
                return RouteServiceProvider::HOME;
            default:
                return RouteServiceProvider::HOME;
        }
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
{
    // Validate the input
    $validator = Validator::make($request->all(), [
        'username' => 'required|string',
        'password' => 'required',
    ]);

    if ($validator->fails()) {
        return back()->withErrors($validator)->withInput();
    }

    // Rate limiting key
    $key = Str::lower($request->input('username')) . '|' . $request->ip();
    $maxAttempts = 5;
    $decayMinutes = 15;

    if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
        $seconds = RateLimiter::availableIn($key);
        return back()->withErrors([
            'username' => "Too many login attempts. Please try again in " . ceil($seconds / 60) . " minutes."
        ])->withInput($request->only('username'));
    }

    // Remember me option
    $remember = $request->has('remember');

    // Attempt login first (don't send OTP yet)
    if (Auth::attempt($request->only('username', 'password'), $remember)) {
        // Clear failed attempts after successful login
        RateLimiter::clear($key);

        $user = Auth::user();

        // Admins bypass 2FA
        if ($user->role === 'admin') {
            return $this->sendLoginResponse($request); // Skip 2FA for admin users
        }

        // Store user ID for 2FA verification and log out user until verified
        Session::put('two_factor:user:id', $user->id);
        Auth::logout();

        // Redirect to choose 2FA method (SMS or Email)
        return redirect()->route('2fa.verify');
    } else {
        // Increment failed login attempts and return error
        RateLimiter::hit($key, $decayMinutes * 60);
        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('username'));
    }
}
    // Function to send OTP via both SMS and email
    protected function sendOtp($user)
    {
        $otpCode = $user->two_factor_code; // Retrieve the saved OTP from the user's record
    
        // Send OTP via SMS
        $this->sendSms($user->phone_number, $otpCode);
    
        // Send OTP via Email
        $this->sendEmail($user->email, $otpCode);
    
        // Log the OTP sent action
        Log::info('Sent OTP via SMS and Email', ['user_id' => $user->id, 'otp' => $otpCode]);
    }
    

    
    
    public function show2FAVerifyForm()
    {
        return view('auth.2fa-verify');
    }

    public function verify2FA(Request $request)
{
    $request->validate(['two_factor_code' => 'required|numeric|digits:6']);

    $userId = Session::get('two_factor:user:id');
    $user = User::find($userId);

    if (!$user) {
        return redirect()->route('login')->withErrors(['username' => 'Invalid session. Please login again.']);
    }

    // Compare the OTP entered with the one saved and check expiry
    if ($user->two_factor_code === $request->two_factor_code && now()->lessThan($user->two_factor_expires_at)) {
        $user->two_factor_code = null;
        $user->two_factor_expires_at = null;
        $user->save();

        Auth::login($user);
        Session::forget('two_factor:user:id');

        return redirect()->intended($this->redirectPath());
    } else {
        Log::warning('Invalid or expired OTP.', [
            'expected' => $user->two_factor_code,
            'received' => $request->two_factor_code,
            'expires_at' => $user->two_factor_expires_at,
            'current_time' => now(),
        ]);

        return back()->withErrors(['two_factor_code' => 'Invalid or expired OTP. Please try again.']);
    }
}


    protected function sendSms($phoneNumber, $code)
    {
        $apiKey = config('services.semaphore.api_key');
        $senderName = config('services.semaphore.sender_name');
        $message = "Your 2FA verification code is: $code";
    
        $response = Http::post('https://api.semaphore.co/api/v4/messages', [
            'apikey' => $apiKey,
            'number' => $phoneNumber,
            'message' => $message,
            'sendername' => $senderName,
        ]);
    
        Log::info('SMS OTP sent', ['phone' => $phoneNumber, 'otp' => $code, 'response' => $response->body()]);
    }
    protected function sendEmail($email, $code)
    {
        $subject = "Your 2FA Verification Code";
        $message = "Your 2FA verification code is: $code"; // Use the code passed from sendOtp()
    
        Mail::raw($message, function ($mail) use ($email, $subject) {
            $mail->to($email)->subject($subject);
        });
    
        // Log the email sending action
        Log::info('Email OTP sent', ['email' => $email, 'otp' => $code]);
    }
    public function logout(Request $request)
    {
        $user = Auth::user();

        // Update last activity (optional)
        $user->last_activity = null;
        $user->save();

        // Log out and clear session
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function send2FA(Request $request)
{
    $userId = Session::get('two_factor:user:id');
    $user = User::find($userId);

    if (!$user) {
        return redirect()->route('login')->withErrors(['username' => 'Invalid session. Please login again.']);
    }

    // Generate new 2FA code
    $otpCode = rand(100000, 999999);
    $user->two_factor_code = $otpCode;
    $user->two_factor_expires_at = now()->addMinutes(10);
    $user->save();
    

    // Check selected method
    if ($request->input('2fa_method') === 'sms') {
        $this->sendSms($user->phone_number, $otpCode);
    } else {
        $this->sendEmail($user->email, $otpCode);
    }

    return response()->json(['success' => true, 'message' => 'A 2FA code has been sent.']);
}


// LoginController.php or AuthController.php
public function verifyOtp(Request $request)
{
    try {
        $request->validate(['otp' => 'required|digits:6']);

        Log::info('Session data before OTP verification:', session()->all());

        $userId = session('two_factor:user:id');
        if (!$userId) {
            Log::error('Session data missing: No user ID found in session.');
            return response()->json(['success' => false, 'message' => 'Session expired. Please log in again.']);
        }

        $user = User::find($userId);
        if (!$user) {
            Log::error('User not found with ID: ' . $userId);
            return response()->json(['success' => false, 'message' => 'User not found. Please log in again.']);
        }

        if ($user->two_factor_code === $request->otp && now()->lessThan($user->two_factor_expires_at)) {
            $user->two_factor_code = null;
            $user->two_factor_expires_at = null;
            $user->save();

            Log::info('OTP matched successfully.', ['expected' => $user->two_factor_code, 'received' => $request->otp]);

            Auth::login($user);
            Session::forget('two_factor:user:id');

            return response()->json(['success' => true, 'message' => 'OTP verified successfully!']);
        }

        Log::warning('Invalid or expired OTP.', [
            'expected' => $user->two_factor_code,
            'received' => $request->otp,
            'expires_at' => $user->two_factor_expires_at,
            'current_time' => now(),
        ]);

        return response()->json(['success' => false, 'message' => 'Invalid or expired OTP. Please try again.']);
    } catch (\Exception $e) {
        Log::error('Error during OTP verification: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString(),
            'user_id' => $userId ?? 'undefined',
            'otp' => $request->otp ?? 'undefined',
        ]);
        return response()->json(['success' => false, 'message' => 'An error occurred while verifying the OTP.']);
    }
}
public function resend2FA(Request $request)
{
    // Retrieve user ID from session
    $userId = Session::get('two_factor:user:id');
    $user = User::find($userId);

    if (!$user) {
        return redirect()->route('login')->withErrors(['username' => 'Invalid session. Please login again.']);
    }

    // Generate a new 2FA code
    $otpCode = rand(100000, 999999);
    $user->two_factor_code = $otpCode;
    $user->two_factor_expires_at = now()->addMinutes(10);
    $user->save();

    // Check the selected method and resend OTP accordingly
    if ($request->input('2fa_method') === 'sms') {
        $this->sendSms($user->phone_number, $otpCode);
    } else {
        $this->sendEmail($user->email, $otpCode);
    }

    // Return a success message for the resend action
    return back()->with('resend_success', 'A new 2FA code has been sent to your phone or email.');
}


}