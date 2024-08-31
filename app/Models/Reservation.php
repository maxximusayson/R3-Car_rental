<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function document()
    {
        return $this->hasOne(Document::class);
    }

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];
    protected $fillable = [
        'full_name',
        'email',
        'start_date',
        'end_date',
        'car_brand',
        'car_model',
        'price_per_day',
        'payment_method',
        'driver_license',  // Ensure this is included
        'valid_id',        // Ensure this is included
        'remaining_balance', // Ensure this is included
        'amount_paid',
        // other fields...
    ];

    public function getRemainingBalanceAttribute()
{
    $totalPaid = $this->payments()->sum('amount_paid');
    return $this->total_price - $totalPaid;
}

public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    
}
