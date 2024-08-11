<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AuditTrail;
use App\Models\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function show(User $user)
    {
        // Create an audit trail entry
        AuditTrail::create([
            'action' => 'Viewed user details',
            'user_id' => Auth::id(),
        ]);

        // Retrieve uploaded files for the user
        $uploadedFiles = UploadedFile::where('user_id', $user->id)->get();

        // Pass the user and uploaded files to the view
        return view('admin.clientDetails', compact('user', 'uploadedFiles'));
    }

    public function showClientDetails()
    {
        // Retrieve uploaded files for the authenticated user
        $uploadedFiles = UploadedFile::where('user_id', Auth::id())->get();

        // Pass the uploaded files to the view
        return view('clientDetails', ['uploadedFiles' => $uploadedFiles]);
    }
    public function uploadProfilePicture(Request $request)
{
    $request->validate([
        'profile_picture' => 'required|image|mimes:jpg,png,jpeg|max:2048',
    ]);

    $user = Auth::user();
    if ($request->hasFile('profile_picture')) {
        // Delete the old profile picture if it exists
        if ($user->profile_picture && \Storage::exists(str_replace('/storage/', '', $user->profile_picture))) {
            \Storage::delete(str_replace('/storage/', '', $user->profile_picture));
        }

        // Store the new profile picture
        $image = $request->file('profile_picture');
        $imagePath = $image->store('profile_pictures', 'public');
        $user->profile_picture = '/storage/' . $imagePath;
        $user->save();
    }

    return redirect()->back()->with('success', 'Profile picture updated successfully!');
}

}
