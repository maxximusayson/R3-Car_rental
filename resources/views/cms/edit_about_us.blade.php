@extends('layouts.myapp1')

@section('content')
<div class="container">
    <h1>Edit About Us Section</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('cms.aboutUs.update') }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="content">Content</label>
            <textarea id="content" name="content" class="form-control" rows="10">{{ old('content', $aboutUs->content ?? '') }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('cms.manage') }}" class="btn btn-secondary">Back to List</a>
    </form>
</div>
@endsection