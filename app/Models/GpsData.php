<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GpsData extends Model
{
    use HasFactory;

    // Specify the table name if it's different from the model name
    protected $table = 'gps_data';

    // Define the fillable properties
    protected $fillable = [
        'gps_id',
        'latitude',
        'longitude',
        'speed',
        'satellites',
        'gps_status',
        'timestamp',
    ];

    // Optional: Define any relationships or additional methods
}
