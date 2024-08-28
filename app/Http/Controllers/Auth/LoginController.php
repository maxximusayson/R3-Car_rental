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
use Illuminate\Support\Str;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Define where to redirect users after login based on their role.
     *
     * @return string
     */
    protected function redirectTo()
    {
        if (Auth::check()) {
            return $this->getRedirectPath(Auth::user()->role);
        }

        return RouteServiceProvider::HOME; // Fallback
    }

    /**
     * Determine the redirect path based on the user role.
     *
     * @param string $role
     * @return string
     */
    protected function getRedirectPath($role)
    {
        switch ($role) {
            case 'admin':
                return RouteServiceProvider::ADMIN;
            case 'user':
                return RouteServiceProvider::HOME;
            // Add more roles and their respective redirects here
            default:
                return RouteServiceProvider::HOME; // Default redirect if role is not matched
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
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required',
        ]);
    
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
    
        $key = Str::lower($request->input('username')) . '|' . $request->ip();
        $maxAttempts = 5;
        $decayMinutes = 15;
    
        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'username' => "Too many login attempts. Please try again in " . ceil($seconds / 60) . " minutes."
            ])->withInput($request->only('username'));
        }
    
        $remember = $request->has('remember');
    
        if (Auth::attempt($request->only('username', 'password'), $remember)) {
            $user = Auth::user();

            // Check if the user is an admin and bypass 2FA if required
            if ($user->role === 'admin') {
                return $this->sendLoginResponse($request);
            }

            // Generate 2FA code for non-admin users or if 2FA is not bypassed
            $user->two_factor_code = rand(100000, 999999);
            $user->two_factor_expires_at = now()->addMinutes(10);
            $user->save();
    
            // Send the 2FA code via SMS (assuming you have this method implemented)
            $this->sendSms($user->phone_number, $user->two_factor_code);
    
            // Store the user's ID in the session for 2FA verification
            Session::put('two_factor:user:id', $user->id);
    
            // Log out the user to prevent full login until 2FA is verified
            Auth::logout();
    
            // Redirect to the 2FA verification page
            return redirect()->route('2fa.verify');
        } else {
            RateLimiter::hit($key, $decayMinutes * 60);
        }
    
        return back()->withInput($request->only('username'))->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ]);
    }

    public function show2FAVerifyForm()
    {
        return view('auth.2fa-verify');
    }
    
    public function verify2FA(Request $request)
    {
        $request->validate([
            'two_factor_code' => 'required|numeric|digits:6',
        ]);
    
        $userId = Session::get('two_factor:user:id');
        $user = User::find($userId);
    
        if (!$user) {
            return redirect()->route('login')->withErrors(['username' => 'Invalid session. Please login again.']);
        }
    
        if ($user->two_factor_code === $request->two_factor_code && now()->lessThan($user->two_factor_expires_at)) {
            $user->two_factor_code = null;
            $user->two_factor_expires_at = null;
            $user->save();
    
            // Log the user in manually after successful 2FA
            Auth::login($user);
    
            // Clear the session
            Session::forget('two_factor:user:id');
    
            return redirect()->intended($this->redirectPath());
        }
    
        return back()->withErrors(['two_factor_code' => 'The verification code is incorrect or expired.']);
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

        return $response->successful();
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            // Update active status to false
            Auth::user()->update(['active' => false]);

            // Log the user out of the application
            Auth::logout();

            // Invalidate the session
            $request->session()->invalidate();

            // Regenerate the session token
            $request->session()->regenerateToken();
        }

        // Redirect to the home page or login page
        return redirect('/');
    }
}
