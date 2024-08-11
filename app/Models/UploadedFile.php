<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadedFile extends Model
{
    use HasFactory;

    protected $fillable = ['filename', 'path', 'user_id'];

    /**
     * Get the user that owns the uploaded file.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
