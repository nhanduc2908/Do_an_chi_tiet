<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin,manager')->except(['show', 'update']);
    }

    public function index(Request $request)
    {
        $query = User::with('company', 'department');
        
        if ($request->has('role')) {
            $query->where('role', $request->role);
        }
        if ($request->has('company_id')) {
            $query->where('company_id', $request->company_id);
        }
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }
        
        $users = $query->paginate(20);
        return response()->json($users);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|exists:roles,name',
            'company_id' => 'nullable|exists:companies,id',
            'department_id' => 'nullable|exists:departments,id',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'company_id' => $validated['company_id'],
            'department_id' => $validated['department_id'],
            'status' => 'active',
        ]);

        return response()->json($user, 201);
    }

    public function show($id)
    {
        $user = User::with(['company', 'department', 'team', 'rank'])->findOrFail($id);
        
        if (auth()->user()->role !== 'admin' && auth()->id() != $id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        if (auth()->user()->role !== 'admin' && auth()->id() != $id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string',
            'phone' => 'nullable|string',
            'avatar' => 'nullable|image|max:2048',
            'department_id' => 'nullable|exists:departments,id',
            'team_id' => 'nullable|exists:teams,id',
        ]);

        if (auth()->user()->role === 'admin' && $request->has('role')) {
            $validated['role'] = $request->role;
        }

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $path;
        }

        $user->update($validated);
        return response()->json($user);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->id === auth()->id()) {
            return response()->json(['message' => 'Cannot delete yourself'], 422);
        }
        
        $user->delete();
        return response()->json(['message' => 'User deleted']);
    }

    public function resetPassword(Request $request, $id)
    {
        $request->validate(['password' => 'required|string|min:8']);
        
        $user = User::findOrFail($id);
        $user->update(['password' => Hash::make($request->password)]);
        
        return response()->json(['message' => 'Password reset']);
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $newStatus = $user->status === 'active' ? 'inactive' : 'active';
        $user->update(['status' => $newStatus]);
        
        return response()->json(['message' => "User {$newStatus}"]);
    }

    public function permissions($id)
    {
        $user = User::findOrFail($id);
        $permissions = Role::getPermissions($user->role);
        
        return response()->json($permissions);
    }
}