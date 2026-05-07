<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionService
{
    public function getUserPermissions(User $user): array
    {
        if ($user->role === 'admin') {
            return Permission::pluck('name')->toArray();
        }
        return Role::getPermissions($user->role);
    }

    public function userHasPermission(User $user, string $permission): bool
    {
        if ($user->role === 'admin') return true;
        return in_array($permission, Role::getPermissions($user->role));
    }

    public function getRoleLevel(string $role): int
    {
        $levels = [
            'admin' => 100, 'ciso' => 90, 'manager' => 70,
            'auditor' => 60, 'secops' => 50, 'qa' => 40,
            'ba' => 35, 'da' => 35, 'dev' => 30, 'hr' => 20
        ];
        return $levels[$role] ?? 10;
    }
}