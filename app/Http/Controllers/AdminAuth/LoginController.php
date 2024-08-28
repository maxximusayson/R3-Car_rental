<?php

namespace App\Http\Controllers\AdminAuth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
}
