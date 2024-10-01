<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SmsService;
use App\Models\Otp;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException; // Import PHPMailer's Exception class correctly


class OTPController extends Controller
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    // Existing SMS OTP Sending Function
    public function sendOtp(Request $request)
    {
        // Validate phone number input
        $request->validate([
            'phone_number' => 'required|numeric|digits:10',
        ]);

        // Check if OTP has already been sent in this session
        if (session()->has('otp_sent')) {
            return back()->with('error', 'OTP has already been sent. Please check your phone.');
        }

        $phone = '63' . $request->input('phone_number');
        $otp = rand(100000, 999999);

        // Store OTP in the session
        session(['otp' => $otp]);

        // Store OTP in the database
        Otp::updateOrCreate(
            ['phone' => $phone],
            ['otp' => $otp]
        );

        try {
            // Send the OTP via SMS Service
            $this->smsService->sendOtp($phone, $otp);

            // Store the phone number and OTP sent status in the session
            session(['phone' => $phone, 'otp_sent' => true]);

            // Redirect to the OTP verification page with a success message
            return redirect()->route('verify.otp')->with('success', 'OTP sent successfully.');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to send OTP: ' . $e->getMessage());
        }
    }

    // Email OTP Sending Function
   // app/Http/Controllers/OTPController.php

 

 public function sendEmailOtp(Request $request)
{
    $request->validate([
        'email' => 'required|email',
    ]);

    $otp = rand(100000, 999999);
    $email = $request->input('email');
    session(['otp' => $otp]);

    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = env('MAIL_USERNAME'); // Your Gmail username
        $mail->Password = env('MAIL_PASSWORD'); // Your Gmail App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Your 2FA Code';
        $mail->Body = "<h2>Your OTP is: $otp</h2> Please enter this code to verify your account.";

        $mail->send();

        return response()->json(['success' => true, 'message' => 'OTP sent successfully via email.']);
    } catch (PHPMailerException $e) {
        // Log and return the error for better debugging
        \Log::error('Failed to send OTP: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'Failed to send OTP: ' . $e->getMessage()]);
    }
}
   



    


    public function verifyOtp(Request $request)
    {
        // Validate the OTP input
        $request->validate([
            'otp' => 'required|numeric|digits:6',
        ]);

        $otp = $request->input('otp');
        $sessionOtp = Session::get('otp');

        // Retrieve the register data from the session
        $registerData = session('register_data');

        if ($otp == $sessionOtp) {
            // OTP is valid, proceed with user creation and login

            // Create the user account including email
            $user = User::create([
                'name' => $registerData['name'],
                'email' => $registerData['email'], // Ensure to include email
                'username' => $registerData['username'],
                'phone_number' => $registerData['phone_number'], // Correct field name
                'password' => Hash::make($registerData['password']),
            ]);

            // Log the user in
            Auth::login($user);

            // Clear session data
            Session::forget(['otp', 'register_data', 'otp_sent']);

            // Redirect to the home page or dashboard
            return redirect()->route('home')->with('success', 'Account created and OTP verified successfully. You are now logged in.');
        } else {
            // Handle invalid OTP
            return back()->with('error', 'Invalid OTP. Please try again.');
        }
    }
}
