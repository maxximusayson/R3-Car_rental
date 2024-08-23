<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FileUploadController extends Controller
{
    /**
     * Handle file upload.
     */
    public function upload(Request $request)
    {
        // Validation rules
        $request->validate([
            'valid_id' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'proof_of_billing' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Check if user is authenticated
        if (Auth::check()) {
            // Handle valid ID upload
            if ($request->hasFile('valid_id')) {
                // Process the uploaded file
                $validIdFile = $request->file('valid_id');
                $validIdFileName = time() . '_valid_id_' . $validIdFile->getClientOriginalName();
                $validIdFile->move(public_path('uploads'), $validIdFileName);
                $validIdImageUrl = asset('uploads/' . $validIdFileName);

                // Save file information to the database with user ID
                UploadedFile::create([
                    'filename' => $validIdFileName,
                    'path' => $validIdImageUrl,
                    'user_id' => Auth::id(), // Get authenticated user's ID
                ]);

                // Store the valid ID image URL in the session
                $request->session()->put('validIdImageUrl', $validIdImageUrl);
            }

            // Handle proof of billing upload (similar process as valid ID)
            if ($request->hasFile('proof_of_billing')) {
                // Process the uploaded file
                $proofOfBillingFile = $request->file('proof_of_billing');
                $proofOfBillingFileName = time() . '_proof_of_billing_' . $proofOfBillingFile->getClientOriginalName();
                $proofOfBillingFile->move(public_path('uploads'), $proofOfBillingFileName);
                $proofOfBillingImageUrl = asset('uploads/' . $proofOfBillingFileName);

                // Save file information to the database with user ID
                UploadedFile::create([
                    'filename' => $proofOfBillingFileName,
                    'path' => $proofOfBillingImageUrl,
                    'user_id' => Auth::id(), // Get authenticated user's ID
                ]);

                // Store the proof of billing image URL in the session
                $request->session()->put('proofOfBillingImageUrl', $proofOfBillingImageUrl);
            }
        } else {
            // Handle the case where the user is not authenticated
            // You may want to log this event or handle it differently based on your application's requirements
        }

        // Redirect back with success message
        return redirect()->back()->with('success', 'Files uploaded successfully.');
    }

    /**
     * Show the form for uploading files and display uploaded files.
     */
    public function showForm()
    {
        // Initialize variables
        $validIdImageUrl = null;
        $proofOfBillingImageUrl = null;
        $uploadedFiles = [];

        // Check if user is authenticated
        if (Auth::check()) {
            // Get uploaded files from the database for the authenticated user
            $uploadedFiles = UploadedFile::where('user_id', Auth::id())->get();

            // If session data is available, use it to set image URLs
            $validIdImageUrl = session('validIdImageUrl');
            $proofOfBillingImageUrl = session('proofOfBillingImageUrl');

            // If session data is not available or empty, loop through uploaded files to find URLs
            if (!$validIdImageUrl || !$proofOfBillingImageUrl) {
                foreach ($uploadedFiles as $file) {
                    if (strpos($file->filename, 'valid_id') !== false) {
                        $validIdImageUrl = $file->path;
                    } elseif (strpos($file->filename, 'proof_of_billing') !== false) {
                        $proofOfBillingImageUrl = $file->path;
                    }
                }
            }
        }

        // Return the view with image URLs and uploaded files
        return view('clientDetails', compact('validIdImageUrl', 'proofOfBillingImageUrl', 'uploadedFiles'));
    }


    public function uploadFiles(Request $request)
{
    // Validate the files
    $request->validate([
        'valid_id' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'proof_of_billing' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    // Handle Valid ID Upload
    if ($request->hasFile('valid_id')) {
        $validIdPath = $request->file('valid_id')->store('uploads', 'public');
    }

    // Handle Proof of Billing Upload
    if ($request->hasFile('proof_of_billing')) {
        $proofOfBillingPath = $request->file('proof_of_billing')->store('uploads', 'public');
    }

    // Store the file paths in session
    session([
        'validIdImageUrl' => Storage::url($validIdPath),
        'proofOfBillingImageUrl' => Storage::url($proofOfBillingPath),
    ]);

    // Redirect back to the form with success message
    return back()->with([
        'validIdImageUrl' => Storage::url($validIdPath),
        'proofOfBillingImageUrl' => Storage::url($proofOfBillingPath),
    ]);
}
}