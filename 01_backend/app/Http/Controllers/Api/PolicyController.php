<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Policy;
use App\Models\PolicyAcknowledgment;
use Illuminate\Http\Request;

class PolicyController extends Controller
{
    public function index(Request $request)
    {
        $query = Policy::where('is_active', true);
        
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }
        
        $policies = $query->orderBy('created_at', 'desc')->get();
        return response()->json($policies);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:policies',
            'content' => 'required|string',
            'category' => 'required|string',
            'version' => 'required|string',
            'effective_date' => 'required|date',
        ]);

        $policy = Policy::create([
            'name' => $validated['name'],
            'content' => $validated['content'],
            'category' => $validated['category'],
            'version' => $validated['version'],
            'effective_date' => $validated['effective_date'],
            'created_by' => auth()->id(),
        ]);

        return response()->json($policy, 201);
    }

    public function show(Policy $policy)
    {
        return response()->json($policy);
    }

    public function update(Request $request, Policy $policy)
    {
        $validated = $request->validate([
            'content' => 'sometimes|string',
            'version' => 'sometimes|string',
            'is_active' => 'sometimes|boolean',
        ]);

        $policy->update($validated);
        return response()->json($policy);
    }

    public function acknowledge($id, Request $request)
    {
        $policy = Policy::findOrFail($id);
        
        PolicyAcknowledgment::create([
            'policy_id' => $id,
            'user_id' => $request->user()->id,
            'ip_address' => $request->ip(),
        ]);

        return response()->json(['message' => 'Policy acknowledged']);
    }

    public function acknowledgments(Policy $policy)
    {
        $acknowledgments = $policy->acknowledgments()->with('user')->get();
        return response()->json($acknowledgments);
    }
}