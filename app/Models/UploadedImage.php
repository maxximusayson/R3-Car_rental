<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadedImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'valid_id_url',
        'proof_of_billing_url',
    ];
}
