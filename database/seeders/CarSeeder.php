<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $cars = [
            [
                'brand' => 'Toyota',
                'model' => 'Innova',
                'engine' => '2.8L',
                'price_per_day' => 2500,
                 'image' => 'public/images/cars/Toyota-Innova-2.4L-4YTDZ9VRI4.png',
                'quantity' => 1,
                'status' => 'Available',
            ],
            [
                'brand' => 'Toyota',
                'model' => 'Fortuner',
                'engine' => '2.5L',
                'price_per_day' => 3000,
                'image' => 'public/images/cars/Toyota-Fortuner-2.5L-BOQaq7gAtp.png',
                'quantity' => 1,
                'status' => 'Available',
                'reduce' => 10,
                'stars' => 5,
            ],
            [
                'brand' => 'Nissan',
                'model' => 'Navarra',
                'engine' => '2.4L',
                'price_per_day' => 3000,
                'image' => 'public/images/cars/Nissan-Navara-2.4L-pwwyMJsKzx.png',
                'quantity' => 1,
                'status' => 'Available',
                'reduce' => 0,
                'stars' => 5,
            ],
            [
                'brand' => 'Toyota',
                'model' => 'GL GRANDIA',
                'engine' => '2.8L',
                'price_per_day' => 4500,
                'image' => '/public/images/cars/TOYOTA-GL GRANDIA-2.8L-1DJW4Qp4B9.jpg',
                'quantity' => 1,
                'status' => 'Available',

                'reduce' => 20,
                'stars' => 5,
            ],
            [
                'brand' => 'Toyota',
                'model' => 'Vios',
                'engine' => '2.4L',
                'price_per_day' => 2000,
                'image' => '/public/images/cars/Toyota-Vios-2.4L-Pyx2BT4PC3.png',
                'quantity' => 1,
                'status' => 'Available',

                'reduce' => 10,
                'stars' => 5,
            ],
            [
                'brand' => 'Mitsubishi',
                'model' => 'Montero',
                'engine' => '2.5L',
                'price_per_day' => 2000,
                'image' => 'public/images/cars/Mitsubishi-Montero-2.5L-rZypeP6Tqh.png',
                'quantity' => 1,
                'status' => 'Available',

                'reduce' => 50,
                'stars' => 5,
            ],
        ];

        foreach ($cars as $car) {
            DB::table('cars')->insert([
                'brand' => $car['brand'],
                'model' => $car['model'],
                'engine' => $car['engine'],
                'price_per_day' => $car['price_per_day'],
                'image' => $car['image'],
                'quantity' => $car['quantity'],
                'status' => $car['status'],
                // 'reduce' => $car['reduce'],
                // 'stars' => $car['stars'],
            ]);
        }
    }
}
