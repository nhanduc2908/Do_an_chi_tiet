<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Role;

class CheckKeyForHighRole
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $roleConfig = Role::getRolesList()[$user->role] ?? [];
        
        if (($roleConfig['requires_key'] ?? false) && !$request->hasHeader('X-Security-Key')) {
            return response()->json(['message' => 'Security key required'], 401);
        }

        return $next($request);
    }
}