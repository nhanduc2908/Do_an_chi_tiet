<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    public function index()
    {
        $roles = Role::withCount('users')->orderBy('level', 'desc')->get();
        return response()->json($roles);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles',
            'display_name' => 'required|string',
            'level' => 'required|integer|min:0|max:100',
            'color' => 'nullable|string',
            'requires_key' => 'boolean',
            'requires_otp' => 'boolean',
        ]);

        $role = Role::create($validated);
        return response()->json($role, 201);
    }

    public function show(Role $role)
    {
        $role->load('permissions');
        $role->user_count = User::where('role', $role->name)->count();
        return response()->json($role);
    }

    public function update(Request $request, Role $role)
    {
        if ($role->name === 'admin') {
            return response()->json(['message' => 'Cannot modify admin role'], 403);
        }

        $validated = $request->validate([
            'display_name' => 'sometimes|string',
            'level' => 'sometimes|integer|min:0|max:100',
            'color' => 'nullable|string',
            'requires_key' => 'boolean',
            'requires_otp' => 'boolean',
        ]);

        $role->update($validated);
        return response()->json($role);
    }

    public function destroy(Role $role)
    {
        if ($role->name === 'admin') {
            return response()->json(['message' => 'Cannot delete admin role'], 403);
        }

        if (User::where('role', $role->name)->count() > 0) {
            return response()->json(['message' => 'Cannot delete role with assigned users'], 422);
        }

        $role->delete();
        return response()->json(['message' => 'Role deleted']);
    }

    public function permissions(Role $role)
    {
        $permissions = $role->permissions;
        $allPermissions = Permission::all();
        
        return response()->json([
            'role_permissions' => $permissions,
            'all_permissions' => $allPermissions,
        ]);
    }

    public function assignPermissions(Request $request, Role $role)
    {
        $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->permissions()->sync($request->permissions);
        
        return response()->json(['message' => 'Permissions assigned']);
    }

    public function initializeDefault()
    {
        if (Role::count() > 0) {
            return response()->json(['message' => 'Roles already exist'], 400);
        }

        $defaultRoles = [
            ['name' => 'admin', 'display_name' => 'Quản trị viên', 'level' => 100, 'color' => '#FF0000'],
            ['name' => 'dev', 'display_name' => 'Lập trình viên', 'level' => 30, 'color' => '#00FF00'],
            ['name' => 'ba', 'display_name' => 'Phân tích nghiệp vụ', 'level' => 35, 'color' => '#00CCFF'],
            ['name' => 'da', 'display_name' => 'Chuyên gia dữ liệu', 'level' => 35, 'color' => '#9900FF'],
            ['name' => 'hr', 'display_name' => 'Nhân sự', 'level' => 20, 'color' => '#FF9900'],
            ['name' => 'qa', 'display_name' => 'Kiểm thử', 'level' => 40, 'color' => '#FF00FF'],
            ['name' => 'secops', 'display_name' => 'Vận hành bảo mật', 'level' => 50, 'color' => '#00FFFF'],
            ['name' => 'auditor', 'display_name' => 'Kiểm toán', 'level' => 60, 'color' => '#FFFF00'],
            ['name' => 'manager', 'display_name' => 'Quản lý', 'level' => 70, 'color' => '#FF6600'],
            ['name' => 'ciso', 'display_name' => 'Giám đốc ATTT', 'level' => 90, 'color' => '#FF0066'],
        ];

        foreach ($defaultRoles as $roleData) {
            Role::create($roleData);
        }

        return response()->json(['message' => 'Default roles initialized']);
    }
}