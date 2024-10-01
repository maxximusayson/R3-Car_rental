<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\Alert;
use App\Models\Audit;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function updateProfile(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . Auth::id(),
            'phone_number' => 'required|string|max:15',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $user = Auth::user();
        $oldProfilePicture = $user->profile_picture_url;
    
        // Update user profile fields
        $user->name = $request->input('name');
        $user->username = $request->input('username'); // Username input
        $user->phone_number = $request->input('phone_number'); // Phone number input
    
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


    public function uploadFiles(Request $request)
    {
        $request->validate([
            'valid_id' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'proof_of_billing' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validIdPath = $request->file('valid_id')->store('uploads', 'public');
        $proofOfBillingPath = $request->file('proof_of_billing')->store('uploads', 'public');

        session([
            'validIdImageUrl' => Storage::url($validIdPath),
            'proofOfBillingImageUrl' => Storage::url($proofOfBillingPath),
        ]);

        return redirect()->back()->with('success', 'Files uploaded successfully!');
    }
    public function removePicture(Request $request)
    {
        $user = Auth::user();
        
        // Set the profile picture to the default image
        $user->profile_picture_url = 'images/icons/default.jpg'; // Ensure this path is correct
        $user->save();
    
        // Optional: If you want to remove the physical file from storage (only if it exists)
        if ($user->profile_picture_url !== 'images/icons/default.jpg' && file_exists(public_path($user->profile_picture_url))) {
            unlink(public_path($user->profile_picture_url));
        }
    
        return redirect()->back()->with('success', 'Profile picture removed successfully.');
    }
    
    

}
