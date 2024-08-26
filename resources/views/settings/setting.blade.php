@extends('layouts.myapp1')

@section('content')
<div class="container">
    <h1>Settings</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Add New Admin Form -->
    <h2>Add New Admin</h2>
    <form method="POST" action="{{ route('settings.addAdmin') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirm Password:</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
        </div>

        <div class="form-group">
            <label for="avatar_choose">Upload Avatar:</label>
            <input type="file" class="form-control" id="avatar_choose" name="avatar_choose">
        </div>

        <div class="form-group">
            <label for="avatar_option">Or Select Avatar Option:</label>
            <input type="text" class="form-control" id="avatar_option" name="avatar_option">
        </div>

        <button type="submit" class="btn btn-primary">Add Admin</button>
    </form>
</div>
@endsection