@extends('layouts.myapp1')

@section('content')
<div class="container">
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
            <textarea id="content" name="content" class="form-control" rows="10">{{ $section->content }}</textarea>
        </div>
        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('cms.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
