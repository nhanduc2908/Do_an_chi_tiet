<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SessionTimeout
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()) {
            $lastActivity = session('last_activity');
            $timeout = config('session.lifetime', 120) * 60;
            
            if ($lastActivity && (time() - $lastActivity) > $timeout) {
                auth()->logout();
                session()->flush();
                return response()->json(['message' => 'Session expired'], 401);
            }
            
            session(['last_activity' => time()]);
        }

        return $next($request);
    }
}