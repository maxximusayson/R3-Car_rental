<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LastKnownLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'gps_id',
        'latitude',
        'longitude',
        'recorded_at',
    ];

    public function saveLastKnownLocation(Request $request)
{
    $data = $request->validate([
        'gps_id' => 'nullable|string',
        'latitude' => 'required|numeric',
        'longitude' => 'required|numeric',
        'speed' => 'nullable|numeric',
        'satellites' => 'nullable|integer',
        'gps_status' => 'nullable|string',
        'timestamp' => 'nullable|date',
    ]);

    LastKnownLocation::create($data);

    return response()->json(['message' => 'Location saved successfully.']);
}
}
