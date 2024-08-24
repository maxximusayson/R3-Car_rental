<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\RateLimiter;
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
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $key = Str::lower($request->input('email')) . '|' . $request->ip();
        $maxAttempts = 5;
        $decayMinutes = 15;

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'email' => "Too many login attempts. Please try again in " . ceil($seconds / 60) . " minutes."
            ])->withInput($request->only('email'));
        }

        if (Auth::attempt($request->only('email', 'password'))) {
            // Update active status
            Auth::user()->update(['active' => true]);

            RateLimiter::clear($key); // Clear the rate limiter if login is successful
            return redirect()->intended($this->redirectPath());
        } else {
            // Increment rate limiter if login fails
            RateLimiter::hit($key, $decayMinutes * 60);
        }

        return back()->withInput($request->only('email'))->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
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
