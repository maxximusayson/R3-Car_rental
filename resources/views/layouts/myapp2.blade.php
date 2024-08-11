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
    <link rel="icon" type="image/x-icon" href="/images/logos/logo1.jpg"> {{-- tab icon --}}
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-WVZ9P8XS0ezOjR0+T6H0u9f4MbVJ4u3n5Fdjp1kNqff+gECfn9ZoT6Bx0tqu9Tcq3mIjSjbPqGfbiK3+TW9P5A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        html {
            scroll-behavior: smooth;
        }

        .sidebar {
            height: 100%;
            width: 250px;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
            background-color: #3B9ABF;
            padding-top: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: #fff;
            transition: transform 0.3s ease;
        }

        .sidebar a {
            padding: 15px 20px;
            text-decoration: none;
            font-size: 18px;
            color: white;
            display: flex;
            align-items: center;
            transition: background-color 0.3s;
        }

        .sidebar a:hover {
            background-color: #3BA7BF;
        }

        .sidebar a .fa {
            margin-right: 10px;
        }

        .main-content {
            margin-left: 260px;
            padding: 20px;
            background-color: #f9fafb;
            min-height: 100vh;
        }

        .dropdown-btn {
            font-size: 18px;
            border: none;
            background: none;
            color: white;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            text-align: left;
            cursor: pointer;
            outline: none;
            transition: background-color 0.3s;
        }

        .dropdown-btn:hover {
            background-color: #3B9ABF;
        }

        .dropdown-container {
            display: none;
            background-color: #3B9ABF;
            padding-left: 20px;
        }

        .dropdown-container a {
            padding: 10px 20px;
            color: white;
            display: flex;
            align-items: center;
        }

        .dropdown-container a:hover {
            background-color: #3B9ABF;
        }

        .logo img {
            height: 60px;
        }

        .sidebar-footer {
            padding: 20px;
            background-color: #3B9ABF;
            text-align: center;
        }

        .sidebar-footer a {
            display: block;
            margin: 10px 0;
            color: #fff;
        }

        .sidebar-footer a:hover {
            color: #f3f4f6;
        }

        .hamburger-menu {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 2;
            cursor: pointer;
        }

        .hamburger-menu span {
            display: block;
            width: 25px;
            height: 3px;
            margin: 5px;
            background: #000;
        }

        @media screen and (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .main-content {
                margin-left: 0;
                padding-top: 60px;
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
                <i class="fa-solid fa-sign-in-alt"></i>Login
            </a>
            <a href="{{ route('register') }}">
                <i class="fa-solid fa-user-plus"></i>Register
            </a>
            <a href="/">
                <i class="fa-solid fa-home"></i>Home
            </a>
            <a href="{{ route('cars') }}">
                <i class="fa-solid fa-car"></i>Cars
            </a>
            <a href="/location">
                <i class="fa-solid fa-map-marker-alt"></i>Location
            </a>
            <a href="/contact_us">
                <i class="fa-solid fa-envelope"></i>Contact
            </a>
            @else
            <a href="/">
                <i class="fa-solid fa-home"></i>Home
            </a>
            <a href="{{ route('cars') }}">
                <i class="fa-solid fa-car"></i>Cars
            </a>
            <a href="/location">
                <i class="fa-solid fa-map-marker-alt"></i>Location
            </a>
            <a href="/contact_us">
                <i class="fa-solid fa-envelope"></i>Contact
            </a>
            <button class="dropdown-btn">
                <span>
                    <i class="fa-solid fa-user"></i>{{ Auth::user()->name }}
                </span>
                <svg class="w-4 h-4 ml-2" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            <div class="dropdown-container">
                <a href="{{ route('logout') }}" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                    <i class="fa-solid fa-sign-out-alt"></i>{{ __('Logout') }}
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
        var dropdown = document.querySelector('.dropdown-btn');
        var dropdownContainer = document.querySelector('.dropdown-container');

        dropdown.addEventListener('click', function () {
            dropdownContainer.style.display = dropdownContainer.style.display === 'block' ? 'none' : 'block';
        });

        function toggleSidebar() {
            var sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('open');
        }
    </script>
</body>

</html>
