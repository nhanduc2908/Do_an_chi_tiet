<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $companies = Company::withCount('users', 'evaluations')->paginate(20);
        return response()->json($companies);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:companies',
            'code' => 'required|string|unique:companies',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        $company = Company::create($validated);
        return response()->json($company, 201);
    }

    public function show(Company $company)
    {
        $company->load(['departments', 'users' => function($q) {
            $q->limit(10);
        }]);
        return response()->json($company);
    }

    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|unique:companies,name,' . $company->id,
            'code' => 'sometimes|string|unique:companies,code,' . $company->id,
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'status' => 'sometimes|in:active,inactive',
        ]);

        $company->update($validated);
        return response()->json($company);
    }

    public function destroy(Company $company)
    {
        $company->delete();
        return response()->json(['message' => 'Company deleted']);
    }

    public function users(Company $company)
    {
        $users = $company->users()->paginate(20);
        return response()->json($users);
    }

    public function evaluations(Company $company)
    {
        $evaluations = $company->evaluations()->with('domain')->paginate(20);
        return response()->json($evaluations);
    }
}