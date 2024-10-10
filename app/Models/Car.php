<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand',
        'model',
        'engine',
        'quantity',
        'price_per_day',
        'description',
        'status',
        'branch',
        // 'available' is not needed here as we use reservation logic to check availability
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function insurance()
    {
        return $this->hasOne(Insurance::class);
    }

    public function images()
    {
        return $this->hasMany(CarImage::class); // Adjust this to your actual model
    }

    // Check if the car is available on a specific date
    public function isAvailableOn($date)
    {
        return !$this->reservations()->whereDate('start_date', '<=', $date)
                                     ->whereDate('end_date', '>=', $date)
                                     ->exists();
    }

    // Check if the car is available between two dates
    public function isAvailableBetween($startDate, $endDate)
    {
        return !$this->reservations()->whereDate('start_date', '<=', $endDate)
                                     ->whereDate('end_date', '>=', $startDate)
                                     ->exists();
    }

    public function videos()
{
    return $this->hasMany(CarVideo::class);
}
public function ratings()
{
    return $this->hasMany(Rating::class);
}
    
}
