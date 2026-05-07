<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\IpBlacklist as IpBlacklistModel;

class IpBlacklist
{
    public function handle(Request $request, Closure $next)
    {
        $ip = $request->ip();
        
        $isBlacklisted = IpBlacklistModel::where('ip_address', $ip)
            ->where(function($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->exists();

        if ($isBlacklisted) {
            return response()->json(['message' => 'IP blocked'], 403);
        }

        return $next($request);
    }
}