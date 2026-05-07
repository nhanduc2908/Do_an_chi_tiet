<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Domain;
use Illuminate\Http\Request;

class DomainController extends Controller
{
    public function index(Request $request)
    {
        $domains = Domain::withCount('criteria', 'evaluations')->get();
        return response()->json($domains);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'name_en' => 'required|string',
            'code' => 'required|string|unique:domains',
            'description' => 'nullable|string',
            'weight' => 'nullable|numeric|min:0|max:100',
        ]);

        $domain = Domain::create($validated);
        return response()->json($domain, 201);
    }

    public function show(Domain $domain)
    {
        $domain->load(['criteria' => function($q) {
            $q->orderBy('order');
        }]);
        return response()->json($domain);
    }

    public function update(Request $request, Domain $domain)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string',
            'name_en' => 'sometimes|string',
            'description' => 'nullable|string',
            'weight' => 'nullable|numeric|min:0|max:100',
            'status' => 'sometimes|in:active,inactive',
        ]);

        $domain->update($validated);
        return response()->json($domain);
    }

    public function destroy(Domain $domain)
    {
        if ($domain->criteria()->count() > 0) {
            return response()->json(['message' => 'Cannot delete domain with existing criteria'], 422);
        }
        
        $domain->delete();
        return response()->json(['message' => 'Domain deleted']);
    }

    public function statistics(Domain $domain)
    {
        $stats = [
            'total_criteria' => $domain->criteria()->count(),
            'active_criteria' => $domain->criteria()->where('status', 'active')->count(),
            'total_evaluations' => $domain->evaluations()->count(),
            'avg_score' => round($domain->evaluations()->avg('percentage'), 2),
        ];
        
        return response()->json($stats);
    }
}