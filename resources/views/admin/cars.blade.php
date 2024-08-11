@extends('layouts.myapp1')

@section('content')
    <!-- Clock -->
    <div id="clock" class="text-gray-900 dark:text-gray-300 text-lg font-semibold absolute top-4 right-4">
        <span id="time"></span>
    </div>

    <div class="my-10 flex justify-end mx-auto max-w-screen-xl">
        <a href="{{ route('cars.create') }}" class="text-white bg-blue-600 hover:bg-blue-700 py-2 px-4 rounded-md shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6 inline mr-2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Add New Car
        </a>
    </div>

    <!-- Search Input and Dropdown -->
    <div class="my-4 mx-auto max-w-screen-xl flex items-center space-x-4">
        <div class="relative">
            <input type="text" id="searchInput" placeholder="Search cars..." class="w-64 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-indigo-200 focus:border-indigo-500">
        </div>
        <div class="relative">
            <select id="carBrand" class="w-48 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-indigo-200 focus:border-indigo-500">
                <option value="">Select Brand</option>
                <option value="Toyota">Toyota</option>
                <option value="Nissan">Nissan</option>
                <option value="Mitsubishi">Mitsubishi</option>
            </select>
        </div>
    </div>

    <div class="overflow-x-auto bg-white shadow sm:rounded-lg mx-auto max-w-screen-xl">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Images
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Brand
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Model
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Engine
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Price per Day
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Quantity
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Reserved
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody id="carTableBody" class="bg-white divide-y divide-gray-200">
                @foreach ($cars as $car)
                    <tr class="car-row">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-2">
                                @foreach ($car->images as $image)
                                    <div class="flex-shrink-0 h-24 w-24">
                                        <img class="h-24 w-24 object-cover" loading="lazy" src="{{ asset($image->path ?? 'path/to/default-image.jpg') }}" alt="car image">
                                    </div>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $car->brand }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $car->model }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $car->engine }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $car->price_per_day }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $car->quantity }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                {{ $car->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('cars.edit', ['car' => $car->id]) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">Edit</a>
                            <form action="{{ route('cars.destroy', ['car' => $car->id]) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Remove</button>
                            </form>
                        </td>
                    </tr>
                @endforeach

                <!-- Displayed when no cars match the search -->
                @if ($cars->isEmpty())
                    <tr>
                        <td colspan="8" class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium text-gray-500">No cars found.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <script>
        // JavaScript for filtering cars based on input and dropdown selection
        const searchInput = document.getElementById('searchInput');
        const carBrandSelect = document.getElementById('carBrand');
        const carTableBody = document.getElementById('carTableBody');

        function filterCars() {
            const filterValue = searchInput.value.trim().toLowerCase();
            const brandValue = carBrandSelect.value.trim();

            const rows = carTableBody.getElementsByTagName('tr');

            for (let i = 0; i < rows.length; i++) {
                const brand = rows[i].querySelector('.text-sm.font-medium.text-gray-900').textContent.trim();
                const model = rows[i].querySelector('.text-sm.text-gray-900:nth-child(3)').textContent.trim().toLowerCase();
                const engine = rows[i].querySelector('.text-sm.text-gray-900:nth-child(4)').textContent.trim().toLowerCase();

                const brandMatch = brandValue === '' || brand.toLowerCase() === brandValue.toLowerCase();
                const searchMatch = model.includes(filterValue) || engine.includes(filterValue);

                rows[i].style.display = brandMatch && searchMatch ? '' : 'none';
            }
        }

        searchInput.addEventListener('input', filterCars);
        carBrandSelect.addEventListener('change', filterCars);
    </script>
@endsection
