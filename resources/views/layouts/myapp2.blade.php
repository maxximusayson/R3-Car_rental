<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>R3 Garage Car Rentals</title>
    @vite('resources/css/app.css')

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
           <!-- Trigger Button -->
<div class="dropdown-container">
    <a href="#" id="logout-link">
        <span class="material-icons">logout</span>{{ __('Logout') }}
    </a>
</div>

<!-- Pop-up Dialog -->
<div id="logout-dialog" class="dialog hidden">
    <div class="dialog-content">
        <p class="dialog-message">Are you sure you want to log out?</p>
        <div class="dialog-buttons">
            <button id="confirm-logout" class="btn btn-primary">Yes</button>
            <button id="cancel-logout" class="btn btn-secondary">No</button>
        </div>
    </div>
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
    @csrf
</form>

<!-- Styles -->
<style>
    .dialog {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(0, 0, 0, 0.5);
        z-index: 9999;
    }

    .dialog-content {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        text-align: center;
        max-width: 400px;
        width: 100%;
    }

    .dialog-message {
        font-size: 18px;
        margin-bottom: 20px;
        color: #333; /* Updated to ensure text visibility */
    }

    .dialog-buttons {
        display: flex;
        justify-content: center;
        gap: 10px;
    }

    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        font-size: 16px;
        cursor: pointer;
        margin: 5px;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .btn-primary {
        background-color: #007bff;
        color: white;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .btn-secondary {
        background-color: #6c757d;
        color: white;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
    }

    .hidden {
        display: none;
    }
</style>

<!-- JavaScript -->
<script>
    document.getElementById('logout-link').addEventListener('click', function (e) {
        e.preventDefault();
        document.getElementById('logout-dialog').classList.remove('hidden');
    });

    document.getElementById('confirm-logout').addEventListener('click', function () {
        document.getElementById('logout-form').submit();
    });

    document.getElementById('cancel-logout').addEventListener('click', function () {
        document.getElementById('logout-dialog').classList.add('hidden');
    });
</script>

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
