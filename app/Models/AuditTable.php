<?php

// AuditTable.php model

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AuditTable extends Model
{
    use HasFactory;

    protected $table = 'audit_tables';

    protected $fillable = [
        'action',
        'user',
        'details',  // Make sure details is fillable if used for extra information
    ];

    protected $dates = ['created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

