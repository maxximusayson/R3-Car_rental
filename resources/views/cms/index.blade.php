@extends('layouts.myapp1')

@section('content')
<!-- <div class="container-fluid mt-4">
    <h2 class="text-center mb-4">CMS - Manage Posts</h2>
    <div class="row">
        <div class="col-lg-6"> -->
            <!-- Form for creating a new post -->
            <!-- <div class="card bg-light shadow-sm mb-4">
                <div class="card-body">
                    <h3 class="card-title mb-4 text-primary">Add New Post</h3>
                    <form action="{{ route('posts.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="form-group">
                            <label for="content">Content</label>
                            <textarea class="form-control" id="content" name="content" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Submit</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-6"> -->
            <!-- Form for editing an existing post -->
            <!-- @if(isset($post))
            <div class="card bg-light shadow-sm mb-4">
                <div class="card-body">
                    <h3 class="card-title mb-4 text-info">Edit Post</h3>
                    <form action="{{ route('posts.update', $post->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ $post->title }}" required>
                        </div>
                        <div class="form-group">
                            <label for="content">Content</label>
                            <textarea class="form-control" id="content" name="content" rows="5" required>{{ $post->content }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-info btn-block">Update</button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</div> -->





<!-- <style>
    .container-fluid {
        background-color: #f8f9fa;
        padding: 2rem;
        border-radius: 8px;
    }

    .card {
        border: none;
        border-radius: 8px;
        background-color: #ffffff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s;
    }


    .card-title {
        font-size: 1.75rem;
        font-weight: bold;
        color: #333;
        text-align: center;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-control {
        border: 1px solid #ced4da;
        border-radius: 4px;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .form-control:focus {
        border-color: #80bdff;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .btn {
        font-size: 1rem;
        font-weight: bold;
        padding: 0.75rem 1.5rem;
        border-radius: 4px;
        transition: background-color 0.15s ease-in-out, border-color 0.15s ease-in-out;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #004f9e;
    }

    .btn-info {
        background-color: #17a2b8;
        border-color: #17a2b8;
    }

    .btn-info:hover {
        background-color: #117a8b;
        border-color: #10707c;
    }

    h2 {
        font-size: 2rem;
        font-weight: bold;
        color: #333;
        text-align: center;
        margin-bottom: 2rem;
    }
</style> -->


<div class="container-fluid mt-4">
    <h2 class="text-center mb-4">CMS - Manage Homepage Content</h2>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="row">
        <div class="col-lg-12">
            <div class="card bg-light shadow-sm mb-4">
                <div class="card-body">
                    <h3 class="card-title mb-4 text-info">Edit Homepage Content</h3>
                    <form action="{{ route('homepage.update') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="main-content">Homepage Content</label>
                            <textarea class="form-control" id="main-content" name="main-content" rows="20" required>{{ old('main-content', $mainContent) }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-info btn-block">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mt-4">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <div class="col-lg-12">
            <div class="card bg-light shadow-sm mb-4">
                <div class="card-body">
                    <h3 class="card-title mb-4 text-info">Edit About Us Content</h3>
                    <form action="{{ route('about_us.update') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="about_us_content">About Us Content</label>
                            <textarea class="form-control" id="about_us_content" name="about_us_content" rows="10" required>{{ old('about_us_content', $aboutUsContent) }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-info btn-block">Update About Us</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border: none;
        border-radius: 8px;
        background-color: #ffffff;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }

    .card-title {
        font-size: 1.5rem;
        font-weight: bold;
        color: #333;
    }

    .form-control {
        border: 1px solid #ced4da;
        border-radius: 4px;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .form-control:focus {
        border-color: #80bdff;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .btn-info {
        background-color: #17a2b8;
        border-color: #17a2b8;
    }

    .btn-info:hover {
        background-color: #117a8b;
        border-color: #10707c;
    }

    .btn-block {
        display: block;
        width: 100%;
    }
</style>

@endsection
