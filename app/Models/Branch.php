<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = ['name', 'location', 'latitude', 'longitude'];

    // Add any relationships or custom methods here if needed
}
