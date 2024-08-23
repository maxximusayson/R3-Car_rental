<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $table = 'clients';

    protected $fillable = [
        'name',
        'email',
        'profile_picture_url',
        'driver_license',
        'proof_of_billing',
        'created_at',
        'updated_at',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function index()
{
    $clients = Client::withCount('reservations')->get();
    return view('clients.index', compact('clients'));
}
public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}