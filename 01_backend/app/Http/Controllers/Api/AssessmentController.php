<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Criteria;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Assessment::with(['user', 'domain']);
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        if ($request->has('domain_id')) {
            $query->where('domain_id', $request->domain_id);
        }
        
        $assessments = $query->orderBy('created_at', 'desc')->paginate(20);
        return response()->json($assessments);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'domain_id' => 'required|exists:domains,id',
            'description' => 'nullable|string',
        ]);

        $assessment = Assessment::create([
            'title' => $validated['title'],
            'domain_id' => $validated['domain_id'],
            'user_id' => auth()->id(),
            'status' => 'draft',
        ]);

        return response()->json($assessment, 201);
    }

    public function show($id)
    {
        $assessment = Assessment::with(['domain', 'details.criteria'])->findOrFail($id);
        return response()->json($assessment);
    }

    public function update(Request $request, $id)
    {
        $assessment = Assessment::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'sometimes|string',
            'description' => 'nullable|string',
            'status' => 'sometimes|in:draft,submitted,reviewed',
        ]);

        $assessment->update($validated);
        return response()->json($assessment);
    }

    public function submit($id)
    {
        $assessment = Assessment::findOrFail($id);
        $assessment->update(['status' => 'submitted', 'submitted_at' => now()]);
        return response()->json(['message' => 'Assessment submitted']);
    }

    public function review($id, Request $request)
    {
        $request->validate([
            'score' => 'required|numeric|min:0|max:100',
            'feedback' => 'nullable|string',
        ]);

        $assessment = Assessment::findOrFail($id);
        $assessment->update([
            'status' => 'reviewed',
            'score' => $request->score,
            'feedback' => $request->feedback,
            'reviewed_at' => now(),
            'reviewed_by' => auth()->id(),
        ]);

        return response()->json(['message' => 'Assessment reviewed']);
    }

    public function destroy($id)
    {
        $assessment = Assessment::findOrFail($id);
        $assessment->delete();
        return response()->json(['message' => 'Assessment deleted']);
    }
}