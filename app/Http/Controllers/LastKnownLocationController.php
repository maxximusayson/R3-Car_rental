<?php

namespace App\Http\Controllers;

use App\Models\LastKnownLocation;
use Illuminate\Http\Request;

class LastKnownLocationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'gps_id' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'recorded_at' => 'nullable|date',
        ]);

        $location = LastKnownLocation::create($request->all());

        return response()->json($location, 201);
    }
}
