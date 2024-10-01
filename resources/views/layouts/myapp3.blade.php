<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', 'Default Title')</title>


    <title>R3 Garage Car Rental</title>
    <link rel="icon" type="image/x-icon" href="/images/logos/r3.jpg"> {{-- tab icon --}}
    
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Vite for resources -->
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')

    

</head>

<body style="background-color: #f5f5f5;">

    {{-- Header --}}
    @guest
        <header>
            <nav class="bg-custom-orange px-4 lg:px-6 py-4 text-white" style="font-family: 'Century Gothic', sans-serif;">
                <div class="flex flex-wrap justify-between items-center mx-auto max-w-screen-xl">
                    {{-- Logo --}}
                    <a href="{{ route('home') }}" class="flex items-center">
                        <img src="/images/logos/R3Logo.png" class="mr-2 h-16" alt="R3 Logo" loading="lazy" />
                    </a>

                    {{-- Desktop Navigation --}}
<div class="hidden lg:flex lg:space-x-8">
    <a href="/" class="text-white custom-font group relative transition duration-300 ease-in-out">
        HOME
        <span class="absolute left-0 bottom-0 w-0 h-0.5 bg-white transition-all duration-300 ease-in-out group-hover:w-full"></span>
    </a>
    
    <a href="{{ route('cars') }}" class="text-white custom-font group relative transition duration-300 ease-in-out">
        CARS
        <span class="absolute left-0 bottom-0 w-0 h-0.5 bg-white transition-all duration-300 ease-in-out group-hover:w-full"></span>
    </a>
    
    <a href="/location" class="text-white custom-font group relative transition duration-300 ease-in-out">
        LOCATIONS
        <span class="absolute left-0 bottom-0 w-0 h-0.5 bg-white transition-all duration-300 ease-in-out group-hover:w-full"></span>
    </a>
    
    <a href="/contact_us" class="text-white custom-font group relative transition duration-300 ease-in-out">
        CONTACT US
        <span class="absolute left-0 bottom-0 w-0 h-0.5 bg-white transition-all duration-300 ease-in-out group-hover:w-full"></span>
    </a>
</div>


                    {{-- Mobile Hamburger Menu --}}
                    <div class="lg:hidden">
                        <button class="text-white" id="mobile-menu-button">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>

                    {{-- Mobile Menu --}}
                    <div id="mobile-menu" class="hidden lg:hidden flex">
                        <ul class="flex flex-col items-center space-y-4 py-4">
                            <li><a href="/" class="text-white custom-font">Home</a></li>
                            <li><a href="{{ route('cars') }}" class="text-white custom-font">Cars</a></li>
                            <li><a href="/location" class="text-white custom-font">Location</a></li>
                            <li><a href="/contact_us" class="text-white custom-font">Contact</a></li>
                        </ul>
                        <div class="mobile-menu-buttons space-y-4 py-4">
                        </div>
                    </div>

                    {{-- Desktop Login/Register Buttons --}}
                    <div class="hidden lg:flex space-x-4">
                    </div>
                </div>
            </nav>
        </header>
    @else
        {{-- Logged-in User Header --}}
    <header>
    <nav class="bg-custom-orange px-6 py-4 text-white" style="font-family: 'Century Gothic', sans-serif;">
        <div class="flex justify-between items-center">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center">
                <img src="/images/logos/R3Logo.png" class="h-16" alt="R3 Logo" />
            </a>

            <!-- Desktop Navigation Links -->
            <div class="hidden lg:flex space-x-6">
                <a href="/" class="text-white font-medium hover:text-yellow-400 transition duration-300 px-4 py-2 rounded-md">Home</a>
                <a href="{{ route('cars') }}" class="text-white font-medium hover:text-yellow-400 transition duration-300 px-4 py-2 rounded-md">Cars</a>
                <a href="/location" class="text-white font-medium hover:text-yellow-400 transition duration-300 px-4 py-2 rounded-md">Location</a>
                <a href="/contact_us" class="text-white font-medium hover:text-yellow-400 transition duration-300 px-4 py-2 rounded-md">Contact</a>
                @if (Auth::user()->role == 'admin')
                    <a href="{{ route('adminDashboard') }}" class="text-white font-medium hover:text-yellow-400 transition duration-300 px-4 py-2 rounded-md">Dashboard</a>
                    <a href="{{ route('users') }}" class="text-white font-medium hover:text-yellow-400 transition duration-300 px-4 py-2 rounded-md">Users</a>
                    <a href="{{ route('insurances.index') }}" class="text-white font-medium hover:text-yellow-400 transition duration-300 px-4 py-2 rounded-md">Insurances</a>
                @endif
            </div>

            <!-- Mobile Hamburger Menu -->
            <div class="lg:hidden flex items-center">
                <button class="text-white" id="mobile-menu-button">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <!-- User Dropdown -->
            <div class="relative hidden lg:flex">
                <button class="bg-dark text-white px-4 py-2 rounded-md flex items-center space-x-2 transition duration-300" id="userDropdownButton">
                    <span>{{ Auth::user()->name }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div id="dropdown" class="z-20 hidden bg-white divide-y divide-gray-200 rounded-lg shadow-lg w-48 absolute right-0 mt-2">
                    <ul class="py-2 text-sm text-gray-700">
                        <li><a href="{{ route('clientReservation') }}" class="block px-4 py-2 hover:bg-gray-100">My Account</a></li>
                        <li>
                            <a href="{{ route('logout') }}" class="block px-4 py-2 hover:bg-gray-100" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="lg:hidden fixed inset-0 bg-white z-30 transform -translate-x-full transition-transform">
            <div class="flex justify-end p-4">
                <button class="text-gray-600" id="close-menu-button">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <ul class="flex flex-col items-center space-y-4 py-4">
                <li><a href="/" class="text-gray-800 font-medium">Home</a></li>
                <li><a href="{{ route('cars') }}" class="text-gray-800 font-medium">Cars</a></li>
                <li><a href="/location" class="text-gray-800 font-medium">Location</a></li>
                <li><a href="/contact_us" class="text-gray-800 font-medium">Contact</a></li>
                @if (Auth::user()->role == 'admin')
                    <li><a href="{{ route('adminDashboard') }}" class="text-gray-800 font-medium">Dashboard</a></li>
                    <li><a href="{{ route('users') }}" class="text-gray-800 font-medium">Users</a></li>
                    <li><a href="{{ route('insurances.index') }}" class="text-gray-800 font-medium">Insurances</a></li>
                @endif
            </ul>
        </div>
    </nav>
</header>

<script>
    // Toggle mobile menu
    document.getElementById('mobile-menu-button').addEventListener('click', function() {
        var mobileMenu = document.getElementById('mobile-menu');
        mobileMenu.classList.toggle('translate-x-0');
    });

    // Close mobile menu
    document.getElementById('close-menu-button').addEventListener('click', function() {
        var mobileMenu = document.getElementById('mobile-menu');
        mobileMenu.classList.add('-translate-x-full');
    });

    // Close mobile menu when clicking outside
    window.addEventListener('click', function(event) {
        var mobileMenu = document.getElementById('mobile-menu');
        var button = document.getElementById('mobile-menu-button');
        if (!button.contains(event.target) && !mobileMenu.contains(event.target)) {
            mobileMenu.classList.add('-translate-x-full');
        }
    });

    // Toggle user dropdown
    document.getElementById('userDropdownButton').addEventListener('click', function() {
        var dropdown = document.getElementById('dropdown');
        dropdown.classList.toggle('hidden');
    });

    // Close user dropdown when clicking outside
    window.addEventListener('click', function(event) {
        var dropdown = document.getElementById('dropdown');
        var button = document.getElementById('userDropdownButton');
        if (!button.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.classList.add('hidden');
        }
    });
</script>

    @endif

    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>
    
       <!-- Mobile Menu Toggle Script -->
    <script>
        document.getElementById('mobile-menu-button').addEventListener('click', function () {
            var mobileMenu = document.getElementById('mobile-menu');
            var body = document.querySelector('body');
            if (mobileMenu.style.display === 'none' || mobileMenu.style.display === '') {
                mobileMenu.style.display = 'flex'; // Show menu
                body.classList.add('no-scroll'); // Disable scrolling
            } else {
                mobileMenu.style.display = 'none'; // Hide menu
                body.classList.remove('no-scroll'); // Enable scrolling
            }
        });
    </script>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>

    <!-- Chatbot Functionality -->
    <div id="chatbot">
        <div id="chatbotHeader">
            R3 Garage Car Rental
            <button id="minimizeChat" title="Minimize"><i class="fas fa-minus"></i></button>
            <button id="closeChat" title="Close"><i class="fas fa-times"></i></button>
        </div>
        <div id="chatbotMessages"></div>
        <div id="chatbotInput"></div>
    </div>

    <button id="chatbotTrigger"><i class="fas fa-comments"></i></button>

 <script>
    const botProfileImage = '/images/icons/chatbot.png';

    // Function to open the chatbot
    document.getElementById('chatbotTrigger').addEventListener('click', function () {
        const chatbot = document.getElementById('chatbot');
        chatbot.style.display = 'flex'; // Show the chatbot
        chatbot.classList.add('show');  // Add show class for animations
        document.getElementById('chatbotTrigger').style.display = 'none'; // Hide the trigger button

        // Only display the welcome message if it's the first time opening (chat history is empty)
        const chatbotMessages = document.getElementById('chatbotMessages');
        if (chatbotMessages.innerHTML.trim() === '') {
            showTypingIndicator();
            setTimeout(() => {
                hideTypingIndicator();
                addBotMessage('Hi there! Welcome to R3 Car Rental Garage. How can we assist you today?');
                appendQuickReplies();
            }, 3000);
        }
    });

    // Function to handle user option selection
    function selectOption(option) {
        const chatbotMessages = document.getElementById('chatbotMessages');
        addUserMessage(option); // Display user-selected option
        showTypingIndicator(); // Show typing indicator for bot response
        setTimeout(() => {
            hideTypingIndicator();
            handleBotResponse(option); // Handle bot response
            appendQuickReplies(); // Show quick replies again after bot response
        }, 3000);
    }

    // Function to handle bot responses based on user selection
    function handleBotResponse(option) {
        if (option === 'Rental Rates') {
            addBotMessage('Economy Cars: $25/day, SUVs: $50/day, Luxury Cars: $100/day. Rentals outside Metro Manila will incur an additional charge.');
        } else if (option === 'Rental Policies') {
            addBotMessage('Customers must leave one valid ID. Only drivers listed in the rental agreement may drive the vehicle.');
        } else if (option === 'Book a Car') {
            addBotMessage('Please visit our <a href="https://r3garagecarrental.online/cars" target="_blank" style="color: #007bff; text-decoration: underline;">Car Listing page</a> to choose and book your car.');
        } else if (option === 'Contact Us') {
            addBotMessage('Phone: +1 234 567 890, Email: support@r3garage.com, Address: 123 Main St, Metro Manila');
        } else if (option === 'FAQs') {
            addBotMessage('FAQs: Payment Options (Gcash, Cash only), Late Returns (additional hourly rate applies).');
        }
    }

    // Function to add user message to chat
    function addUserMessage(message) {
        const chatbotMessages = document.getElementById('chatbotMessages');
        const userMessage = `<div class="message userMessage"><div class="messageBubble">${message}</div></div>`;
        chatbotMessages.insertAdjacentHTML('beforeend', userMessage);
    }

    // Function to add bot message to chat
    function addBotMessage(message) {
        const chatbotMessages = document.getElementById('chatbotMessages');
        const botMessage = `<div class="message botMessage"><img src="${botProfileImage}" alt="Bot"><div class="messageBubble">${message}</div></div>`;
        chatbotMessages.insertAdjacentHTML('beforeend', botMessage);
    }

    // Function to show typing indicator
    function showTypingIndicator() {
        const chatbotMessages = document.getElementById('chatbotMessages');
        const typingIndicator = `<div id="typingIndicator" class="message typing">Typing...</div>`;
        chatbotMessages.insertAdjacentHTML('beforeend', typingIndicator);
    }

    // Function to hide typing indicator
    function hideTypingIndicator() {
        const typingIndicator = document.getElementById('typingIndicator');
        if (typingIndicator) typingIndicator.remove();
    }

    // Function to show quick reply buttons
    function appendQuickReplies() {
        const chatbotMessages = document.getElementById('chatbotMessages');
        const quickReplies = `
            <div style="display: flex; flex-wrap: wrap; justify-content: flex-start;">
                <button class="quickReply" onclick="selectOption('Rental Rates')">Rental Rates</button>
                <button class="quickReply" onclick="selectOption('Rental Policies')">Rental Policies</button>
                <button class="quickReply" onclick="selectOption('Book a Car')">Book a Car</button>
                <button class="quickReply" onclick="selectOption('Contact Us')">Contact Us</button>
                <button class="quickReply" onclick="selectOption('FAQs')">FAQs</button>
            </div>
        `;
        chatbotMessages.insertAdjacentHTML('beforeend', quickReplies);
    }

    // Function to clear chat messages
    function clearChatMessages() {
        const chatbotMessages = document.getElementById('chatbotMessages');
        chatbotMessages.innerHTML = ''; // Clear all messages
    }

    // Minimize chatbot without closing completely, preserving chat history
    document.getElementById('minimizeChat').addEventListener('click', function () {
        const chatbot = document.getElementById('chatbot');
        chatbot.style.display = 'none';  // Hide chatbot
        document.getElementById('chatbotTrigger').style.display = 'block'; // Show the trigger button again
    });

    // Close chatbot if clicking outside of it
    window.addEventListener('click', function (event) {
        const chatbot = document.getElementById('chatbot');
        const trigger = document.getElementById('chatbotTrigger');
        const isClickInside = chatbot.contains(event.target) || trigger.contains(event.target);

        if (!isClickInside) {
            chatbot.style.display = 'none';  // Hide chatbot if clicking outside
            document.getElementById('chatbotTrigger').style.display = 'block'; // Show the trigger button again
        }
    });

    // Close the chatbot and reset the chat
    document.getElementById('closeChat').addEventListener('click', function () {
        const chatbot = document.getElementById('chatbot');
        clearChatMessages(); // Clear all chat messages when closed
        chatbot.classList.remove('show'); // Remove the 'show' class to start hiding animation
        setTimeout(() => {
            chatbot.style.display = 'none'; // Hide chatbot after animation completes
            document.getElementById('chatbotTrigger').style.display = 'block'; // Show the trigger button again
        }, 300); // Delay to allow transition to complete before hiding
    });
</script>



    {{-- Footer --}}
    @if (!Auth::check() || Auth::user()->role != 'admin')
       <!-- Footer -->
<footer class="footer mt-auto">
    <!-- Top Footer Section with Social Links -->
    <div class="footer-top bg-gray-900 text-white py-6">
        <p class="text-center text-lg font-medium mb-4">Stay connected with us on social media:</p>
        <div class="flex justify-center space-x-6">
            <a href="https://www.facebook.com/r3carrental" class="hover:text-gray-400 transition duration-300">
                <i class="fab fa-facebook-f text-2xl"></i>
            </a>
            <a href="#" class="hover:text-gray-400 transition duration-300">
                <i class="fab fa-google text-2xl"></i>
            </a>
            <a href="https://github.com/iceon25" class="hover:text-gray-400 transition duration-300">
                <i class="fab fa-github text-2xl"></i>
            </a>
        </div>
    </div>

    <!-- Middle Footer Section with Main Links and Contact Info -->
    <div class="footer-main bg-gray-800 text-white py-8">
        <div class="container mx-auto flex flex-col lg:flex-row justify-between space-y-8 lg:space-y-0">
            <!-- Company Info Section -->
            <div class="footer-section lg:w-1/3 text-center lg:text-left">
                <h3 class="text-2xl font-semibold mb-4">R3 Garage Car Rental</h3>
                <p class="text-gray-400">Your journey begins with us. Reliable cars, unbeatable service, and a commitment to getting you there.</p>
            </div>

            <!-- Legal Section -->
            <div class="footer-section lg:w-1/3 text-center lg:text-left">
                <h3 class="text-2xl font-semibold mb-4">Legal</h3>
                <ul class="space-y-2">
                    <li><a href="{{route('terms_conditions')}}" class="text-gray-400 hover:text-white transition duration-300">Terms & Conditions</a></li>
                    <li><a href="{{route('privacy_policy')}}" class="text-gray-400 hover:text-white transition duration-300">Privacy Policy</a></li>
                </ul>
            </div>

            <!-- Contact Info Section -->
            <div class="footer-section lg:w-1/3 text-center lg:text-left">
                <h3 class="text-2xl font-semibold mb-4">Contact Us</h3>
                <ul class="space-y-2">

                    <li class="flex justify-center lg:justify-start items-center">
                        <i class="fas fa-phone mr-3"></i>
                        <span>+63-955-379-3727</span>
                    </li>
                    <li class="flex justify-center lg:justify-start items-center">
                        <i class="fas fa-envelope mr-3"></i>
                        <span>r3garage@gmail.com</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Bottom Footer Section with Copyright Info -->
    <div class="footer-bottom bg-gray-900 text-center text-gray-400 py-4">
        <p>© 2024 R3 Garage Car Rental. All Rights Reserved.</p>
    </div>
</footer>

    @endif
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    
        <!-- Custom Styles -->
    <style>
    
        html {
            scroll-behavior: smooth;
        }

        .bg-custom-orange {
            background-color: #3B9ABF;
        }

        /* Hide hamburger icon on larger screens */
        @media (min-width: 1024px) {
            #mobile-menu-button {
                display: none !important;
            }
        }

        /* Fullscreen mobile menu */
        #mobile-menu {
            background-color: #3B9ABF;
            width: 100%;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 9999;
            display: none;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        /* Disable body scroll when menu is open */
        body.no-scroll {
            overflow: hidden;
        }

        /* Mobile menu button spacing */
        .mobile-menu-buttons {
            margin-top: 20px;
        }

        /* Chatbot styles */
        #chatbot {
            width: 400px;
            height: 600px;
            position: fixed;
            bottom: 20px;
            right: 20px;
            border-radius: 20px;
            background-color: #fff;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            display: none;
            flex-direction: column;
            overflow: hidden;
            transition: all 0.3s ease;
            border: 1px solid #e0e0e0;
        }

        #chatbotHeader {
            background-color: #0056b3;
            color: white;
            padding: 15px;
            text-align: center;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
        }

        #chatbotMessages {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            background-color: #f9f9f9;
        }

        /* Quick reply button styles */
        .quickReply {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            margin: 5px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        /* Chatbot trigger icon */
        #chatbotTrigger {
            position: fixed;
            bottom: 20px;
            right: 20px;
            font-size: 50px;
            color: #007bff;
            background: none;
            border: none;
            cursor: pointer;
            transition: color 0.3s ease, transform 0.3s ease;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            #chatbot {
                width: 100%; /* Full width on mobile */
                height: 100vh; /* Full height on mobile */
                bottom: 0;
                right: 0;
                border-radius: 0; /* Remove rounded corners */
            }

            #chatbotHeader {
                font-size: 16px; /* Smaller font for mobile */
                padding: 10px;
            }

            #chatbotMessages {
                padding: 15px;
                font-size: 14px; /* Adjust font size for smaller screens */
            }

            .quickReply {
                padding: 8px 16px; /* Smaller padding for mobile */
                font-size: 12px; /* Smaller font size */
            }

            #chatbotTrigger {
                font-size: 40px; /* Smaller chatbot trigger icon */
            }
        }

        @media (max-width: 480px) {
            #chatbotMessages {
                padding: 10px;
                font-size: 12px; /* Even smaller font size for very small screens */
            }

            .quickReply {
                padding: 6px 12px; /* Even smaller padding */
                font-size: 11px; /* Even smaller font */
            }

            #chatbotHeader {
                font-size: 14px;
                padding: 8px;
            }

            #chatbotTrigger {
                font-size: 35px; /* Adjust chatbot trigger for very small screens */
            }
        }

        #chatbotTrigger:hover {
            color: #0056b3;
            transform: scale(1.1);
        }

        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .message {
            margin: 10px 0;
            display: flex;
            align-items: flex-start;
        }

        .messageBubble {
            padding: 12px 20px;
            border-radius: 25px;
            max-width: 100%;
            word-wrap: break-word;
            font-size: 14px;
            line-height: 1.5;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
            position: relative;
            display: inline-block;
            overflow-wrap: break-word;
        }

        .botMessage {
            flex-direction: row;
        }

        .botMessage img {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .botMessage .messageBubble {
            background-color: #e1f5fe;
            border: 1px solid #b3e5fc;
            margin-left: 10px;
        }

        .userMessage {
            justify-content: flex-end;
            text-align: right;
        }

        .userMessage .messageBubble {
            background-color: #007bff;
            color: #fff;
            border: 1px solid #007bff;
            margin-right: 10px;
        }
    </style>
</body>
</html>
