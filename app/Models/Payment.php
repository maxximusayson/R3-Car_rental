<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    // Define the table name if it doesn't follow the Laravel convention
    protected $table = 'payments';

    // Define any fillable fields if you intend to use mass assignment
    protected $fillable = ['user_id', 'amount', 'method', 'status'];

    // Define relationships, if any
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Define other relationships if necessary
}
