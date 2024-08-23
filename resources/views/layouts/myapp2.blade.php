<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>R3 Garage Car Rentals</title>
    @vite('resources/css/app.css')
    @vite('node_modules/flowbite/dist/flowbite.min.js')
    <link rel="icon" type="image/x-icon" href="/images/logos/logo.png">
    <!-- Google Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
        }

        .sidebar {
            height: 100%;
            width: 260px;
            position: fixed;
            z-index: 1000;
            top: 0;
            left: 0;
            background-color: #1f2937;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: #fff;
            transition: transform 0.3s ease;
            padding-top: 20px;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.15);
        }

        .sidebar a {
            padding: 15px 25px;
            text-decoration: none;
            font-size: 16px;
            color: #e5e7eb;
            display: flex;
            align-items: center;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .sidebar a .material-icons {
            margin-right: 20px;
            font-size: 24px;
            color: #9ca3af;
        }

        .sidebar a:hover {
            background-color: #374151;
            color: #f3f4f6;
        }

        .main-content {
            margin-left: 270px;
            padding: 30px;
            background-color: #ffffff;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        .dropdown-btn {
            font-size: 16px;
            border: none;
            background: none;
            color: #e5e7eb;
            padding: 15px 25px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            text-align: left;
            cursor: pointer;
            outline: none;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .dropdown-btn .material-icons {
            margin-right: 20px;
            font-size: 24px;
            color: #9ca3af;
        }

        .dropdown-btn:hover {
            background-color: #374151;
            color: #f3f4f6;
        }

        .dropdown-container {
            display: none;
            background-color: #1f2937;
            padding-left: 40px;
        }

        .dropdown-container a {
            padding: 10px 0;
            color: #9ca3af;
            display: flex;
            align-items: center;
        }

        .dropdown-container a .material-icons {
            margin-right: 15px;
            font-size: 20px;
            color: #9ca3af;
        }

        .dropdown-container a:hover {
            color: #f3f4f6;
        }

        .logo img {
            height: 60px;
            display: block;
            margin: 0 auto 20px;
            filter: drop-shadow(0px 4px 6px rgba(0, 0, 0, 0.1));
        }

        .sidebar-footer {
            padding: 20px;
            background-color: #374151;
            text-align: center;
        }

        .sidebar-footer a {
            display: block;
            margin: 10px 0;
            color: #e5e7eb;
            font-size: 14px;
        }

        .sidebar-footer a:hover {
            color: #f3f4f6;
        }

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
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .hamburger-menu span {
            display: block;
            width: 28px;
            height: 3px;
            margin: 6px 0;
            background: #e5e7eb;
        }

        @media screen and (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .main-content {
                margin-left: 0;
            }

            .hamburger-menu {
                display: block;
            }

            .sidebar.open {
                transform: translateX(0);
            }
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
    <div class="sidebar" id="sidebar">
        <div>
            <div class="logo">
                <img loading="lazy" src="/images/logos/R3Logo.png" alt="R3 Garage Car Rentals" />
            </div>
            @guest
            <a href="{{ route('login') }}">
                <span class="material-icons">login</span>Login
            </a>
            <a href="{{ route('register') }}">
                <span class="material-icons">person_add</span>Register
            </a>
            <a href="/">
                <span class="material-icons">home</span>Home
            </a>
            <a href="{{ route('cars') }}">
                <span class="material-icons">directions_car</span>Cars
            </a>
            <a href="/location">
                <span class="material-icons">place</span>Location
            </a>
            <a href="/contact_us">
                <span class="material-icons">mail</span>Contact
            </a>
            @else
            <a href="/">
                <span class="material-icons">home</span>Home
            </a>
            <a href="{{ route('cars') }}">
                <span class="material-icons">directions_car</span>Cars
            </a>
            <a href="/location">
                <span class="material-icons">place</span>Location
            </a>
            <a href="/contact_us">
                <span class="material-icons">mail</span>Contact
            </a>
            <button class="dropdown-btn">
                <span>
                    <span class="material-icons">person</span>{{ Auth::user()->name }}
                </span>
                <span class="material-icons">expand_more</span>
            </button>
            <div class="dropdown-container">
                <a href="{{ route('logout') }}" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                    <span class="material-icons">logout</span>{{ __('Logout') }}
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </div>
            @endguest
        </div>
        <div class="sidebar-footer">
            <a href="{{ route('privacy_policy') }}">Privacy Policy</a>
            <a href="{{ route('terms_conditions') }}">Terms & Conditions</a>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="main-content">
        @yield('content')
    </div>

    <script>
        document.querySelector('.dropdown-btn').addEventListener('click', function () {
            var dropdownContainer = document.querySelector('.dropdown-container');
            dropdownContainer.style.display = dropdownContainer.style.display === 'block' ? 'none' : 'block';
        });

        function toggleSidebar() {
            var sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('open');
        }
    </script>
</body>

</html>
