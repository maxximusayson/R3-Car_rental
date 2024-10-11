<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gallery;
use Illuminate\Support\Facades\File;

class GalleryController extends Controller
{
    // Store a new gallery image
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imageName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('images/gallery'), $imageName);

        Gallery::create([
            'filename' => $imageName,
            'title' => $request->input('title'), // Optional title
            'description' => $request->input('description'), // Optional description
        ]);

        return redirect()->back()->with('success', 'Image added successfully.');
    }

    // Update an existing gallery image
    public function update(Request $request, $id)
    {
        $gallery = Gallery::find($id);

        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Delete the old image
            File::delete(public_path('images/gallery/' . $gallery->filename));

            // Upload new image
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/gallery'), $imageName);

            // Update the filename in the database
            $gallery->filename = $imageName;
        }

        // Update title and description if available
        $gallery->title = $request->input('title');
        $gallery->description = $request->input('description');
        $gallery->save();

        return redirect()->back()->with('success', 'Image updated successfully.');
    }

    // Delete a gallery image
    public function destroy($id)
    {
        $gallery = Gallery::find($id);
        File::delete(public_path('images/gallery/' . $gallery->filename));
        $gallery->delete();

        return redirect()->back()->with('success', 'Image deleted successfully.');
    }
}

