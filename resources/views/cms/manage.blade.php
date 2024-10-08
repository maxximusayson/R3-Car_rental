@extends('layouts.myapp1')

@section('content')
<div class="container">
    <h1>Manage Homepage Content</h1>
    
    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Editable About Us Section -->
    <div class="section mb-4">
        <h2>About Us Section</h2>

        @if($aboutUs)
            <!-- Display current About Us content -->
            <p>{{ Str::limit($aboutUs->content, 500) }}</p>
            <!-- Form for editing About Us -->
            <form action="{{ route('cms.aboutUs.update') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="about_us_content">Edit About Us Content</label>
                    <textarea class="form-control" id="about_us_content" name="content" rows="5" required>{{ $aboutUs->content ?? '' }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Save changes</button>
            </form>
        @else
            <p>Content not available.</p> <!-- Default message if content is missing -->
        @endif
    </div>

</div>

@endsection
