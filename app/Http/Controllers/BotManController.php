<?php

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory as BotManBotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use Illuminate\Http\Request;

class BotManController extends Controller
{
    public function handle()
    {
        $config = [];

        DriverManager::loadDriver(\BotMan\Drivers\Web\WebDriver::class);

        $botman = BotManBotManFactory::create($config);

        // Automatically send welcome message and show buttons when the conversation starts
        $botman->hears('start', function (BotMan $bot) {
            $question = Question::create('Hi there! Welcome to R3 Car Rental Garage. Please let us know how we can help you.')
                ->addButtons([
                    Button::create('Rental Rates')->value('rental rates'),
                    Button::create('Rental Policies')->value('rental policies'),
                    Button::create('Book a Car')->value('book a car'),
                    Button::create('Contact Us')->value('contact us'),
                    Button::create('FAQs – Frequently Asked Questions')->value('faqs')
                ]);
            $bot->reply($question);
        });

        // Other conversations
        $botman->hears('rental rates', function (BotMan $bot) {
            $bot->reply('Here are the rental rates for our cars:');
            $bot->reply('Economy Cars: $25/day\nSUVs: $50/day\nLuxury Cars: $100/day');
            $bot->reply('Note: Rentals outside Metro Manila will incur an additional charge of ₱500 - ₱1000.');
        });

        $botman->hears('rental policies', function (BotMan $bot) {
            $bot->reply('Here are our rental policies:');
            $bot->reply("1. Customers must leave one valid ID for the duration of the rental period.\n2. Only drivers listed in the rental agreement and meeting the minimum age and valid driver's license requirements are authorized to operate the rented vehicle. Foreigners must present and attach their international driver's license and passport to the rental agreement.\n3. The vehicle must be returned with the same fuel level as when rented. Failure to do so will result in refueling charges based on prevailing rates.");
        });

        $botman->hears('book a car', function (BotMan $bot) {
            $bot->reply('Please visit our Car Listing page to choose and book your car.');
        });

        $botman->hears('contact us', function (BotMan $bot) {
            $bot->reply('You can contact us using the following details:');
            $bot->reply('Phone: +1 234 567 890\nEmail: support@r3garage.com\nAddress: 123 Main St, Metro Manila');
        });

        $botman->hears('faqs', function (BotMan $bot) {
            $question = Question::create('Please select a question:')
                ->addButtons([
                    Button::create('Payment Options')->value('payment options'),
                    Button::create('What if I return the car late?')->value('late return policy')
                ]);
            $bot->reply($question);
        });

        $botman->hears('payment options', function (BotMan $bot) {
            $bot->reply('We accept payments via Gcash and Cash only.');
        });

        $botman->hears('late return policy', function (BotMan $bot) {
            $bot->reply('Late returns are subject to an additional hourly rate.');
        });

        $botman->fallback(function(BotMan $bot) {
            $bot->reply('I\'m sorry, I don\'t understand. Could you please rephrase or ask something else?');
        });

        // Start the conversation automatically when the bot is initialized
        $botman->listen();
    }
}
