@extends('layouts.myapp1')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4 text-center">Manage Homepage Content</h1>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <!-- Editable About Us Section -->
        <div class="col-md-12 mb-5">
            <h2 class="text-primary">About Us Section</h2>
            @if($aboutUs)
                <!-- Display current About Us content -->
                <p class="border p-3 rounded bg-light">{{ Str::limit($aboutUs->content, 500) }}</p>
                <!-- Form for editing About Us -->
                <form action="{{ route('cms.aboutUs.update') }}" method="POST" class="mt-3">
                    @csrf
                    <div class="form-group">
                        <label for="about_us_content" class="form-label">Edit About Us Content</label>
                        <textarea class="form-control" id="about_us_content" name="content" rows="5" required>{{ $aboutUs->content ?? '' }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Save Changes</button>
                </form>
            @else
                <p class="text-muted">Content not available.</p> <!-- Default message if content is missing -->
            @endif
        </div>

        <!-- Add Post Section -->
        <div class="col-md-12 mb-5">
            <h2 class="text-primary">Manage Posts</h2>
            <!-- Form for adding a new post -->
            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="bg-light p-4 rounded shadow-sm">
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
                <button type="submit" class="btn btn-primary">Add Post</button>
            </form>

            <!-- List of posts -->
            <h3 class="mt-4">Existing Posts</h3>
            @if($posts->count() > 0)
                @foreach($posts as $post)
                    <div class="card mb-3 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">{{ $post->title }}</h5>
                            <p class="card-text">{{ Str::limit($post->content, 100) }}</p>
                            @if($post->image_path)
                                <img src="{{ asset('images/posts/' . $post->image_path) }}" alt="{{ $post->title }}" class="img-fluid mb-2" style="max-height: 200px; object-fit: cover;">
                            @endif

                            <!-- Edit Post Form -->
                            <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data" class="mt-3">
                                @csrf
                                @method('PUT') <!-- Use PUT method for update -->
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
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </form>

                            <!-- Delete Post Form -->
                            <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="mt-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete Post</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            @else
                <p class="text-muted">No posts available.</p>
            @endif
        </div>
    </div>
</div>
@endsection
