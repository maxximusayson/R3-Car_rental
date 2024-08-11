@extends('layouts.myapp1')

@section('content')
<div class="flex justify-center items-center h-screen">
    <div class="bg-white shadow-lg rounded-lg w-full max-w-md p-8">
        <h2 class="text-2xl font-semibold mb-6 text-center">Add New Admin</h2>
        <form method="POST" action="{{ route('addNewAdmin') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label for="name" class="block mb-2">Name:</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}"
                    class="block w-full border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-indigo-300">
                @error('name')
                <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="email" class="block mb-2">Email Address:</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                    class="block w-full border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-indigo-300">
                @error('email')
                <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="avatar" class="block mb-2">Upload Image:</label>
                <input type="file" id="avatar" name="avatar_choose"
                    class="block w-full border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-indigo-300">
            </div>

            <div class="mb-4">
                <label for="password" class="block mb-2">Password:</label>
                <input type="password" id="password" name="password"
                    class="block w-full border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-indigo-300">
                @error('password')
                <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-6">
                <label for="password-confirm" class="block mb-2">Confirm Password:</label>
                <input type="password" id="password-confirm" name="password_confirmation"
                    class="block w-full border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-indigo-300">
            </div>

            <button type="submit"
                class="bg-indigo-500 text-white px-4 py-2 rounded-lg hover:bg-indigo-600 focus:outline-none focus:ring focus:ring-indigo-300">Add
                New Admin</button>
        </form>
    </div>
</div>
@endsection
