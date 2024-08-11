<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;

class CancelController extends Controller
{
    public function cancelReservation($id)
{
    $reservation = Reservation::findOrFail($id);


    $reservation->status = 'Canceled';
    $reservation->save();

    return redirect()->back()->with('success', 'Reservation canceled successfully.');
}
}
