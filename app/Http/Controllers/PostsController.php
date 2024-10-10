<?php

namespace App\Http\Controllers;

use App\Models\AboutUs;
use App\Models\Post; // Ensure you import your Post model
use Illuminate\Http\Request;

class PostsController extends Controller
{
    // Method to list all posts (index)

    public function index()
    {
        // Fetch all posts
        $posts = Post::all(); // Fetch posts from the database

        // Pass the posts to the home view
        return view('home', compact('posts')); // Change 'posts.index' to 'home'
    }
    


    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validation rules
        ]);
    
        $post = new Post();
        $post->title = $request->title;
        $post->content = $request->content;
    
        // Handle image upload
        if ($request->hasFile('image')) {
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('images/posts'), $imageName); // Save image in public/images/posts/
            $post->image_path = $imageName; // Save only the filename in the database
        }
    
        $post->save();
    
        return redirect()->back()->with('success', 'Post created successfully.');
    }


    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validation rules
    ]);

    $post = Post::findOrFail($id);
    $post->title = $request->title;
    $post->content = $request->content;

    // Handle image update
    if ($request->hasFile('image')) {
        // Optionally delete the old image if necessary
        if ($post->image_path) {
            $oldImagePath = public_path('images/posts/' . $post->image_path);
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath); // Delete the old image
            }
        }

        $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
        $request->file('image')->move(public_path('images/posts'), $imageName); // Save new image
        $post->image_path = $imageName; // Save only the new filename in the database
    }

    $post->save();

    return redirect()->back()->with('success', 'Post updated successfully.');
}



    public function destroy($id)
{
    // Find the post by ID and delete it
    $post = Post::findOrFail($id);

    // If the post has an image, delete the image from the public folder
    if ($post->image_path && file_exists(public_path($post->image_path))) {
        unlink(public_path($post->image_path));
    }

    // Delete the post
    $post->delete();

    // Redirect with a success message
    return redirect()->route('cms.manage')->with('success', 'Post deleted successfully!');
}

    public function show($id)
{
    // Fetch the post by ID
    $post = Post::findOrFail($id); // This will throw a 404 if not found

    // Pass the post to the view
    return view('posts.show', compact('post')); // Adjust view name accordingly
}
}
