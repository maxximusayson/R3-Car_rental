<?php

// app/Http/Controllers/AboutUsController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AboutUsController extends Controller
{
    // Method to display the edit form for the About Us section
    public function edit()
    {
        // Fetch the About Us content from the database
        $aboutUs = DB::table('cms_sections')->where('section_name', 'about_us')->first();

        // Pass the content to the view
        return view('about_us.edit', compact('aboutUs'));
    }

    // Method to update the About Us section content
    public function update(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'content' => 'required|string',
        ]);

        // Update the content in the database
        DB::table('cms_sections')
            ->where('section_name', 'about_us')
            ->update(['content' => $request->input('content')]);

        // Redirect back to the manage page with success message
        return redirect()->route('cms.manage')->with('success', 'About Us section updated successfully.');
    }
}
w
