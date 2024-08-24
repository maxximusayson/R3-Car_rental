@extends('layouts.myapp1')

@section('content')
<div class="flex flex-col items-center justify-center max-w-screen-xl mx-auto my-20">
    <form class="w-full" action="{{ route('cars.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="md:w-2/3 w-5/6 md:px-12 px-6 pb-10 mx-auto mt-2 space-y-8 bg-white shadow-lg rounded-lg">
            <div class="pb-8 border-b border-gray-200">
                <h2 class="text-2xl font-semibold text-center text-gray-800">Create New Car Details</h2>

                <div class="grid grid-cols-1 mt-8 gap-x-6 gap-y-6 sm:grid-cols-6">

                    <!-- Brand -->
                    <div class="sm:col-span-3">
                        <label for="brand" class="block text-sm font-medium text-gray-700">Brand</label>
                        <div class="mt-2">
                            <input type="text" name="brand" id="brand"
                                class="block w-full rounded-lg border border-gray-300 py-2 px-3 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                        @error('brand')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Model -->
                    <div class="sm:col-span-3">
                        <label for="model" class="block text-sm font-medium text-gray-700">Model</label>
                        <div class="mt-2">
                            <input type="text" name="model" id="model"
                                class="block w-full rounded-lg border border-gray-300 py-2 px-3 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                        @error('model')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Engine -->
                    <div class="sm:col-span-2">
                        <label for="engine" class="block text-sm font-medium text-gray-700">Engine</label>
                        <div class="mt-2">
                            <input type="text" name="engine" id="engine"
                                class="block w-full rounded-lg border border-gray-300 py-2 px-3 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                        @error('engine')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Quantity -->
                    <div class="sm:col-span-2">
                        <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                        <div class="mt-2">
                            <input type="text" name="quantity" id="quantity"
                                class="block w-full rounded-lg border border-gray-300 py-2 px-3 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                        @error('quantity')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Price per day -->
                    <div class="sm:col-span-2">
                        <label for="price_per_day" class="block text-sm font-medium text-gray-700">Price per Day</label>
                        <div class="mt-2">
                            <input type="text" name="price_per_day" id="price_per_day"
                                class="block w-full rounded-lg border border-gray-300 py-2 px-3 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                        @error('price_per_day')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Cover Photos -->
                    <div class="col-span-full">
                        <label for="cover-photos" class="block text-sm font-medium text-gray-700">Cover Photos</label>
                        <div class="mt-2 border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                            <div class="text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 7v4a1 1 0 001 1h16a1 1 0 001-1V7M5 10V5a1 1 0 011-1h12a1 1 0 011 1v5m-9 8v3m0 0H8m2 0h4m0 0v-3"></path>
                                </svg>
                                <div class="mt-4">
                                    <label for="file-upload"
                                        class="relative cursor-pointer rounded-md font-medium text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 hover:text-indigo-500">
                                        <span>Upload files</span>
                                        <input id="file-upload" name="images[]" type="file" multiple class="sr-only">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB each</p>
                            </div>
                        </div>
                        @error('images')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <!-- Video Upload -->
                    <div class="col-span-full">
    <label for="video" class="block text-sm font-medium text-gray-700">Upload Video</label>
    <div class="mt-2 border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
        <div class="text-center">
            <label for="video"
                class="relative cursor-pointer rounded-md font-medium text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 hover:text-indigo-500">
                <span>Upload video</span>
                <input id="video" name="video" type="file" class="sr-only" accept="video/*">
            </label>
            <p class="pl-1">or drag and drop</p>
        </div>
        <p class="text-xs text-gray-500">MP4, AVI, MOV up to 50MB</p>
    </div>
    @error('video')
        <span class="text-red-500">{{ $message }}</span>
    @enderror
</div>



                    <!-- Description -->
                    <div class="sm:col-span-6">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <div class="mt-2">
                            <textarea name="description" id="description" rows="4"
                                class="block w-full rounded-lg border border-gray-300 py-2 px-3 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                        </div>
                        @error('description')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Insurance -->
                    <div class="sm:col-span-3">
                        <label for="insurance_status" class="block text-sm font-medium text-gray-700">Insurance</label>
                        <div class="mt-2">
                            <select id="insurance_status" name="insurance_status"
                                class="block w-full rounded-lg border border-gray-300 py-2 px-3 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="active">Active</option>
                                <option value="pending">Pending</option>
                                <option value="expired">Expired</option>
                            </select>
                        </div>
                        @error('insurance_status')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="sm:col-span-3">
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <div class="mt-2">
                            <select id="status" name="status"
                                class="block w-full rounded-lg border border-gray-300 py-2 px-3 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="Available">Available</option>
                                <option value="Unavailable">Unavailable</option>
                            </select>
                        </div>
                        @error('status')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                                        <!-- Branch -->
                                        <div class="sm:col-span-3">
                        <label for="branch" class="block text-sm font-medium text-gray-700">Branch</label>
                        <div class="mt-2">
                            <select id="branch" name="branch"
                                class="block w-full rounded-lg border border-gray-300 py-2 px-3 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option disabled selected>Select Branch</option>
                                <option value="Marikina">Marikina</option>
                                <option value="Isabela">Isabela</option>
                            </select>
                        </div>
                        @error('branch')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-center gap-x-4">
                <a href="{{ route('cars.index') }}"
                    class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-100">
                    Cancel
                </a>
                <button type="submit"
                    class="inline-flex items-center justify-center px-6 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Save
                </button>
            </div>

        </div>
    </form>
</div>
@endsection
