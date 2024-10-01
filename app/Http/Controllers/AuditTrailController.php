<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuditTrail;

class AuditTrailController extends Controller
{
    public function index()
    {
        // Retrieve the logs from the database
        $logs = AuditTrail::orderBy('created_at', 'desc')->paginate(10);
        
        

        // Pass the logs to the view
        return view('audittrail.index', compact('logs'));
        
        
    }
}
