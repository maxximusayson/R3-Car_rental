<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\AuditTrail;
use Illuminate\Support\Facades\Auth;

class LogActivity
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (Auth::check()) {
            $user = Auth::user()->name;
            $action = $request->path(); // You can customize this to log more specific actions

            AuditTrail::create([
                'action' => $action,
                'user' => $user
            ]);
        }

        return $response;
    }
}

