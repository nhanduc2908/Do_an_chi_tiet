<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Role;

class RequireOtpForLowRole
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $roleConfig = Role::getRolesList()[$user->role] ?? [];
        
        if (($roleConfig['requires_otp'] ?? false) && !$request->hasHeader('X-OTP')) {
            return response()->json(['message' => 'OTP required'], 401);
        }

        return $next($request);
    }
}