<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Training;
use App\Models\TrainingAssignment;
use Illuminate\Http\Request;

class TrainingController extends Controller
{
    public function index(Request $request)
    {
        $query = Training::where('status', 'active');
        
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }
        
        $trainings = $query->paginate(20);
        return response()->json($trainings);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'type' => 'required|string',
            'duration' => 'nullable|integer',
            'content_url' => 'nullable|url',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
        ]);

        $training = Training::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'type' => $validated['type'],
            'duration' => $validated['duration'],
            'content_url' => $validated['content_url'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'created_by' => auth()->id(),
        ]);

        return response()->json($training, 201);
    }

    public function show(Training $training)
    {
        $training->load('assignments.user');
        return response()->json($training);
    }

    public function update(Request $request, Training $training)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string',
            'description' => 'nullable|string',
            'duration' => 'nullable|integer',
            'content_url' => 'nullable|url',
            'status' => 'sometimes|in:active,inactive',
        ]);

        $training->update($validated);
        return response()->json($training);
    }

    public function assign(Request $request, Training $training)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        foreach ($request->user_ids as $userId) {
            TrainingAssignment::updateOrCreate(
                ['training_id' => $training->id, 'user_id' => $userId],
                ['assigned_by' => auth()->id(), 'status' => 'assigned']
            );
        }

        return response()->json(['message' => 'Training assigned']);
    }

    public function complete($id, Request $request)
    {
        $assignment = TrainingAssignment::findOrFail($id);
        
        $assignment->update([
            'status' => 'completed',
            'completed_at' => now(),
            'score' => $request->score,
            'feedback' => $request->feedback,
        ]);

        return response()->json(['message' => 'Training completed']);
    }

    public function myTrainings(Request $request)
    {
        $assignments = TrainingAssignment::where('user_id', $request->user()->id)
            ->with('training')
            ->get();
        
        return response()->json($assignments);
    }

    public function statistics()
    {
        $stats = [
            'total_trainings' => Training::count(),
            'total_assignments' => TrainingAssignment::count(),
            'completed' => TrainingAssignment::where('status', 'completed')->count(),
            'completion_rate' => round(TrainingAssignment::where('status', 'completed')->count() / max(TrainingAssignment::count(), 1) * 100, 2),
            'avg_score' => round(TrainingAssignment::avg('score'), 2),
            'by_type' => Training::selectRaw('type, COUNT(*) as count')
                ->groupBy('type')
                ->pluck('count', 'type'),
        ];
        
        return response()->json($stats);
    }
}