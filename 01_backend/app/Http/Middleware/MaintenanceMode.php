<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class MaintenanceMode
{
    public function handle(Request $request, Closure $next)
    {
        if (config('app.maintenance_mode', false)) {
            return response()->json(['message' => 'System under maintenance'], 503);
        }
        return $next($request);
    }
}