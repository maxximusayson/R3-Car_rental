<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;

class HomeController extends Controller
{
    public function editHomepage()
    {
        // Read current homepage content from file
        $mainContent = $this->readFileContents('home.blade.php');

        return view('cms.index', compact('mainContent'));
    }

    public function updateHomepage(Request $request)
    {
        // Validate the form input
        $request->validate([
            'main-content' => 'required',
        ]);

        // Update homepage content in the file
        $this->writeFileContents('home.blade.php', $request->input('main-content'));

        // Redirect back with success message
        return redirect()->route('homepage.edit')->with('success', 'Homepage content updated successfully!');
    }

    public function aboutUs()
    {
        // Example: Fetching content from a file
        $aboutUsContent = File::get(storage_path('app/about_us.txt')); // Adjust this based on your storage location

        return view('about_us', compact('aboutUsContent'));
    }

    public function updateAboutUs(Request $request)
    {
        // Validate and store updated content here
        // Example: Storing content in a file
        $request->validate([
            'about_us_content' => 'required|string',
        ]);

        File::put(storage_path('app/about_us.txt'), $request->input('about_us_content'));

        return redirect()->route('about_us')->with('success', 'About Us content updated successfully!');
    }

    public function index() {
        $cars = Car::all(); // Or whatever query you use to get cars
        return view('your-view', compact('cars'));
    }
    
}
