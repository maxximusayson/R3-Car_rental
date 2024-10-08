@extends('layouts.myapp1')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCDg7pLs7iesp74vQ-KSEjnFJW3BKhVq7k"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@section('content')
<!-- Header -->
<div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white p-6 rounded-lg shadow-lg mb-4">
    <h1 class="text-4xl font-extrabold text-shadow">Welcome, Admin!</h1>
    <p class="mt-2 text-lg">Here’s an overview of your current activities and statistics.</p>
</div>

<!-- Clock -->
<div id="clock" class="text-white text-lg font-semibold position-absolute top-4 end-0 bg-gradient-to-r from-blue-500 to-indigo-600 py-2 px-4 rounded-lg shadow-lg">
    <span id="date"></span>, <span id="time"></span>
</div>


    <div class="my-3 d-flex justify-content-end">
        <a href="{{ route('cars.create') }}" class="btn btn-primary shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6 inline me-2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Add New Car
        </a>
    </div>

    <!-- Search Input and Dropdown -->
    <div class="my-4 mx-auto d-flex flex-column flex-sm-row align-items-center gap-3">
        <input type="text" id="searchInput" placeholder="Search cars..." class="form-control flex-grow-1" style="max-width: 250px;">
        <select id="carBrand" class="form-select flex-shrink-1" style="min-width: 150px;">
            <option value="">Select Brand</option>
            <option value="Toyota">Toyota</option>
            <option value="Nissan">Nissan</option>
            <option value="Mitsubishi">Mitsubishi</option>
        </select>
    </div>

    <div class="container my-5">
        <div class="card shadow-lg border-0 rounded-lg">
        <div class="card-header" style="background-color: #343a40; color: #f8f9fa;">
    <h5 class="mb-0" style="font-size: 1.5rem; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);">Car List</h5>
</div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Images/Videos</th>
                                <th>Brand</th>
                                <th>Model</th>
                                <th>Engine</th>
                                <th>Price per Day</th>
                                <th>Quantity</th>
                                <th>Reserved</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="carTableBody">
                            @foreach ($cars as $car)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center flex-wrap">
                                            @if ($car->images && $car->images->count() > 0)
                                                @foreach ($car->images as $image)
                                                    <div class="me-2">
                                                        <img class="img-fluid rounded" src="{{ asset($image->image_path) }}" alt="{{ $car->brand }} {{ $car->model }}" style="width: 80px; height: 80px;">
                                                    </div>
                                                @endforeach
                                            @else
                                                <img class="img-fluid me-2 rounded" src="{{ asset('path/to/default-image.jpg') }}" alt="default car image" style="width: 80px; height: 80px;">
                                            @endif

                                            @if($car->video_path)
                                                <video controls class="me-2 rounded" style="width: 80px; height: 80px;">
                                                    <source src="{{ asset('storage/' . $car->video_path) }}" type="video/mp4">
                                                    Your browser does not support the video tag.
                                                </video>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-muted">{{ $car->brand }}</td>
                                    <td class="text-muted">{{ $car->model }}</td>
                                    <td class="text-muted">{{ $car->engine }}</td>
                                    <td>₱{{ number_format($car->price_per_day, 2) }}</td>
                                    <td>{{ $car->quantity }}</td>
                                    <td>
                                        <span class="badge {{ $car->status == 'Reserved' ? 'bg-danger' : 'bg-success' }}">
                                            {{ $car->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('cars.edit', ['car' => $car->id]) }}" class="btn btn-outline-info btn-sm">Edit</a>
                                        <form action="{{ route('cars.destroy', ['car' => $car->id]) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach

                            @if ($cars->isEmpty())
                                <tr>
                                    <td colspan="8" class="text-center text-muted">No cars found.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-gradient {
            background: linear-gradient(to right, #6a11cb, #2575fc);
        }
        .table-light th {
            color: #333;
            font-weight: 600;
        }
        .table-light td {
            vertical-align: middle;
            font-size: 0.9rem;
            color: #555;
        }
        .btn-outline-info, .btn-outline-danger {
            transition: all 0.3s ease;
        }
        .btn-outline-info:hover, .btn-outline-danger:hover {
            background-color: rgba(0, 0, 0, 0.1);
        }
    </style>

    <script>
        function updateClock() {
            const now = new Date();
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            const dateString = now.toLocaleDateString(undefined, options);
            const timeString = now.toLocaleTimeString();
            document.getElementById('date').textContent = dateString;
            document.getElementById('time').textContent = timeString;
        }

        updateClock();
        setInterval(updateClock, 1000);
        
        const searchInput = document.getElementById('searchInput');
        const carBrandSelect = document.getElementById('carBrand');
        const carTableBody = document.getElementById('carTableBody');

        function filterCars() {
            const filterValue = searchInput.value.trim().toLowerCase();
            const brandValue = carBrandSelect.value.trim().toLowerCase();
            const rows = carTableBody.getElementsByTagName('tr');

            for (let i = 0; i < rows.length; i++) {
                const cells = rows[i].getElementsByTagName('td');
                const brand = cells[1].textContent.trim().toLowerCase();
                const model = cells[2].textContent.trim().toLowerCase();
                const engine = cells[3].textContent.trim().toLowerCase();

                const brandMatch = brandValue === '' || brand.includes(brandValue);
                const searchMatch = model.includes(filterValue) || engine.includes(filterValue);

                rows[i].style.display = brandMatch && searchMatch ? '' : 'none';
            }
        }

        searchInput.addEventListener('input', filterCars);
        carBrandSelect.addEventListener('change', filterCars);
    </script>
@endsection
