<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class ContactController extends Controller
{
    public function submit(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        try {
            // Create a new notification
            Notification::create($validated);

            // Redirect with success message
            return redirect()->back()->with('success', 'Message sent successfully.');
        } catch (\Exception $e) {
            // Handle the error and provide feedback
            return redirect()->back()->withErrors('An error occurred while sending your message.');
        }
    }
}
