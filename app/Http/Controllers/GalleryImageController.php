<?php

namespace App\Http\Controllers;

use App\Models\GalleryImage;
use Illuminate\Http\Request;

class GalleryImageController extends Controller
{
    // Display all gallery images.
    public function index()
    {
        $galleryImages = GalleryImage::all();
        return view('home', compact('galleryImages'));
    }

    // Store a new gallery image.
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle the file upload without using Storage
        $image = $request->file('image');
        $imageName = time() . '_' . $image->getClientOriginalName();
        $imagePath = 'images/gallery/' . $imageName;

        // Move the uploaded file to the public/images/gallery directory
        $image->move(public_path('images/gallery'), $imageName);

        // Save the image path to the database
        GalleryImage::create(['image_path' => $imagePath]);

        return redirect()->back()->with('success', 'Image added successfully!');
    }

    // Delete a gallery image.
    public function destroy(GalleryImage $galleryImage)
    {
        // Define the path to the image
        $imagePath = public_path($galleryImage->image_path);

        // Check if the file exists and delete it
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        // Delete the database record
        $galleryImage->delete();

        return redirect()->back()->with('success', 'Image deleted successfully!');
    }
}
