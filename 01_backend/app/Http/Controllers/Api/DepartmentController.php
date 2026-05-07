<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Department::with('company', 'manager');
        
        if ($request->has('company_id')) {
            $query->where('company_id', $request->company_id);
        }
        
        $departments = $query->paginate(20);
        return response()->json($departments);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'code' => 'required|string|unique:departments',
            'company_id' => 'required|exists:companies,id',
            'manager_id' => 'nullable|exists:users,id',
        ]);

        $department = Department::create($validated);
        return response()->json($department, 201);
    }

    public function show(Department $department)
    {
        $department->load(['company', 'manager', 'teams', 'users' => function($q) {
            $q->limit(20);
        }]);
        return response()->json($department);
    }

    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string',
            'manager_id' => 'nullable|exists:users,id',
        ]);

        $department->update($validated);
        return response()->json($department);
    }

    public function destroy(Department $department)
    {
        $department->delete();
        return response()->json(['message' => 'Department deleted']);
    }

    public function users(Department $department)
    {
        $users = $department->users()->paginate(20);
        return response()->json($users);
    }

    public function teams(Department $department)
    {
        $teams = $department->teams()->paginate(20);
        return response()->json($teams);
    }
}