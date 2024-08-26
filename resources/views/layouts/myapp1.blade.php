<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Administrator</title>
    @vite('resources/css/app.css')
    <link rel="icon" type="image/x-icon" href="/images/icons/admin.png"> {{-- tab icon --}}
    @vite('node_modules/flowbite/dist/flowbite.min.js')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        html {
            scroll-behavior: smooth;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            font-family: 'Century Gothic', sans-serif;
        }

        main {
            flex: 1;
            padding: 20px;
        }

        .sidebar {
            width: 250px;
            background-color: #343a40;
            color: white;
            padding-top: 20px;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 1000;
            overflow-y: auto;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            transform: translateX(0);
            transition: transform 0.3s ease;
        }

        .nav-link {
            display: block;
            padding: 15px 20px;
            color: white;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .nav-link:hover {
            background-color: #265c85;
        }

        .user-info {
            display: flex;
            align-items: center;
            padding: 20px;
        }

        .user-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .user-info p {
            margin: 0;
            font-weight: bold;
            font-size: 16px;
        }

        .logout-btn {
            display: block;
            background-color: transparent;
            border: none;
            padding: 15px 20px;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-align: left;
        }

        .logout-btn:hover {
            background-color: #265c85;
        }

        .content {
            margin-left: 250px;
            transition: margin-left 0.3s ease;
        }

        .hamburger-menu {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1001;
            cursor: pointer;
            background-color: #343a40;
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
            .sidebar {
                transform: translateX(-100%);
            }

            .content {
                margin-left: 0;
            }

            .hamburger-menu {
                display: block;
            }

            .sidebar.open {
                transform: translateX(0);
            }
        }

        .nav-links {
            margin-top: 20px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            color: #ffffff;
            text-decoration: none;
            font-size: 16px;
            border-bottom: 1px solid #495057;
        }

        .nav-link:hover,
        .nav-link.active {
            background-color: #3B9ABF;
        }

        .nav-link.active {
            color: #ffffff;
            background-color: #3B9ABF;
        }

        .nav-link i {
            margin-right: 10px;
        }
    </style>
</head>

<body>
    {{-- Hamburger Menu --}}
    <div class="hamburger-menu" onclick="toggleSidebar()">
        <span></span>
        <span></span>
        <span></span>
    </div>

    {{-- Sidebar --}}
    @guest
    @else
    <aside class="sidebar" id="sidebar">
        <div class="user-info">
            <img loading="lazy" src="/images/icons/admin.png" alt="user icon">
            <div>
                @if (Auth::user()->role == 'admin')
                <p>{{ Auth::user()->name }}</p>
                @else
                <p>{{ Auth::user()->name }}</p>
                @endif
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
            <a href="{{ route('gps.tracking') }}" class="nav-link">
                <i class="fas fa-map-marker-alt"></i> GPS TRACKING
            </a>
            <a href="" class="nav-link">
                <i class="fas fa-cogs"></i> CMS
            </a>
            <a href="{{ route('settings') }}" class="nav-link">
                <i class="fas fa-cogs"></i> SETTINGS
            </a>

            <a href="{{ route('logout') }}" class="nav-link"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i> LOGOUT
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            @endif
        </div>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>
    </aside>
    @endguest

    {{-- Main Content --}}
    <main class="content">
        @yield('content')
    </main>

    <script>
        function toggleSidebar() {
            var sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('open');
        }
    </script>
</body>

</html>
