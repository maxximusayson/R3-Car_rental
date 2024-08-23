<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    


    <title>R3 Garage Car Rentals</title>
    @vite('resources/css/app.css')
    <link rel="icon" type="image/x-icon" href="/images/logos/logo1.jpg"> {{-- tab icon --}}
    @vite('node_modules/flowbite/dist/flowbite.min.js')
    
    <style>
        html {
            scroll-behavior: smooth;
        }
        .bg-custom-orange {
        background-color: #3B9ABF   ;
    }
    </style>
</head>

<body style="background-color: #f5f5f5;">

    {{-- -------------------------------------------------------------- Header -------------------------------------------------------------- --}}
    @guest
    
        <header>
        <nav class="bg-custom-orange border-black-100 px-4 lg:px-6 py-4 text-white" style="font-family: 'Century Gothic', sans-serif;">

  <div class="flex flex-wrap justify-between items-center mx-auto max-w-screen-xl">

  
               {{-- LOGO --}}
               <a href="{{ route('home') }}" class="flex items-center">
    <img loading="lazy" src="/images/logos/R3Logo.png" class="mr-1 h-20" />
        </a>

            {{-- login & Register buttons --}}
            <div class="flex items-center lg:order-2">
    <a href="{{ route('login') }}">
        <button class="flex items-center px-4 lg:px-5 py-2 lg:py-2.5 mr-2 text-white bg-black hover:bg-gray-800 font-medium text-sm rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                <path fill-rule="evenodd" d="M4.464 10l6.716-6.716a.75.75 0 011.06 1.06l-7.5 7.5a.75.75 0 000 1.06l7.5 7.5a.75.75 0 01-1.06 1.06L4.464 12H17.25a.75.75 0 000-1.5H4.464z" clip-rule="evenodd" />
            </svg>
            LOGIN
        </button>
    </a>
    <a href="{{ route('register') }}">
        <button class="flex items-center px-4 lg:px-5 py-2 lg:py-2.5 mr-2 text-white bg-black hover:bg-gray-800 font-medium text-sm rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 2a8 8 0 100 16 8 8 0 000-16zM4 10a6 6 0 1112 0H4z" clip-rule="evenodd" />
                <path d="M9 8a1 1 0 011-1h2a1 1 0 110 2h-2a1 1 0 01-1-1zM7 12a1 1 0 100 2h6a1 1 0 100-2H7z" />
            </svg>
            REGISTER
        </button>
    </a>
</div>


            

                    {{-- NAVBAR-LOOK = HOME, CARS, LOCATIONS, CONTACT US CODES --}}
                    <div class="hidden justify-between items-center w-full lg:flex lg:w-auto lg:order-1" id="mobile-menu-2">
    <ul class="flex flex-col mt-4 font-medium lg:flex-row lg:space-x-8 lg:mt-0">
        <li>
            <a href="/" class="text-white flex items-center">
                <span class="group text-center group-hover:cursor-pointer">Home</span>
                <div class="block invisible bg-pr-400 w-12 h-1 rounded-md text-center -bottom-1 mx-auto relative group-hover:visible"></div>
            </a>
        </li>
        <li>
            <a href="{{ route('cars') }}" class="text-white flex items-center">
                <span class="group text-center group-hover:cursor-pointer">Car</span>
                <div class="block invisible bg-pr-400 w-8 h-1 rounded-md text-center -bottom-1 mx-auto relative group-hover:visible"></div>
            </a>
        </li>
        <li>
            <a href="/location" class="text-white flex items-center">
                <span class="group text-center group-hover:cursor-pointer">Location</span>
                <div class="block invisible bg-pr-400 w-16 h-1 rounded-md text-center -bottom-1 mx-auto relative group-hover:visible"></div>
            </a>
        </li>
        <li>
            <a href="/contact_us" class="text-white flex items-center">
                <span class="group text-center group-hover:cursor-pointer">Contact</span>
                <div class="block invisible bg-pr-400 w-20 h-1 rounded-md text-center -bottom-1 mx-auto relative group-hover:visible"></div>
            </a>
        </li>
    </ul>
</div>



            </nav>
        </header>
    @else

                    {{-- user when login --}}
                        <header>
                        <nav class="custom-nav border-blue-200 px-4 lg:px-6 py-4 dark:bg-blue-700">
                <style>
                    .custom-nav {
                    background-color: #3B9ABF;
                    border-color: #3B8CBF; /* Adjust border color if needed */
                }

                .dark-mode .custom-nav {
                    background-color: #3B8CBF; /* Darker shade for dark mode */
                }
                </style>

                <div class="flex flex-wrap justify-between items-center mx-auto max-w-screen-xl">


                    {{-- LOGO --}}
                    <a href="{{ route('home') }}" class="flex items-center">
                        <img src="/images/logos/R3Logo.png" class="mr-3 h-16" alt="Flowbite Logo" />
                    </a>

                    {{-- admin navbar --}}


                    @if (Auth::user()->role == 'admin')
                        <div class="hidden justify-between mb-6 items-center w-full lg:flex lg:w-auto" id="mobile-menu-2">
                            <ul class="flex flex-col  font-medium lg:flex-row lg:space-x-8 lg:mt-0 ">
                                <li>
                                    <a href='{{ route('adminDashboard') }}'>
                                        <div class="group text-center">
                                            <div class="group-hover:cursor-pointer">Dashboard</div>
                                            <div
                                                class="block invisible bg-pr-400 w-20 h-1 rounded-md text-center -bottom-1 mx-auto relative group-hover:visible">
                                            </div>
                                    </a>

                                </li>

                                <li class=' '>
                                    <a href="{{ route('cars.index') }}">
                                        <div class="group text-center">
                                            <div class="group-hover:cursor-pointer ">Cars</div>
                                            <div
                                                class="block invisible bg-pr-400 w-8 h-1 rounded-md text-center -bottom-1 mx-auto relative group-hover:visible">
                                            </div>
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('users') }}">
                                        <div class="group text-center">
                                            <div class="group-hover:cursor-pointer">Users</div>
                                            <div
                                                class="block invisible bg-pr-400 w-10 h-1 rounded-md text-center -bottom-1 mx-auto relative group-hover:visible">
                                            </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('insurances.index') }}">
                                        <div class="group text-center">
                                            <div class="group-hover:cursor-pointer">Insurances</div>
                                            <div
                                                class="block invisible bg-pr-400 w-20 h-1 rounded-md text-center -bottom-1 mx-auto relative group-hover:visible">
                                            </div>
                                    </a>
                                </li>

                            </ul>
                        </div>
                        <button id="dropdownDefaultButton" data-dropdown-toggle="dropdown"
                            class="text-black bg-pr-900 hover:bg-pr-100 font-medium rounded-lg text-sm px-3 py-2.5 text-center inline-flex items-center "
                            type="button">
                            <img loading="lazy" src="/images/user.png" width="24" alt="user icon" class="mr-3">
                            <p> Admin ( {{ Auth::user()->name }} ) </p>
                            <svg class="w-4 h-4 ml-2" aria-hidden="true" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                        </button>

                        <div id="dropdown"
                            class="z-10 hidden bg-white divide-y divide-blue-100 rounded-lg shadow w-44 dark:bg-blue-700">
                            <ul class="py-2 text-sm text-gray-700 dark:text-gray-200"
                                aria-labelledby="dropdownDefaultButton">
                                <li>
                                    <a href="{{ route('adminDashboard') }}"
                                        class="block px-4 py-2 hover:bg-pr-200 dark:hover:bg-gray-600 dark:hover:text-white">Dashboard</a>
                                </li>
                                <li>
                                    <a class="block px-4 py-2 hover:bg-pr-200 " href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        class="hidden">
                                        @csrf
                                    </form>

                                </li>
                            </ul>
                        </div>
                    @else

            <!-- Customer login POV -->
                        <div class="hidden justify-between items-center w-full lg:flex lg:w-auto" id="mobile-menu-2">
                            <ul class="flex flex-col mt-4 font-medium lg:flex-row lg:space-x-8 lg:mt-0">
                            <li>
                        <a href="/" class="text-white" style="font-family: Arial Black, Arial, sans-serif;">
                            <div class="group text-center">
                                <div class="group-hover:cursor-pointer">Home</div>
                                <div class="block invisible bg-pr-400 w-12 h-1 rounded-md text-center -bottom-1 mx-auto relative group-hover:not visible">
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('cars') }}" class="text-white" style="font-family: Arial Black, Arial, sans-serif;">
                            <div class="group text-center">
                                <div class="group-hover:cursor-pointer">Cars</div>
                                <div class="block invisible bg-pr-400 w-8 h-1 rounded-md text-center -bottom-1 mx-auto relative group-hover:not visible">
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="/location" class="text-white" style="font-family: Arial Black, Arial, sans-serif;">
                            <div class="group text-center">
                                <div class="group-hover:cursor-pointer">Location</div>
                                <div class="block invisible bg-pr-400 w-16 h-1 rounded-md text-center -bottom-1 mx-auto relative group-hover:not visible">
                                </div>
                            </div>
                        </a>

                    </li>
                    <li>
                        <a href="/contact_us" class="text-white" style="font-family: Arial Black, Arial, sans-serif;">
                            <div class="group text-center">
                                <div class="group-hover:cursor-pointer">Contact</div>
                                <div class="block invisible bg-pr-400 w-20 h-1 rounded-md text-center -bottom-1 mx-auto relative group-hover:not visible">
                                </div>
                            </div>
                        </a>
                    </li>

                            </ul>
                        </div>

                        <button id="dropdownDefaultButton" data-dropdown-toggle="dropdown"
                                class="text-white bg-black hover:bg-gray-900 font-medium rounded-lg text-sm px-3 py-2.5 text-center inline-flex items-center"
                                type="button">
                            {{ Auth::user()->name }}
                            <svg class="w-4 h-4 ml-2" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>


                        <!-- Dropdown menu -->
                        <div id="dropdown"
                            class="z-10 hidden bg-white divide-y divide-blue-900 rounded-lg shadow w-44 dark:bg-blue-700">
                            <ul class="py-2 text-sm text-gray-700 dark:text-gray-200"
                                aria-labelledby="dropdownDefaultButton">

                                <li>
                                    <a href="{{ route('clientReservation') }}"
                                        class="block px-4 py-2 hover:bg-pr-200 ">My Account</a>
                                </li>

                              

                                <li>
                                    <a class="block px-4 py-2 hover:bg-pr-200 " href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        class="hidden">
                                        @csrf
                                    </form>

                                </li>
                            </ul>
                        </div>
                    @endif
                  
                </div>
            </nav>
        </header>
    @endguest

    {{-- --------------------------------------------------------------- Main  --------------------------------------------------------------- --}}
    <main>
        @yield('content')
    </main>


    
    <!---------------------------------------------------------------- chatbot ------------------------------------------------------------------->

    <body>

<script src="https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/widget.js"></script>
<script>
    var botmanWidget = {
        title: 'R3 Garage Car Rental',
        introMessage: "Hi! I'm here to help you with your car rental needs.",
        mainColor: '#f7c605',
        bubbleBackground: '#0e76a8',
        aboutText: 'R3 Garage Car Rental Service',
        placeholderText: 'Ask me something...',
        displayMessageTime: true,
    };

    var floatingChat = document.getElementById('floating-chat');
    var chatMessages = document.getElementById('chat-messages');

    var botmanWidgetInstance = new BotManChatWidget(botmanWidget);

    function handleSuggestionClick(choice) {
        botmanWidgetInstance.addMessage('You selected: ' + choice);
        botmanWidgetInstance.sendMessage(choice);
    }


    botmanWidgetInstance.listen(function(message) {
        var messageElement = document.createElement('div');
        messageElement.classList.add('message');
        messageElement.textContent = message.text;
        chatMessages.appendChild(messageElement);
    });


    function sendAutoPilotMessages() {
        botmanWidgetInstance.addMessage('Hello! Welcome to R3 Garage Car Rental. How can I assist you today?');
        
        setTimeout(function() {
            botmanWidgetInstance.sendMessage('How can I assist you today?');
            botmanWidgetInstance.sendMessage({
                text: 'Please select an option below:',
                quick_replies: [
                    {
                        title: 'Browse cars',
                        payload: 'browse cars',
                    },
                    {
                        title: 'What\'s on sale?',
                        payload: 'what\'s on sale?',
                    },
                    {
                        title: 'Learn about us',
                        payload: 'learn about us',
                    },
                    {
                        title: 'Rental process',
                        payload: 'rental process',
                    },
                    {
                        title: 'Contact support',
                        payload: 'contact support',
                    }
                ]
            });
        }, 1000); 
    }


    window.addEventListener('load', sendAutoPilotMessages);
</script>
</body>





<!-- -------------------------------------------------------------------------------------------------------------------------------------------- -->


    {{-- --------------------------------------------------------------- Footer  --------------------------------------------------------------- --}}
@if (Auth::check() && Auth::user()->role == 'admin')

@else
<footer class="px-4 sm:p-6 bg-[#3B8CBF]">
    <div class="pt-10 mx-auto max-w-screen-xl relative">
        <div class="md:flex md:justify-between">
            <div class="mb-12 md:mb-0 flex justify-center ">
                <a href="" class="flex items-center">
                    <img loading="lazy" src="/images/logos/logor3.png" class="mr-3 h-24" alt="Logo" />
                </a>
            </div>

            <div class="grid grid-cols-3 gap-8">    
    <div>
        <h2 class="mb-6 text-sm font-semibold uppercase text-white" style="font-family: 'Arial Black', Arial, sans-serif;">Follow us</h2>
        <ul class="text-sm text-white"> 
            <li class="mb-4">
                <a href="https://www.facebook.com/r3carrental" class="hover:underline" target='_blank'>Facebook</a>
            </li>
            <li>
                <a href="https://www.facebook.com/messages/t/107981499010175" class="hover:underline" target='_blank'>Email Us</a>
            </li>
        </ul>
    </div>
    <div>
        <h2 class="mb-6 text-sm font-semibold uppercase text-white" style="font-family: 'Arial Black', Arial, sans-serif;">Legal</h2>
        <ul class="text-sm text-white"> <!-- Added text-black class here -->
            <li class="mb-4">
                <a href="{{route('privacy_policy')}}" class="hover:underline">Privacy Policy</a>
            </li>
            <li>
                <a href="{{route('terms_conditions')}}" class="hover:underline">Terms &amp; Conditions</a>
            </li>
        </ul>
    </div>
</div>

        </div>

        <hr class="my-6 sm:mx-auto border-black-700 lg:my-8" />

        <div class="flex justify-center">
        <span class="text-sm text-white" style="font-family: 'Arial Black', Arial, sans-serif;">© 2024 <a href="http://127.0.0.1:8000/" target="_blank" class="hover:underline">R3 Garage Car Rental</a>. All Rights Reserved.</span>

</div>

    </div>
</footer>
@endif

</body>
</html>