<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GpsData extends Model
{
    use HasFactory;

    protected $table = 'gps_data'; // Specify the table name if it's not the default

    // Specify the fields that can be mass-assigned
    protected $fillable = [
        'gps_id',
        'latitude',
        'longitude',
        'speed',
        'satellites',
        'gps_status',
        'timestamp',
    ];
}
