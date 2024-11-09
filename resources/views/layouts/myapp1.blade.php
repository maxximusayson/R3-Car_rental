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

    <!-- Bootstrap CSS and Tailwind CSS -->
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
        main { 
            flex: 1; 
            padding: 20px; 
            margin-left: 250px; 
            transition: margin-left 0.3s ease; 
        }
        .sidebar {
            width: 250px;
            background-color: #1f2937;
            color: white;
            padding: 20px 0;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 1000;
            overflow-y: auto;
            box-shadow: 3px 0 10px rgba(0, 0, 0, 0.1);
            transition: width 0.3s ease;
        }
        .sidebar.collapsed {
            width: 80px;
        }
        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #ffffff;
            font-size: 15px;
            border-radius: 8px;
            transition: background-color 0.3s ease, color 0.3s ease;
            text-decoration: none;
            overflow: hidden;
        }
        .nav-link:hover, .nav-link.active { 
            background-color: #3b82f6; 
            color: white;
        }
        .nav-link i {
            margin-right: 12px;
            font-size: 18px;
        }
        .sidebar.collapsed .nav-link span {
            display: none;
        }
        .sidebar.collapsed .nav-link i {
            margin-right: 0;
        }

        /* User Info Box */
        .user-info {
    display: flex;
    align-items: center;
    padding: 10px 15px; /* Reduced padding */
    background-color: #111827;
    border-radius: 8px;
    margin: 10px 15px; /* Reduced margin */
}

.user-info img {
    width: 35px; /* Reduced width */
    height: 35px; /* Reduced height */
    border-radius: 50%;
    margin-right: 10px; /* Reduced margin */
}

.user-info p {
    margin: 0;
    font-weight: bold;
    font-size: 14px; /* Reduced font size */
    color: #ffffff;
}
        .sidebar.collapsed .user-info {
            opacity: 0;
        }

        /* Collapse Button */
        .collapse-btn {
            display: flex;
            justify-content: flex-end;
            padding: 10px 20px;
            cursor: pointer;
            color: #ffffff;
        }

        /* Hamburger Menu for Mobile */
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
    </style>
</head>

<body>
    <!-- Hamburger Menu for Mobile -->
    <div class="hamburger-menu" onclick="toggleSidebar()">
        <span></span>
        <span></span>
        <span></span>
    </div>

    <!-- Sidebar -->
    @guest
    @else
    <aside class="sidebar" id="sidebar">
        <!-- Collapse Button -->
        <div class="collapse-btn" onclick="toggleSidebarCollapse()">
            <i class="fas fa-bars"></i>
        </div>

        <!-- User Info -->
        <div class="user-info">
            <img loading="lazy" src="/images/icons/admin.png" alt="user icon">
            <div>
                <p>{{ Auth::user()->name }}</p>
            </div>
        </div>

        <!-- Navigation Links -->
        <div class="nav-links">
            @if (Auth::user()->role == 'admin')
                <a href="{{ route('adminDashboard') }}" class="nav-link">
                    <i class="fas fa-tachometer-alt"></i> <span>DASHBOARD</span>
                </a>
                <a href="{{ route('cars.index') }}" class="nav-link">
                    <i class="fas fa-car"></i> <span>CARS</span>
                </a>
                <a href="{{ route('users') }}" class="nav-link">
                    <i class="fas fa-users"></i> <span>USERS</span>
                </a>
                <a href="{{ route('insurances.index') }}" class="nav-link">
                    <i class="fas fa-shield-alt"></i> <span>INSURANCE</span>
                </a>
                <a href="{{ route('auditTrail') }}" class="nav-link">
                    <i class="fas fa-file-alt"></i> <span>AUDIT TRAIL</span>
                </a>

                <!-- GPS Tracking Dropdown -->
                <div class="custom-dropdown">
                    <a href="#" class="nav-link dropdown-toggle" id="gpsDropdown" role="button" onclick="toggleGpsDropdown(event)">
                        <i class="fas fa-map-marker-alt"></i> <span>GPS TRACKING</span>
                    </a>
                    <ul class="dropdown-menu custom-dropdown-menu" id="gpsDropdownMenu">
                        <li><a href="{{ route('gps.tracking1') }}" class="dropdown-item">GPS 1</a></li>
                        <li><a href="{{ route('gps.tracking2') }}" class="dropdown-item">GPS 2</a></li>
                    </ul>
                </div>

                <a href="{{ route('cms.manage') }}" class="nav-link {{ request()->routeIs('cms.manage') ? 'active' : '' }}">
                    <i class="fas fa-cogs"></i> <span>SETTINGS</span>
                </a>
                <a href="{{ route('logout') }}" class="nav-link logout-btn" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> <span>LOGOUT</span>
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

    <script>
        // Toggle sidebar on mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('open');
        }

        // Toggle sidebar collapse
        function toggleSidebarCollapse() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.querySelector('main');
            sidebar.classList.toggle('collapsed');
            mainContent.style.marginLeft = sidebar.classList.contains('collapsed') ? '80px' : '250px';
        }

        // Toggle GPS dropdown menu
        function toggleGpsDropdown(event) {
            event.preventDefault();
            const dropdownMenu = document.getElementById('gpsDropdownMenu');
            dropdownMenu.classList.toggle('show');
        }

        // Close dropdown when clicking outside
        window.addEventListener('click', function(e) {
            const dropdown = document.getElementById('gpsDropdown');
            const dropdownMenu = document.getElementById('gpsDropdownMenu');
            if (!dropdown.contains(e.target) && !dropdownMenu.contains(e.target)) {
                dropdownMenu.classList.remove('show');
            }
        });
    </script>
</body>
</html>
