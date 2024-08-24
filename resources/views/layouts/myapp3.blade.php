<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    


    <title>R3 Garage Car Rental</title>
    @vite('resources/css/app.css')
    <link rel="icon" type="image/x-icon" href="/images/logos/r3.jpg"> {{-- tab icon --}}
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

          


            

<div class="hidden justify-between items-center w-full lg:flex lg:w-auto lg:order-1" id="mobile-menu-2">
    <ul class="flex flex-col mt-4 font-medium lg:flex-row lg:space-x-8 lg:mt-0">
        <li>
            <a href="/" class="text-white flex items-center">
                <span class="group text-center group-hover:cursor-pointer custom-font">Home</span>
                <div class="block invisible bg-pr-400 w-12 h-1 rounded-md text-center -bottom-1 mx-auto relative group-hover:visible"></div>
            </a>
        </li>
        <li>
            <a href="{{ route('cars') }}" class="text-white flex items-center">
                <span class="group text-center group-hover:cursor-pointer custom-font">Cars</span>
                <div class="block invisible bg-pr-400 w-8 h-1 rounded-md text-center -bottom-1 mx-auto relative group-hover:visible"></div>
            </a>
        </li>
        <li>
            <a href="/location" class="text-white flex items-center">
                <span class="group text-center group-hover:cursor-pointer custom-font">Location</span>
                <div class="block invisible bg-pr-400 w-16 h-1 rounded-md text-center -bottom-1 mx-auto relative group-hover:visible"></div>
            </a>
        </li>
        <li>
            <a href="/contact_us" class="text-white flex items-center">
                <span class="group text-center group-hover:cursor-pointer custom-font">Contact</span>
                <div class="block invisible bg-pr-400 w-20 h-1 rounded-md text-center -bottom-1 mx-auto relative group-hover:visible"></div>
            </a>
        </li>
    </ul>
</div>

<style>
    /* Add custom font styling */
    .custom-font {
        font-family: 'Century Gothic', sans-serif;
        font-size: 18px; /* Adjust font size as needed */
        font-weight: normal; /* Adjust font weight as needed */
    }
</style>




            </nav>
        </header>
    @else

                    {{-- user when login --}}
                        <header>
                        <nav class="custom-nav border-blue-200 px-4 lg:px-6 py-4 dark:bg-blue-700 ">
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


                    <!-- @if (Auth::user()->role == 'admin')
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
                    @else -->

          <!-- Customer login POV -->
<div class="hidden justify-between items-center w-full lg:flex lg:w-auto" id="mobile-menu-2">
    <ul class="flex flex-col mt-4 font-medium lg:flex-row lg:space-x-8 lg:mt-0">
        <li>
            <a href="/" class="text-white custom-font">
                <div class="group text-center">
                    <div class="group-hover:cursor-pointer">Home</div>
                    <div class="block invisible bg-pr-400 w-12 h-1 rounded-md text-center -bottom-1 mx-auto relative group-hover:not visible">
                    </div>
                </div>
            </a>
        </li>
        <li>
            <a href="{{ route('cars') }}" class="text-white custom-font">
                <div class="group text-center">
                    <div class="group-hover:cursor-pointer">Cars</div>
                    <div class="block invisible bg-pr-400 w-8 h-1 rounded-md text-center -bottom-1 mx-auto relative group-hover:not visible">
                    </div>
                </div>
            </a>
        </li>
        <li>
            <a href="/location" class="text-white custom-font">
                <div class="group text-center">
                    <div class="group-hover:cursor-pointer">Location</div>
                    <div class="block invisible bg-pr-400 w-16 h-1 rounded-md text-center -bottom-1 mx-auto relative group-hover:not visible">
                    </div>
                </div>
            </a>
        </li>
        <li>
            <a href="/contact_us" class="text-white custom-font">
                <div class="group text-center">
                    <div class="group-hover:cursor-pointer">Contact</div>
                    <div class="block invisible bg-pr-400 w-20 h-1 rounded-md text-center -bottom-1 mx-auto relative group-hover:not visible">
                    </div>
                </div>
            </a>
        </li>
    </ul>
</div>
<style>
    .custom-font {
        font-family: 'Century Gothic', Arial, sans-serif;
        font-size: 18px; /* Adjust as needed */
    }
</style>


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
<footer class="footer">
    <div class="footer-top">
        <p>Get connected with us on social networks:</p>
        <div class="social-icons">
            <a href="https://www.facebook.com/r3carrental"><img src="/images/icons/Facebook_icon.svg" alt="Facebook"></a>
            <a href="#"><img src="/images/icons/google.svg" alt="Google"></a>
            <a href="https://github.com/iceon25"><img src="/images/icons/github.svg" alt="GitHub"></a>
        </div>
    </div>
    <div class="footer-main">
        <div class="footer-section">
            <h3>R3 Garage Car Rental</h3>
            <p>Your journey begins with us. Reliable cars, unbeatable service, and a commitment to getting you there.</p>
            </div>
        <div class="footer-section">
            <h3>Legal</h3>
            <ul>
                <li><a href="{{route('terms_conditions')}}">Terms & Conditions</a></li>
                <li><a href="{{route('privacy_policy')}}">Privacy Policy</a></li>
            </ul>
        </div>
        <div class="footer-section">
    <h3>Contact</h3>
    <ul>
        <li>
            <img src="/images/icons/pin.png" alt="Location">
            <span>36 Friendship St. Friendly Village 1,<br>
                Marikina City, Philippines<br>
                Zip Code/Postal code: 1807</span>
        </li>
        <li>
            <img src="/images/icons/phone.png" alt="Phone">
            <span>+63-955-379-3727</span>
        </li>
        <li>
            <img src="/images/icons/web.png" alt="Email">
            <span>r3garage@gmail.com</span>
        </li>
    </ul>
</div>

    </div>
    <div class="footer-bottom">
        <p>© 2024 R3 Garage Car Rental. All Rights Reserved.</p>
    </div>
</footer>

<style>
  .footer {
    background-color: #2c2c2c;
    color: white;
    font-family: 'Century Gothic', Arial, sans-serif;
    padding: 20px 0;
    text-align: center; /* Center text and elements inside the footer */
}

.footer-top {
    background-color: #3B9ABF; /* Changed the violet color to #3B9ABF */
    text-align: center;
    padding: 15px 0;
}

.footer-top p {
    margin: 0;
    font-size: 14px;
}

.footer-top .social-icons {
    margin-top: 10px;
}

.footer-top .social-icons a {
    margin: 0 10px;
    display: inline-block;
}

.footer-top .social-icons img {
    width: 20px;
    height: 20px;
}

.footer-main {
    display: flex;
    justify-content: center; /* Center the footer-main content */
    flex-wrap: wrap;
    padding: 20px;
    max-width: 1200px;
    margin: auto;
    text-align: left; /* Align text left within each section */
}

.footer-section {
    flex: 1;
    min-width: 200px;
    margin: 20px;
}

.footer-section h3 {
    font-size: 18px;
    margin-bottom: 15px;
    font-weight: bold;
    text-transform: uppercase;
    text-align: center; /* Center the section titles */
}

.footer-section p {
    font-size: 14px;
    line-height: 1.5;
}

.footer-section ul {
    list-style: none;
    padding: 0;
    text-align: center; /* Center the list items */
}

.footer-section ul li {
    margin-bottom: 10px;
    font-size: 14px;
    display: flex;
    align-items: center;
    justify-content: center; /* Center the content inside list items */
}

.footer-section ul li img {
    margin-right: 10px;
    width: 20px;
    height: 20px;
}

.footer-section ul li a {
    color: white;
    text-decoration: none;
    transition: color 0.3s ease, transform 0.3s ease;
}

.footer-section ul li a:hover {
    color: #d1ecff;
}

.footer-bottom {
    background-color: #212121;
    text-align: center;
    padding: 10px 0;
}

.footer-bottom p {
    margin: 0;
    font-size: 12px;
}

@media (max-width: 768px) {
    .footer-main {
        flex-direction: column;
        align-items: center;
    }

    .footer-section {
        margin: 10px 0;
    }
}




</style>

@endif

</body>
</html>