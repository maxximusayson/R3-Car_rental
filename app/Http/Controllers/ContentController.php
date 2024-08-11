<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Content; // Assuming you have a Content model

class ContentController extends Controller
{
    public function editHomepage()
    {
        $mainContent = Content::where('type', 'homepage')->first()->content ?? '';
        return view('edit-homepage', compact('mainContent'));
    }

    public function updateHomepage(Request $request)
    {
        $request->validate([
            'main-content' => 'required|string',
        ]);

        Content::updateOrCreate(
            ['type' => 'homepage'],
            ['content' => $request->input('main-content')]
        );

        return redirect()->route('homepage.edit')->with('success', 'Homepage content updated successfully!');
    }

    public function editAboutUs()
    {
        $aboutUsContent = Content::where('type', 'about_us')->first()->content ?? '';
        return view('edit-about-us', compact('aboutUsContent'));
    }

    public function updateAboutUs(Request $request)
    {
        $request->validate([
            'about_us_content' => 'required|string',
        ]);

        Content::updateOrCreate(
            ['type' => 'about_us'],
            ['content' => $request->input('about_us_content')]
        );

        return redirect()->route('about_us.edit')->with('success', 'About Us content updated successfully!');
    }
}   