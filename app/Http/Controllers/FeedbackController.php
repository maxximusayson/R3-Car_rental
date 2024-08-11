<?php

// app/Http/Controllers/FeedbackController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications\FeedbackSubmitted;
use Illuminate\Support\Facades\Notification;
use App\Models\User; // Assuming you have a User model

class FeedbackController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'feedback' => 'required|string|max:255',
        ]);

        $feedback = $request->input('feedback');

        // Notify the admin users
        $adminUsers = User::where('is_admin', true)->get(); // Assuming you have an 'is_admin' field
        Notification::send($adminUsers, new FeedbackSubmitted($feedback));

        return response()->json(['success' => true]);
    }
}
