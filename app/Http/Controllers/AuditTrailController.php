<?php

namespace App\Http\Controllers;

use App\Models\AuditTable;  // Use the correct model
use Illuminate\Http\Request;

class AuditTrailController extends Controller
{
    public function index()
    {
        // Retrieve audit logs
        $audits = AuditTable::latest() // Get the latest records
            ->with(['user', 'reservation', 'payment'])  // Load related models if necessary
            ->paginate(10); // Adjust pagination size

        // Pass the audits to the view
        return view('audittrail.index', compact('audits'));
    }

    public function showAuditTrail()
    {
        return $this->index();  // Reuse the index method for showAuditTrail
    }
}
