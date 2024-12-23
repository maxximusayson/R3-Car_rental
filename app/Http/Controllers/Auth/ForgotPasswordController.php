<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetCode(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|numeric|digits:11|exists:users,phone_number',
        ]);

        $user = User::where('phone_number', $request->phone_number)->first();
        $resetToken = Str::random(6); // Generate a random 6-digit token

        // Save the token in the database
        $user->reset_token = $resetToken;
        $user->save();

        // Send the reset code via SMS using Semaphore API
        $this->sendSms($user->phone_number, $resetToken);

        // Store the phone number in session to use in the verify code page
        session(['phone_number' => $request->phone_number]);

        return redirect()->route('password.verify')
            ->with('message', 'A verification code has been sent to your phone number.');
    }

    public function showVerifyCodeForm()
    {
        $phoneNumber = session('phone_number');

        if (!$phoneNumber) {
            return redirect()->route('password.request')->with('error', 'Please provide a phone number.');
        }

        return view('auth.verify-code', compact('phoneNumber'));
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|numeric|digits:11',
            'reset_code' => 'required|string|min:6|max:6',
        ]);

        $user = User::where('phone_number', $request->phone_number)
                    ->where('reset_token', $request->reset_code)
                    ->first();

        if (!$user) {
            return back()->withErrors(['reset_code' => 'Invalid verification code.']);
        }

        // Code is correct, clear session data related to phone number
        session(['verified_phone_number' => $request->phone_number]);

        // Redirect directly to reset password page with the token
        return redirect()->route('password.reset', ['token' => $user->reset_token])
            ->with('message', 'Verification successful. You can now reset your password.');
    }

    public function showResetPasswordForm($token)
{
    $phoneNumber = session('verified_phone_number');

    if (!$phoneNumber) {
        return redirect()->route('password.request')->with('error', 'Please verify your phone number first.');
    }

    return view('auth.reset-password', compact('phoneNumber', 'token'));
}


public function resetPassword(Request $request)
{
    // Validate the incoming request
    $request->validate([
        'token' => 'required',
        'username' => 'required|string|exists:users,username',
        'password' => 'required|confirmed|min:8',
    ]);

    // Find the user by username and token
    $user = User::where('username', $request->username)
                ->where('reset_token', $request->token)
                ->first();

    if (!$user) {
        return back()->withErrors(['username' => 'Invalid token or username.']);
    }

    // Update the user's password
    $user->password = Hash::make($request->password);
    $user->reset_token = null; // Clear the reset token after successful password reset
    $user->save();

    // Clear the session
    session()->forget(['verified_phone_number', 'phone_number']);

    // Redirect to the login page with a success message
    return redirect()->route('login')->with('message', 'Your password has been successfully reset!');
}


    protected function sendSms($phoneNumber, $resetToken)
    {
        $apiKey = config('services.semaphore.api_key');
        $senderName = config('services.semaphore.sender_name');

        $message = "Your password reset code is: $resetToken";

        $response = Http::post('https://api.semaphore.co/api/v4/messages', [
            'apikey' => $apiKey,
            'number' => $phoneNumber,
            'message' => $message,
            'sendername' => $senderName,
        ]);

        return $response->successful();
    }
}
