<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\Auth\RolePermissionService;

class CheckPermission
{
    protected RolePermissionService $permissionService;

    public function __construct(RolePermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    public function handle(Request $request, Closure $next, $permission)
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        if (!$this->permissionService->userHasPermission($user, $permission)) {
            return response()->json(['message' => 'Forbidden - Missing permission'], 403);
        }

        return $next($request);
    }
}