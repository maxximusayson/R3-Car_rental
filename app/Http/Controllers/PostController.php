<?php

namespace App\Http\Controllers;

use App\Models\AboutUs;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // Display a listing of the posts
    public function index()
    {
        $posts = Post::all(); // Fetch all posts
        $aboutUs = AboutUs::first(); // Fetch the About Us content if needed
        return view('home', compact('posts')); // Return to home view
    }

    // Show the form for creating a new post
    public function create()
    {
        return view('posts.create');
    }

    // Store a newly created post in storage
    public function store(Request $request)
{
    // Validate the incoming request data
    $request->validate([
        'title' => 'required|max:255',
        'content' => 'required',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Create a new Post instance
    $post = new Post();
    $post->title = $request->input('title');
    $post->content = $request->input('content');

    // Check if an image file is uploaded
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imageName = time() . '.' . $image->getClientOriginalExtension(); // Unique file name
        $image->move(public_path('images/posts'), $imageName); // Store image
        $post->image_path = 'images/posts/' . $imageName; // Save relative path
    } else {
        $post->image_path = null; // Set to null if no image
    }

    // Save the post to the database
    $post->save();

    // Redirect back with a success message
    return redirect()->back()->with('success', 'Post added successfully!');
}

    

    // Display the specified post
    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    // Show the form for editing the specified post
    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    // Update the specified post in storage
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        
        // Validate input
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        // Update the post
        $post->title = $request->input('title');
        $post->content = $request->input('content');
    
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts', 'public');
            $post->image = $imagePath;
        }
    
        $post->save();
    
        return redirect()->back()->with('success', 'Post updated successfully');
    }
    

    // Remove the specified post from storage
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        return redirect()->back()->with('success', 'Post deleted successfully!');
    }

}
