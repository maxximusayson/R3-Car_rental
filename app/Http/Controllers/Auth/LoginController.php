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

    protected function redirectTo()
    {
        if (Auth::check() && Auth::user()->role == 'admin') {
            return RouteServiceProvider::ADMIN;
        }
        return RouteServiceProvider::HOME;
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login'); // Ensure your view is correctly set up
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

    // Rate limiting logic
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
        RateLimiter::clear($key); // Clear the rate limiter if login is successful
        return redirect()->intended($this->redirectPath());
    } else {
        RateLimiter::hit($key, $decayMinutes * 60); // Increment the rate limiter
    }

    return back()->withInput($request->only('email'))->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ]);
}
}