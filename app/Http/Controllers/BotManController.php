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

        $botman->hears('hello', function (BotMan $bot) {
            $question = Question::create('Hello! Welcome to R3 Garage Car Rental. How can I assist you today?')
                ->addButtons([
                    Button::create('Browse cars')->value('browse cars'),
                    Button::create('What\'s on sale?')->value('what\'s on sale?'),
                    Button::create('Learn about us')->value('learn about us'),
                    Button::create('Rental process')->value('rental process'),
                    Button::create('Contact support')->value('contact support')
                ]);
            $bot->reply($question);
        });

        $botman->hears('browse cars', function (BotMan $bot) {
            $bot->typesAndWaits(1);
            $bot->reply('Here are the categories of cars you can rent:');
            $question = Question::create('Please choose a category:')
                ->addButtons([
                    Button::create('Economy')->value('economy cars'),
                    Button::create('SUV')->value('SUV cars'),
                    Button::create('Luxury')->value('luxury cars')
                ]);
            $bot->reply($question);
        });

        $botman->hears('economy cars', function (BotMan $bot) {
            $bot->typesAndWaits(1);
            $bot->reply('Here are our economy cars available for rent:');
            $bot->reply('1. Car A: $25/day, fuel-efficient, compact.\n2. Car B: $30/day, reliable, budget-friendly.\n3. Car C: $35/day, spacious, affordable.');
            $question = Question::create('Would you like to book one of these cars or need more information?')
                ->addButtons([
                    Button::create('Book Car A')->value('book car A'),
                    Button::create('Book Car B')->value('book car B'),
                    Button::create('Book Car C')->value('book car C'),
                    Button::create('More Info')->value('more info economy cars')
                ]);
            $bot->reply($question);
        });

        $botman->hears('SUV cars', function (BotMan $bot) {
            $bot->typesAndWaits(1);
            $bot->reply('Here are our SUVs available for rent:');
            $bot->reply('1. Car D: $50/day, spacious, family-friendly.\n2. Car E: $55/day, powerful, all-terrain.\n3. Car F: $60/day, luxurious, high-performance.');
            $question = Question::create('Would you like to book one of these cars or need more information?')
                ->addButtons([
                    Button::create('Book Car D')->value('book car D'),
                    Button::create('Book Car E')->value('book car E'),
                    Button::create('Book Car F')->value('book car F'),
                    Button::create('More Info')->value('more info SUV cars')
                ]);
            $bot->reply($question);
        });

        $botman->hears('luxury cars', function (BotMan $bot) {
            $bot->typesAndWaits(1);
            $bot->reply('Here are our luxury cars available for rent:');
            $bot->reply('1. Car G: $100/day, premium comfort, advanced features.\n2. Car H: $120/day, high-end, ultimate performance.\n3. Car I: $150/day, elite luxury, top-tier technology.');
            $question = Question::create('Would you like to book one of these cars or need more information?')
                ->addButtons([
                    Button::create('Book Car G')->value('book car G'),
                    Button::create('Book Car H')->value('book car H'),
                    Button::create('Book Car I')->value('book car I'),
                    Button::create('More Info')->value('more info luxury cars')
                ]);
            $bot->reply($question);
        });

        $botman->hears('what\'s on sale?', function (BotMan $bot) {
            $bot->typesAndWaits(1);
            $bot->reply('These are our current sales:');
            $bot->reply('1. Discount on Car A: $20/day.\n2. Discount on Car D: $45/day.\n3. Discount on Car G: $90/day.');
            $question = Question::create('Would you like to take advantage of any of these offers?')
                ->addButtons([
                    Button::create('Book Car A')->value('book car A'),
                    Button::create('Book Car D')->value('book car D'),
                    Button::create('Book Car G')->value('book car G'),
                    Button::create('More Info')->value('more info sales')
                ]);
            $bot->reply($question);
        });

        $botman->hears('learn about us', function (BotMan $bot) {
            $bot->typesAndWaits(1);
            $bot->reply('R3 Garage Car Rental is dedicated to providing a wide range of vehicles to meet your needs. Our mission is to provide quality service and affordable prices. We offer everything from economy cars to luxury vehicles, ensuring that you find the perfect car for your journey.');
            $bot->reply('We have been serving customers for over 10 years, and our commitment to excellence has earned us a reputation for reliability and customer satisfaction.');
            $question = Question::create('Would you like to know more about our services or contact support?')
                ->addButtons([
                    Button::create('Our Services')->value('our services'),
                    Button::create('Contact Support')->value('contact support')
                ]);
            $bot->reply($question);
        });

        $botman->hears('rental process', function (BotMan $bot) {
            $bot->typesAndWaits(1);
            $bot->reply('The rental process at R3 Garage Car Rental is simple and straightforward:');
            $bot->reply('1. Choose a car from our wide selection.\n2. Provide necessary documents (ID, driver\'s license, etc.).\n3. Make a payment.\n4. Pick up your car and enjoy your journey.');
            $question = Question::create('Do you have any questions about the rental process or need assistance?')
                ->addButtons([
                    Button::create('More Info')->value('more info rental process'),
                    Button::create('Contact Support')->value('contact support')
                ]);
            $bot->reply($question);
        });

        $botman->hears('contact support', function (BotMan $bot) {
            $bot->typesAndWaits(1);
            $bot->reply('You can contact our support team via the following methods:');
            $bot->reply('Phone: +1 234 567 890\nEmail: support@r3garage.com\nOur support team is available 24/7 to assist you with any questions or concerns.');
        });

        $botman->fallback(function(BotMan $bot) {
            $bot->typesAndWaits(1);
            $bot->reply('I\'m sorry, I don\'t understand. Could you please rephrase or ask something else?');
        });

        // Schedule auto-pilot messages with buttons
        $botman->hears('start auto-pilot', function (BotMan $bot) {
            $bot->typesAndWaits(1); // Reduced typing delay to 1 second
            $bot->reply('Hello! Welcome to R3 Garage Car Rental. How can I assist you today?');

            $question = Question::create('Please select an option below:')
                ->addButtons([
                    Button::create('Browse cars')->value('browse cars'),
                    Button::create('What\'s on sale?')->value('what\'s on sale?'),
                    Button::create('Learn about us')->value('learn about us'),
                    Button::create('Rental process')->value('rental process'),
                    Button::create('Contact support')->value('contact support')
                ]);
                
            $bot->typesAndWaits(1); // Reduced typing delay to 1 second
            $bot->reply($question);
        });

        $botman->listen();
    }
}
