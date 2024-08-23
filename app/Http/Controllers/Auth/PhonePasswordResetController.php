<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class PhonePasswordResetController extends Controller
{
    public function sendResetLinkViaPhone(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|numeric|exists:users,phone',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Assuming you have a way to send an SMS or handle phone-based password reset.
        // For example, you might generate a token and send it via SMS.

        $token = Str::random(60);
        DB::table('password_resets')->insert([
            'email' => $request->phone,
            'token' => Hash::make($token),
            'created_at' => now(),
        ]);

        // Here you would send the token to the user's phone via SMS.

        return back()->with('status', 'A password reset link has been sent to your phone.');
    }
}
