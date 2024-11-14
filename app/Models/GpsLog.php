<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GpsLog extends Model
{
    use HasFactory;

    // Define the table name if it's not the plural of the model name
    protected $table = 'gps_log';

    // Define the fillable properties for mass assignment
    protected $fillable = [
        'car_id',
        'gps_id',
        'filename',
        'date',
    ];

    // Optional: If you want Eloquent to manage the timestamps (created_at and updated_at)
    public $timestamps = true;

    // Define the relationship with the Car model
    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
