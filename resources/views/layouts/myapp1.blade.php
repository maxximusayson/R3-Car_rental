<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin</title>
    @vite('resources/css/app.css')
    <link rel="icon" type="image/x-icon" href="/images/icons/admin.png">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* General styles */
        html { scroll-behavior: smooth; }
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #f7f8fc;
            color: #333;
        }
        main { flex: 1; padding: 20px; margin-left: 270px; transition: margin-left 0.3s ease; }
        .sidebar {
            width: 250px;
            background-color: #1f2937;
            color: white;
            padding: 30px 0;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 1000;
            overflow-y: auto;
            box-shadow: 3px 0 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #ffffff;
            font-size: 15px;
            border-radius: 10px;
            transition: background-color 0.3s ease;
        }
        .nav-link:hover, .nav-link.active { background-color: #3b82f6; }
        .nav-link i { margin-right: 12px; font-size: 18px; }
        .user-info {
            display: flex;
            align-items: center;
            padding: 20px;
            background-color: #111827;
            border-radius: 10px;
            margin: 0 15px;
        }
        .user-info img { width: 45px; height: 45px; border-radius: 50%; margin-right: 15px; }
        .user-info p { margin: 0; font-weight: bold; font-size: 18px; color: #ffffff; }
        .hamburger-menu {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1001;
            cursor: pointer;
            background-color: #1f2937;
            padding: 10px;
            border-radius: 5px;
        }
        .hamburger-menu span {
            display: block;
            width: 25px;
            height: 3px;
            margin: 5px;
            background: #fff;
        }
        @media screen and (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            main { margin-left: 0; }
            .hamburger-menu { display: block; }
            .sidebar.open { transform: translateX(0); }
        }

        /* Smooth Dropdown Animation with Scale */
        .custom-dropdown-menu {
            opacity: 0;
            transform: scale(0.95);
            transition: opacity 0.2s ease, transform 0.2s ease;
            display: none; /* Initially hidden */
        }
        .custom-dropdown-menu.show {
            display: block;
            opacity: 1;
            transform: scale(1);
        }
    </style>
</head>

<body>
    <!-- Hamburger Menu -->
    <div class="hamburger-menu" onclick="toggleSidebar()">
        <span></span>
        <span></span>
        <span></span>
    </div>

    <!-- Sidebar -->
    @guest
    @else
    <aside class="sidebar" id="sidebar">
        <div class="user-info">
            <img loading="lazy" src="/images/icons/admin.png" alt="user icon">
            <div>
                <p>{{ Auth::user()->name }}</p>
            </div>
        </div>

        <div class="nav-links">
            @if (Auth::user()->role == 'admin')
                <a href="{{ route('adminDashboard') }}" class="nav-link">
                    <i class="fas fa-tachometer-alt"></i> DASHBOARD
                </a>
                <a href="{{ route('cars.index') }}" class="nav-link">
                    <i class="fas fa-car"></i> CARS
                </a>
                <a href="{{ route('users') }}" class="nav-link">
                    <i class="fas fa-users"></i> USERS
                </a>
                <a href="{{ route('insurances.index') }}" class="nav-link">
                    <i class="fas fa-shield-alt"></i> INSURANCE
                </a>
                <a href="{{ route('auditTrail') }}" class="nav-link">
                    <i class="fas fa-file-alt"></i> AUDIT TRAIL
                </a>

                <!-- GPS Tracking Dropdown with Modern Smooth Animation -->
                <div class="nav-link custom-dropdown">
                    <a href="#" class="nav-link dropdown-toggle" id="gpsDropdown" role="button" onclick="toggleGpsDropdown()">
                        <i class="fas fa-map-marker-alt"></i> GPS TRACKING
                    </a>
                    <ul class="dropdown-menu custom-dropdown-menu" id="gpsDropdownMenu">
                        <li><a href="{{ route('gps.tracking1') }}" class="dropdown-item">GPS 1</a></li>
                        <li><a href="{{ route('gps.tracking2') }}" class="dropdown-item">GPS 2</a></li>
                    </ul>
                </div>

                <a href="{{ route('cms.manage') }}" class="nav-link {{ request()->routeIs('cms.manage') ? 'active' : '' }}" aria-label="Content Management System">
                    <i class="fas fa-cogs"></i> CMS
                </a>
                <a href="{{ route('logout') }}" class="nav-link logout-btn" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> LOGOUT
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            @endif
        </div>
    </aside>
    @endguest

    <!-- Main Content -->
    <main class="content">
        @yield('content')
    </main>

    <!-- Bootstrap JS and dependencies (optional if needed) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function toggleSidebar() {
            var sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('open');
        }

        function toggleGpsDropdown() {
            var dropdownMenu = document.getElementById('gpsDropdownMenu');
            dropdownMenu.classList.toggle('show');
        }

        // Close the dropdown if clicked outside
        window.addEventListener('click', function(e) {
            var dropdown = document.getElementById('gpsDropdown');
            var dropdownMenu = document.getElementById('gpsDropdownMenu');
            if (!dropdown.contains(e.target) && !dropdownMenu.contains(e.target)) {
                dropdownMenu.classList.remove('show');
            }
        });
    </script>
</body>
</html>
