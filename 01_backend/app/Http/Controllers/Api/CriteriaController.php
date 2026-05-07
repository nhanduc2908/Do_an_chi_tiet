<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Criteria;
use App\Models\Domain;
use App\Models\Condition;
use Illuminate\Http\Request;

class CriteriaController extends Controller
{
    public function index(Request $request)
    {
        $query = Criteria::with('domain', 'conditions');
        
        if ($request->has('domain_id')) {
            $query->where('domain_id', $request->domain_id);
        }
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        if ($request->has('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }
        
        $criteria = $query->orderBy('domain_id')->orderBy('order')->paginate(50);
        return response()->json($criteria);
    }

    public function getByDomain($domainId)
    {
        $domain = Domain::findOrFail($domainId);
        $criteria = Criteria::where('domain_id', $domainId)
            ->with('conditions')
            ->orderBy('order')
            ->get();
        
        return response()->json([
            'domain' => $domain,
            'criteria' => $criteria,
            'total' => $criteria->count(),
        ]);
    }

    public function show($id)
    {
        $criteria = Criteria::with(['domain', 'conditions'])->findOrFail($id);
        return response()->json($criteria);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'domain_id' => 'required|exists:domains,id',
            'weight' => 'nullable|numeric|min:0|max:100',
            'priority' => 'nullable|in:high,medium,low',
        ]);

        $criteria = Criteria::create($validated);
        return response()->json($criteria, 201);
    }

    public function update(Request $request, $id)
    {
        $criteria = Criteria::findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|string',
            'weight' => 'nullable|numeric|min:0|max:100',
            'priority' => 'nullable|in:high,medium,low',
            'status' => 'nullable|in:active,inactive',
        ]);
        
        $criteria->update($validated);
        return response()->json($criteria);
    }

    public function destroy($id)
    {
        $criteria = Criteria::findOrFail($id);
        $criteria->delete();
        return response()->json(['message' => 'Criteria deleted']);
    }

    public function getConditions($criteriaId)
    {
        $conditions = Condition::where('criteria_id', $criteriaId)->orderBy('order')->get();
        return response()->json($conditions);
    }

    public function addCondition(Request $request, $criteriaId)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $condition = Condition::create([
            'criteria_id' => $criteriaId,
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return response()->json($condition, 201);
    }
}