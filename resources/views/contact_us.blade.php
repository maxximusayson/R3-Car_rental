@extends('layouts.myapp')
@section('content')

<!-- Contact Form -->
<div class="max-w-2xl mx-auto my-12 p-8 bg-white rounded-lg shadow-lg" style="font-family: 'Century Gothic', sans-serif;">
    <h2 class="text-3xl font-extrabold text-center text-gray-900 mb-8">Get in Touch</h2>
    <form action="{{ route('contact.submit') }}" method="POST" id="contact-form" class="space-y-6">
        @csrf
        <!-- Name Fields -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                <input type="text" id="first_name" name="first_name"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    placeholder="First Name" required style="font-family: 'Century Gothic', sans-serif;">
            </div>
            <div>
                <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                <input type="text" id="last_name" name="last_name"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    placeholder="Last Name" required style="font-family: 'Century Gothic', sans-serif;">
            </div>
        </div>

        <!-- Contact Info Fields -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    placeholder="you@example.com" required style="font-family: 'Century Gothic', sans-serif;">
            </div>
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                <input type="text" id="phone" name="phone"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    placeholder="Your phone number" required style="font-family: 'Century Gothic', sans-serif;">
            </div>
        </div>

        <!-- Subject Field -->
        <div>
            <label for="subject" class="block text-sm font-medium text-gray-700">Subject</label>
            <select name="subject" id="subject"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                style="font-family: 'Century Gothic', sans-serif;">
                <option value="0" disabled selected>Select a subject</option>
                <option value="reservation">Reservation</option>
                <option value="payment">Payment</option>
                <option value="car problem">Car problem</option>
                <option value="cancelation">Cancelation</option>
                <option value="other">Other</option>
            </select>
        </div>

        <!-- Message Field -->
        <div>
            <label for="message" class="block text-sm font-medium text-gray-700">Your Message</label>
            <textarea id="message" name="message" rows="6"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                placeholder="Leave a comment..." required style="font-family: 'Century Gothic', sans-serif;"></textarea>
        </div>

        <!-- Submit Button -->
        <div class="text-center">
            <button type="submit"
                class="w-full md:w-auto px-6 py-3 bg-indigo-600 text-white font-bold rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out"
                style="font-family: 'Century Gothic', sans-serif;">
                Send Message
            </button>
        </div>
    </form>
</div>

@endsection
