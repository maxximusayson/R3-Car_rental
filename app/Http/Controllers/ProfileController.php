<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\Alert;
use App\Models\Audit;

class ProfileController extends Controller
{
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $oldProfilePicture = $user->profile_picture_url;

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone_number = $request->input('phone_number');

        if ($request->hasFile('profile_picture')) {
            // Remove old profile picture if exists
            if ($oldProfilePicture) {
                $oldImage = public_path('profile_pictures/' . basename($oldProfilePicture));
                if (File::exists($oldImage)) {
                    File::delete($oldImage);
                }
            }

            // Store the new profile picture in the public directory
            $path = $request->file('profile_picture')->move(public_path('profile_pictures'), $request->file('profile_picture')->getClientOriginalName());
            $user->profile_picture_url = 'profile_pictures/' . $request->file('profile_picture')->getClientOriginalName();

            // Create alert
            Alert::create([
                'user_id' => $user->id,
                'message' => 'You uploaded a new profile picture',
            ]);

            // Log in audit trail
            Audit::create([
                'user_id' => $user->id,
                'action' => 'Profile picture updated',
                'details' => 'New profile picture uploaded: ' . $user->profile_picture_url,
            ]);
        }

        $user->save();

        // Log other profile changes
        Audit::create([
            'user_id' => $user->id,
            'action' => 'Profile updated',
            'details' => 'User updated their profile information',
        ]);

        return back()->with('status', 'Profile updated successfully!');
    }
}