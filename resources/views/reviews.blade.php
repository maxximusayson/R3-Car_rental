@extends('layouts.myapp')
@section('content')
<style>
    /* Font style for paragraphs */
    .custom-font {
        font-family: sans-serif; /* Fallback font */
        font-size: 18px;
        line-height: 1.6;
    }

    /* Font style for headings */
    .font-car {
        font-family: Arial Black, Arial, sans-serif; /* Fallback font */
        font-weight: 900; /* Ensure Arial Black font weight */
        /* Add other heading styles as needed */
    }

    /* Background image for the page */
    body {
        background-color: #f3f4f6; /* Light gray background */
    }

    .form-container {
        max-width: 800px;
        margin: auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        font-size: 16px;
        font-weight: bold;
        color: #333;
    }

    .form-input {
        width: 100%;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }

    .form-select {
        width: 100%;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
        background-color: #f9f9f9;
    }

    .form-textarea {
        width: 100%;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
        resize: none;
    }

    .form-button {
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        background-color: #007bff; /* Blue button color */
        color: #fff;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .form-button:hover {
        background-color: #0056b3; /* Darker blue on hover */
    }
</style>

<div class="form-container">
    <h2 class="text-4xl font-extrabold tracking-tight text-center text-black font-car mb-6">Write Your Reviews</h2>
    <p class="text-center text-gray-700 font-car mb-8">Welcome to our car rental reviews platform! Discover authentic and insightful reviews from fellow renters to make informed decisions about your next car rental. Find ratings, feedback, and experiences shared by real users to help you choose the perfect vehicle for your journey.</p>

    <form action="#" class="space-y-8" id="contact-form">
        <div class="form-group">
            <label for="first-name" class="form-label">First Name</label>
            <input type="text" id="first-name" class="form-input" style="width: 70%;" placeholder="Firstname" required>
        </div>

        <div class="form-group">
            <label for="subject" class="form-label">Subject</label>
            <select name="subject" id="subject" class="form-select" style="width: 50%;">
                <option value="0" disabled selected>Select subject</option>
                <option value="reservation">Reservation</option>
                <option value="payment">Payment</option>
                <option value="car problem">Car problem</option>
                <option value="cancelation">Cancellation</option>
                <option value="other">Other</option>
            </select>
        </div>

        <div class="form-group">
            <label for="rating" class="form-label">Rating: </label>
            <div class="rating-container">
                <input type="hidden" name="rating" id="rating" value="5"> <!-- Default value -->
                <button type="button" class="star-btn" onclick="toggleRatingDropdown()">5 ★★★★★</button>
                <div id="ratingDropdown" class="rating-dropdown">
                    <button class="star-option" onclick="setRating(5)">5 ★★★★★</button>
                    <button class="star-option" onclick="setRating(4)">4 ★★★★</button>
                    <button class="star-option" onclick="setRating(3)">3 ★★★</button>
                    <button class="star-option" onclick="setRating(2)">2 ★★</button>
                    <button class="star-option" onclick="setRating(1)">1 ★</button>
                </div>
            </div>

            <style>
                /* Existing styles... */

                .rating-container {
                    position: relative;
                    display: inline-block;
                    width: 25%;
                }

                .star-btn {
                    width: 100%;
                    padding: 10px;
                    border: 1px solid #ccc;
                    border-radius: 5px;
                    background-color: #fff;
                    text-align: left;
                    cursor: pointer;
                }

                .rating-dropdown {
                    position: absolute;
                    top: 100%;
                    left: 0;
                    width: 100%;
                    border: 1px solid #ccc;
                    border-radius: 5px;
                    background-color: #fff;
                    display: none;
                }

                .star-option {
                    width: 100%;
                    padding: 8px;
                    background-color: #fff;
                    border: none;
                    border-bottom: 1px solid #ccc;
                    text-align: left;
                    cursor: pointer;
                }

                .star-option:last-child {
                    border-bottom: none;
                }
            </style>

        </div>

        <script>
            let dropdownVisible = false;

            function toggleRatingDropdown() {
                const dropdown = document.getElementById('ratingDropdown');
                dropdownVisible = !dropdownVisible;
                dropdown.style.display = dropdownVisible ? 'block' : 'none';
            }

            function setRating(rating) {
                document.getElementById('rating').value = rating;
                document.querySelector('.star-btn').textContent = rating + ' Stars';
                toggleRatingDropdown(); // Hide the dropdown after selecting a rating
            }
        </script>

        <div class="form-group">
            <label for="message" class="form-label">Review Message</label>
            <textarea id="message" rows="6" class="form-textarea" placeholder="Leave a comment..."></textarea>
        </div>


        <button type="submit" class="form-button">Submit Review</button>
    </form>
</div>

<script>
    $(document).ready(function() {
        function showPopup() {
            alert('Thank you! We have received your message.');
        }

        $('#contact-form').submit(function(e) {
            e.preventDefault();
            showPopup();
        });
    });
</script>
@endsection
