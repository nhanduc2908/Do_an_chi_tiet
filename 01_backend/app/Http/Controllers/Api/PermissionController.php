<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    public function index()
    {
        $permissions = Permission::orderBy('module')->get();
        return response()->json($permissions);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:permissions',
            'display_name' => 'required|string',
            'module' => 'required|string',
        ]);

        $permission = Permission::create($validated);
        return response()->json($permission, 201);
    }

    public function update(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);
        
        $validated = $request->validate([
            'display_name' => 'sometimes|string',
            'description' => 'nullable|string',
        ]);

        $permission->update($validated);
        return response()->json($permission);
    }

    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        
        if ($permission->roles()->count() > 0) {
            return response()->json(['message' => 'Cannot delete permission assigned to roles'], 422);
        }
        
        $permission->delete();
        return response()->json(['message' => 'Permission deleted']);
    }

    public function modules()
    {
        $modules = Permission::select('module')->distinct()->pluck('module');
        return response()->json($modules);
    }

    public function rolePermissions(Role $role)
    {
        $permissions = $role->permissions;
        $allPermissions = Permission::all();
        
        return response()->json([
            'assigned' => $permissions,
            'available' => $allPermissions->diff($permissions),
        ]);
    }
}