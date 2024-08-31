@extends('layouts.myapp1')

@section('content')
<div class="container">
    @if(isset($section))
        <!-- Editing a specific section -->
        <h1>Edit {{ ucfirst($section->section_name) }} Section</h1>
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('cms.update', $section->section_name) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="content">Content</label>
                <textarea id="content" name="content" class="form-control" rows="10">{{ old('content', $section->content) }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('cms.manage') }}" class="btn btn-secondary">Back to List</a>
        </form>
    @else
        <!-- List of sections -->
        <h1>Manage Homepage Content</h1>
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @foreach($sections as $section)
            <div class="section mb-4">
                <h2>{{ ucfirst($section->section_name) }} Section</h2>
                <p>{{ Str::limit($section->content, 200) }}</p> <!-- Display a snippet of the content -->
                <a href="{{ route('cms.manage', ['section_name' => $section->section_name]) }}" class="btn btn-primary">Edit</a>
            </div>
        @endforeach
    @endif
</div>
@endsection
