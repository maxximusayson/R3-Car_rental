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
        html {
            scroll-behavior: smooth;
        }

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
            padding: 30px 0;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 1000;
            overflow-y: auto;
            box-shadow: 3px 0 10px rgba(0, 0, 0, 0.1);
            transition: width 0.3s ease, transform 0.3s ease;
        }

        .sidebar.collapsed {
            width: 0;
        }

        main.collapsed {
            margin-left: 0;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #ffffff;
            font-size: 15px;
            border-radius: 10px;
            transition: background-color 0.3s ease;
            opacity: 1;
            transition: opacity 0.3s ease;
        }

        .nav-link:hover,
        .nav-link.active {
            background-color: #3b82f6;
        }

        .sidebar.collapsed .nav-link {
            opacity: 0;
            padding: 0;
        }

        .user-info {
            display: flex;
            align-items: center;
            padding: 20px;
            background-color: #111827;
            border-radius: 10px;
            margin: 0 15px;
            overflow: hidden;
            white-space: nowrap;
            opacity: 1;
            transition: opacity 0.3s ease;
        }

        .sidebar.collapsed .user-info {
            opacity: 0;
            padding: 0;
        }

        .user-info img {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            margin-right: 15px;
        }

        .content {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .hamburger-menu {
            display: block;
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

        .nav-links {
            margin-top: 30px;
            padding: 0 20px;
        }

        .nav-link i {
            margin-right: 12px;
            font-size: 18px;
            transition: opacity 0.3s ease;
        }

        .sidebar.collapsed .nav-link i {
            opacity: 0;
        }

        /* Buttons */
        .nav-link.logout-btn {
            background-color: #ef4444;
            transition: background-color 0.3s ease;
        }

        .nav-link.logout-btn:hover {
            background-color: #dc2626;
        }

        .nav-link:hover i {
            transform: translateX(5px);
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
                <i class="fas fa-tachometer-alt"></i> <p>DASHBOARD</p>
            </a>
            <a href="{{ route('cars.index') }}" class="nav-link">
                <i class="fas fa-car"></i> <p>CARS</p>
            </a>
            <a href="{{ route('users') }}" class="nav-link">
                <i class="fas fa-users"></i> <p>USERS</p>
            </a>
            <a href="{{ route('insurances.index') }}" class="nav-link">
                <i class="fas fa-shield-alt"></i> <p>INSURANCE</p>
            </a>
            <a href="{{ route('auditTrail') }}" class="nav-link">
                <i class="fas fa-file-alt"></i> <p>AUDIT TRAIL</p>
            </a>
            <a href="{{ route('gps.tracking') }}" class="nav-link">
                <i class="fas fa-map-marker-alt"></i> <p>GPS TRACKING</p>
            </a>
            <a href="{{ route('cms.manage') }}" class="nav-link {{ request()->routeIs('cms.manage') ? 'active' : '' }}" aria-label="Content Management System">
                <i class="fas fa-cogs"></i> <p>SETTINGS</p>
            </a>
            <a href="{{ route('logout') }}" class="nav-link logout-btn" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i> <p>LOGOUT</p>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            @endif
        </div>
    </aside>
    @endguest

    <!-- Main Content -->
    <main class="content" id="main">
        @yield('content')
    </main>

    <!-- Bootstrap JS and dependencies (optional if needed) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const main = document.getElementById('main');
            sidebar.classList.toggle('collapsed');
            main.classList.toggle('collapsed');
        }
    </script>
</body>

</html>
