<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\IpWhitelist as IpWhitelistModel;

class IpWhitelist
{
    public function handle(Request $request, Closure $next)
    {
        $ip = $request->ip();
        
        $whitelistEnabled = config('security.ip_whitelist_enabled', false);
        
        if (!$whitelistEnabled) {
            return $next($request);
        }

        $isWhitelisted = IpWhitelistModel::where('ip_address', $ip)
            ->where('is_active', true)
            ->exists();

        if (!$isWhitelisted) {
            return response()->json(['message' => 'IP not whitelisted'], 403);
        }

        return $next($request);
    }
}