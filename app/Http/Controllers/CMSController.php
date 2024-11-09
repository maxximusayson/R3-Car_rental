<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\GalleryImage;
use App\Models\HomepageSection;
use App\Models\Post;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CMSController extends Controller
{
    // Display all sections
    public function manage()
    {
        $sections = DB::table('cms_sections')->get();
        $aboutUs = DB::table('cms_sections')->where('section_name', 'about_us')->first();
        if (!$aboutUs) {
            DB::table('cms_sections')->insert([
                'section_name' => 'about_us',
                'content' => 'Default content for About Us.',
            ]);
            $aboutUs = DB::table('cms_sections')->where('section_name', 'about_us')->first();
        }

        $posts = Post::all();
        $galleryImages = GalleryImage::all(); // Fetch all gallery images

        return view('cms.manage', compact('sections', 'aboutUs', 'posts', 'galleryImages'));
    }
    

    

    // Edit a specific section
    public function edit($section_name)
    {
        $section = Section::where('section_name', $section_name)->first();
        $sections = Section::all(); // Get all sections for display
    
        if (!$section) {
            return redirect()->route('cms.manage')->with('error', 'Section not found.');
        }
    
        return view('your_view_name', compact('section', 'sections')); // Pass both variables
    }

    // Update a specific section
    public function update(Request $request, $section_name)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $section = Section::where('section_name', $section_name)->firstOrFail();
        $section->content = $request->input('content');
        $section->save();

        return redirect()->route('cms.manage')->with('success', 'Section updated successfully!');
    }

    public function editAboutUs()
    {
        // Fetch the About Us content
        $aboutUs = DB::table('cms_sections')->where('section_name', 'about_us')->first();
    
        return view('cms.edit_about_us', compact('aboutUs'));
    }

    public function updateAboutUs(Request $request)
    {
        // Validate the request
        $request->validate([
            'content' => 'required|string',
        ]);
        
        // Update the About Us content in the database
        DB::table('cms_sections')
            ->where('section_name', 'about_us')
            ->update(['content' => $request->input('content')]);
    
        return redirect()->route('cms.manage')->with('success', 'About Us section updated successfully.');
    }
    // In your controller
    public function showHomePage()
    {
        $aboutUs = DB::table('cms_sections')->where('section_name', 'about_us')->first();
        $posts = Post::with('images')->latest()->take(5)->get();
        $cars = Car::with('images')->take(3)->get();  // Fetching cars
        $galleryImages = GalleryImage::all();

        return view('home', compact('aboutUs', 'posts', 'cars', 'galleryImages'));
    }
    
    
    

    
    
    public function storePost(Request $request)
    {
        // Validate the request
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle the image upload if it exists
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts', 'public');
        }

        // Create the new post
        Post::create([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'image_path' => $imagePath,
        ]);

        return redirect()->route('cms.manage')->with('success', 'Post added successfully!');
    }

   public function storeGalleryImage(Request $request)
{
    $request->validate([
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Check if the image file is being received correctly
    if (!$request->hasFile('image')) {
        return back()->with('error', 'No image file found.');
    }

    $image = $request->file('image');

    // Check if the image is valid
    if (!$image->isValid()) {
        return back()->with('error', 'Uploaded image is not valid.');
    }

    $imageName = time() . '_' . $image->getClientOriginalName();
    $imagePath = 'images/gallery/' . $imageName;

    // Try moving the uploaded file to the public/images/gallery directory
    try {
        $image->move(public_path('images/gallery'), $imageName);
    } catch (\Exception $e) {
        return back()->with('error', 'Failed to upload image: ' . $e->getMessage());
    }

    // Save the image path to the database
    GalleryImage::create(['image_path' => $imagePath]);

    return redirect()->route('cms.manage')->with('success', 'Gallery image added successfully!');
}


    public function destroyGalleryImage(GalleryImage $galleryImage)
    {
        $imagePath = public_path($galleryImage->image_path);

        // Check if the file exists and delete it
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        $galleryImage->delete();

        return redirect()->route('cms.manage')->with('success', 'Gallery image deleted successfully!');
    }
    
}