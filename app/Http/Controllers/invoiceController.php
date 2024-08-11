<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Reservation;

use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


class InvoiceController extends Controller
{
    public function invoice($reservation_id)
    {
        $reservation = Reservation::find($reservation_id);

        if (!$reservation) {
            abort(404); // Handle case where reservation is not found
        }

        $pdf = PDF::loadView('invoice', compact('reservation'));
        $filename = 'Reservation-' . $reservation_id . '-invoice.pdf';
        
        // Ensure the directory exists, create it if not
        $directory = 'public/invoices/';
        Storage::makeDirectory($directory);

        // Save the PDF to the specified file path
        $pdf->save(storage_path('app/' . $directory . $filename));

        // Return the PDF file as a download
        return $pdf->download('invoice.pdf');
    }

   
}
