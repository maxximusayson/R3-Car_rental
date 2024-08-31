<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\HomepageSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class HomeController extends Controller
{
    public function editHomepage()
    {
        // Fetch the content for different sections from the database
        $carousel_section = HomepageSection::where('section_name', 'carousel')->first();
        $welcome_section = HomepageSection::where('section_name', 'welcome')->first();
        $about_us_section = HomepageSection::where('section_name', 'about_us')->first();
        $why_choose_us_section = HomepageSection::where('section_name', 'why_choose_us')->first();
        $gallery_section = HomepageSection::where('section_name', 'gallery')->first();

        return view('cms.index', compact('carousel_section', 'welcome_section', 'about_us_section', 'why_choose_us_section', 'gallery_section'));
    }

    public function updateHomepage(Request $request)
    {
        // Validate the form input
        $request->validate([
            'carousel_section' => 'required|string',
            'welcome_section' => 'required|string',
            'about_us_section' => 'required|string',
            'why_choose_us_section' => 'required|string',
            'gallery_section' => 'required|string',
        ]);

        // Update each section content in the database
        HomepageSection::where('section_name', 'carousel')->update(['content' => $request->input('carousel_section')]);
        HomepageSection::where('section_name', 'welcome')->update(['content' => $request->input('welcome_section')]);
        HomepageSection::where('section_name', 'about_us')->update(['content' => $request->input('about_us_section')]);
        HomepageSection::where('section_name', 'why_choose_us')->update(['content' => $request->input('why_choose_us_section')]);
        HomepageSection::where('section_name', 'gallery')->update(['content' => $request->input('gallery_section')]);

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

        // Fetch the dynamic content for the homepage, with default fallbacks if sections are not found
        $carousel_section = HomepageSection::where('section_name', 'carousel')->first() ?? (object)['content' => '<div>Default Carousel Content</div>'];
        $welcome_section = HomepageSection::where('section_name', 'welcome')->first() ?? (object)['content' => '<h1>Welcome to Our Website!</h1>'];
        $about_us_section = HomepageSection::where('section_name', 'about_us')->first() ?? (object)['content' => '<p>Default About Us Content</p>'];
        $why_choose_us_section = HomepageSection::where('section_name', 'why_choose_us')->first() ?? (object)['content' => '<p>Default Why Choose Us Content</p>'];
        $gallery_section = HomepageSection::where('section_name', 'gallery')->first() ?? (object)['content' => '<div>Default Gallery Content</div>'];

        return view('home', compact('cars', 'carousel_section', 'welcome_section', 'about_us_section', 'why_choose_us_section', 'gallery_section'));
    }

    // Helper methods to read and write files (if needed)
    private function readFileContents($filename)
    {
        return File::get(resource_path('views/' . $filename));
    }

    private function writeFileContents($filename, $content)
    {
        return File::put(resource_path('views/' . $filename), $content);
    }

    public function manage(Request $request)
    {
        $sectionName = $request->query('section_name');

        if ($sectionName) {
            $section = HomepageSection::where('section_name', $sectionName)->firstOrFail();
            return view('cms.manage', ['section' => $section]);
        }

        $sections = HomepageSection::all();
        return view('cms.manage', ['sections' => $sections]);
    }

    public function update(Request $request, $sectionName)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $section = HomepageSection::where('section_name', $sectionName)->firstOrFail();
        $section->content = $request->input('content');
        $section->save();

        return redirect()->route('cms.manage')->with('success', 'Section updated successfully!');
    }
}
