<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserImages extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'valid_id_url', 'proof_of_billing_url'];

    // Relationship function to define the relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
