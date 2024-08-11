<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UploadedFile;

class UploadController extends Controller
{
    public function uploadFiles(Request $request)
    {
        // Validate the uploaded files
        $request->validate([
            'valid_id' => 'required|image',
            'proof_of_billing' => 'required|image',
        ]);
    
        // Store the uploaded files
        $validIdImage = $request->file('valid_id')->store('public/uploads');
        $proofOfBillingImage = $request->file('proof_of_billing')->store('public/uploads');
    
        // Store the file records in the database
        UploadedFile::create([
            'filename' => $validIdImage,
            'user_id' => Auth::id(),
        ]);
    
        UploadedFile::create([
            'filename' => $proofOfBillingImage,
            'user_id' => Auth::id(),
        ]);
    
        // Pass the URLs of the uploaded files to the client details view
        return redirect()->route('userDetails')->with([
            'validIdImageUrl' => asset('storage/' . $validIdImage),
            'proofOfBillingImageUrl' => asset('storage/' . $proofOfBillingImage),
        ]);
    }
}    
