@extends('layouts.myapp1')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<div class="container mt-5 mb-5">
    <h1 class="mb-5 text-center text-dark fw-bold">Admin Panel: Manage Homepage Content</h1>

    <!-- Success Message -->
    @if(session('success'))
        <div class="container mb-4">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    <div class="row">
        <!-- GCash Status Container -->
        <div class="col-md-6 mb-4">
            <div class="gcash-status-container p-4 border rounded shadow-sm" style="height: 100%;">
                <h3 style="font-size: 1.5rem; margin-bottom: 20px; color: #333;">GCash Status</h3>
                <label class="switch">
                    <input type="checkbox" id="toggle-gcash" data-status="{{ env('GCASH_ENABLED', true) ? 'enabled' : 'disabled' }}" {{ env('GCASH_ENABLED', true) ? 'checked' : '' }}>
                    <span class="slider round"></span>
                </label>
                <script>
                    document.getElementById('toggle-gcash').addEventListener('change', function () {
                        const isEnabled = this.checked;
                        fetch('/admin/gcash/toggle', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({ enabled: isEnabled })
                        })
                        .then(response => response.json())
                        .then(data => {
                            alert(data.message);
                        })
                        .catch(error => console.error('Error:', error));
                    });
                </script>
            </div>
            <!-- Toggle Switch Styling -->
            <style>
                /* Style for the toggle switch */
                .switch {
                    position: relative;
                    display: inline-block;
                    width: 60px;
                    height: 34px;
                }

                .switch input {
                    opacity: 0;
                    width: 0;
                    height: 0;
                }

                .slider {
                    position: absolute;
                    cursor: pointer;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background-color: #ccc;
                    transition: 0.4s;
                    border-radius: 34px;
                }

                .slider:before {
                    position: absolute;
                    content: "";
                    height: 26px;
                    width: 26px;
                    border-radius: 50%;
                    left: 4px;
                    bottom: 4px;
                    background-color: white;
                    transition: 0.4s;
                }

                input:checked + .slider {
                    background-color: #4CAF50;
                }

                input:checked + .slider:before {
                    transform: translateX(26px);
                }
            </style>
        </div>

        <!-- Editable About Us Section -->
        <div class="col-md-6 mb-4">
            <div class="container p-4 border rounded shadow-sm" style="height: 100%;">
                <h3 class="text-center mb-3">About Us Section</h3>
                @if($aboutUs)
                    <form action="{{ route('cms.aboutUs.update') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="about_us_content" class="form-label">Edit About Us Content</label>
                            <textarea class="form-control" id="about_us_content" name="content" rows="4" required>{{ $aboutUs->content ?? '' }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">SAVE CHANGES</button>
                    </form>
                @else
                    <div class="alert alert-warning mt-3">Content not available.</div>
                @endif
            </div>
        </div>

        <!-- Add Post Section -->
        <div class="col-md-6 mb-4">
            <div class="container p-4 border rounded shadow-sm" style="height: 100%;">
                <h3 class="text-center mb-3">Manage Posts</h3>
                <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="title" class="form-label">Post Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">Content</label>
                        <textarea class="form-control" id="content" name="content" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Image (optional)</label>
                        <input type="file" class="form-control" id="image" name="image">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Add Post</button>
                </form>
            </div>
        </div>

        <!-- Existing Posts Section with Scroll Limit -->
        <div class="col-md-6 mb-4">
            <div class="container p-4 border rounded shadow-sm" style="max-height: 400px; overflow-y: auto; height: 100%;">
                <h3 class="text-center mb-3">Existing Posts</h3>
                @if($posts->count() > 0)
                    @foreach($posts as $post)
                        <div class="card mb-4 border-0 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">{{ $post->title }}</h5>
                                <p class="card-text">{{ Str::limit($post->content, 100) }}</p>
                                @if($post->image_path)
                                    <img src="{{ asset('images/posts/' . $post->image_path) }}" alt="{{ $post->title }}" class="img-fluid mb-3" style="max-height: 150px; object-fit: cover;">
                                @endif
                                <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data" class="mt-3">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-3">
                                        <label for="edit_title_{{ $post->id }}" class="form-label">Edit Title</label>
                                        <input type="text" class="form-control" id="edit_title_{{ $post->id }}" name="title" value="{{ $post->title }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_content_{{ $post->id }}" class="form-label">Edit Content</label>
                                        <textarea class="form-control" id="edit_content_{{ $post->id }}" name="content" rows="3" required>{{ $post->content }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_image_{{ $post->id }}" class="form-label">Change Image (optional)</label>
                                        <input type="file" class="form-control" id="edit_image_{{ $post->id }}" name="image">
                                    </div>
                                    <button type="submit" class="btn btn-success w-100">Save Changes</button>
                                </form>
                                <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="mt-3">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-100">Delete Post</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">No posts available.</p>
                @endif
            </div>
        </div>

        <!-- Manage Gallery Section with Scroll Limit -->
        <div class="col-md-6 mb-4">
            <div class="container p-4 border rounded shadow-sm" style="max-height: 400px; overflow-y: auto; height: 100%;">
                <h3 class="text-center mb-3">Manage Gallery</h3>
                <form action="{{ route('gallery.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="image" class="form-label">Image</label>
                        <input type="file" class="form-control" id="image" name="image" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Add Image</button>
                </form>
                <h4 class="mt-4">Existing Gallery Images</h4>
                <div class="row">
                    @foreach($galleryImages as $image)
                        <div class="col-md-12 mb-3">
                            <div class="card border-0 shadow-sm">
                                <img src="{{ asset($image->image_path) }}" alt="Gallery Image" class="card-img-top img-fluid rounded" style="max-height: 300px; object-fit: cover;">
                                <div class="card-body text-center">
                                    <form action="{{ route('gallery.destroy', $image->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger w-100">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
