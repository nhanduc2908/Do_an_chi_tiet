<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Incident;
use App\Models\IncidentResponse;
use Illuminate\Http\Request;

class IncidentController extends Controller
{
    public function index(Request $request)
    {
        $query = Incident::with(['assignee', 'reporter']);
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        if ($request->has('severity')) {
            $query->where('severity', $request->severity);
        }
        if ($request->has('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }
        
        $incidents = $query->orderBy('detected_at', 'desc')->paginate(20);
        return response()->json($incidents);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'severity' => 'required|in:critical,high,medium,low',
            'type' => 'required|string',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $incident = Incident::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'severity' => $validated['severity'],
            'type' => $validated['type'],
            'detected_at' => now(),
            'assigned_to' => $validated['assigned_to'],
            'reported_by' => auth()->id(),
        ]);

        return response()->json($incident, 201);
    }

    public function show($id)
    {
        $incident = Incident::with(['assignee', 'reporter', 'responses.performer'])->findOrFail($id);
        return response()->json($incident);
    }

    public function update(Request $request, $id)
    {
        $incident = Incident::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'sometimes|string',
            'description' => 'sometimes|string',
            'severity' => 'sometimes|in:critical,high,medium,low',
            'status' => 'sometimes|in:open,investigating,contained,resolved,closed',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $incident->update($validated);
        return response()->json($incident);
    }

    public function addResponse(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|string',
            'description' => 'required|string',
        ]);

        $incident = Incident::findOrFail($id);
        
        $response = IncidentResponse::create([
            'incident_id' => $id,
            'action' => $request->action,
            'description' => $request->description,
            'performed_by' => auth()->id(),
        ]);

        return response()->json($response, 201);
    }

    public function resolve($id, Request $request)
    {
        $request->validate(['resolution' => 'required|string']);
        
        $incident = Incident::findOrFail($id);
        
        $incident->update([
            'status' => 'resolved',
            'resolved_at' => now(),
        ]);

        IncidentResponse::create([
            'incident_id' => $id,
            'action' => 'resolve',
            'description' => $request->resolution,
            'performed_by' => auth()->id(),
        ]);

        return response()->json(['message' => 'Incident resolved']);
    }

    public function statistics()
    {
        $stats = [
            'total' => Incident::count(),
            'open' => Incident::where('status', 'open')->count(),
            'investigating' => Incident::where('status', 'investigating')->count(),
            'resolved' => Incident::where('status', 'resolved')->count(),
            'by_severity' => [
                'critical' => Incident::where('severity', 'critical')->count(),
                'high' => Incident::where('severity', 'high')->count(),
                'medium' => Incident::where('severity', 'medium')->count(),
                'low' => Incident::where('severity', 'low')->count(),
            ],
            'avg_resolution_time' => Incident::whereNotNull('resolved_at')
                ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, detected_at, resolved_at)) as avg_hours')
                ->value('avg_hours'),
        ];
        
        return response()->json($stats);
    }
}