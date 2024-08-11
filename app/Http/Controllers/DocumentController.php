<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DocumentController extends Controller
{
    /**
     * Handle file upload for driver's license and proof of billing.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function upload(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'drivers_license' => 'required|file|mimes:jpeg,png,pdf|max:2048', // Adjust file types and size as needed
            'proof_of_billing' => 'required|file|mimes:jpeg,png,pdf|max:2048', // Adjust file types and size as needed
        ]);

        // Store the uploaded files
        $driversLicensePath = $request->file('drivers_license')->store('uploads');
        $proofOfBillingPath = $request->file('proof_of_billing')->store('uploads');

        // Here, you may associate the file paths with the user or reservation as needed
        // For example, if you have a User model, you can associate the file paths like this:
        // auth()->user()->update([
        //     'drivers_license_path' => $driversLicensePath,
        //     'proof_of_billing_path' => $proofOfBillingPath,
        // ]);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Documents uploaded successfully.');
    }
}
	