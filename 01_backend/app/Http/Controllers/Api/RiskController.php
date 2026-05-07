<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RiskAssessment;
use App\Models\RiskTreatment;
use App\Models\Asset;
use Illuminate\Http\Request;

class RiskController extends Controller
{
    public function index(Request $request)
    {
        $query = RiskAssessment::with(['asset', 'owner', 'assessor']);
        
        if ($request->has('asset_id')) {
            $query->where('asset_id', $request->asset_id);
        }
        if ($request->has('risk_level')) {
            $query->where('risk_level', $request->risk_level);
        }
        
        $risks = $query->orderBy('risk_score', 'desc')->paginate(20);
        return response()->json($risks);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'threat' => 'required|string',
            'vulnerability' => 'required|string',
            'likelihood' => 'required|integer|min:1|max:5',
            'impact' => 'required|integer|min:1|max:5',
            'mitigation' => 'nullable|string',
            'owner_id' => 'nullable|exists:users,id',
        ]);

        $riskScore = $validated['likelihood'] * $validated['impact'];
        $riskLevel = $this->getRiskLevel($riskScore);

        $risk = RiskAssessment::create([
            'asset_id' => $validated['asset_id'],
            'threat' => $validated['threat'],
            'vulnerability' => $validated['vulnerability'],
            'likelihood' => $validated['likelihood'],
            'impact' => $validated['impact'],
            'risk_score' => $riskScore,
            'risk_level' => $riskLevel,
            'mitigation' => $validated['mitigation'],
            'owner_id' => $validated['owner_id'],
            'assessed_by' => auth()->id(),
            'assessed_at' => now(),
        ]);

        return response()->json($risk, 201);
    }

    public function show($id)
    {
        $risk = RiskAssessment::with(['asset', 'owner', 'assessor', 'treatments'])->findOrFail($id);
        return response()->json($risk);
    }

    public function update(Request $request, $id)
    {
        $risk = RiskAssessment::findOrFail($id);
        
        $validated = $request->validate([
            'likelihood' => 'sometimes|integer|min:1|max:5',
            'impact' => 'sometimes|integer|min:1|max:5',
            'mitigation' => 'nullable|string',
            'owner_id' => 'nullable|exists:users,id',
        ]);

        if (isset($validated['likelihood']) || isset($validated['impact'])) {
            $likelihood = $validated['likelihood'] ?? $risk->likelihood;
            $impact = $validated['impact'] ?? $risk->impact;
            $validated['risk_score'] = $likelihood * $impact;
            $validated['risk_level'] = $this->getRiskLevel($validated['risk_score']);
        }

        $risk->update($validated);
        return response()->json($risk);
    }

    public function addTreatment(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|string',
            'description' => 'required|string',
            'due_date' => 'required|date',
            'assigned_to' => 'required|exists:users,id',
        ]);

        $risk = RiskAssessment::findOrFail($id);
        
        $treatment = RiskTreatment::create([
            'risk_assessment_id' => $id,
            'action' => $request->action,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'assigned_to' => $request->assigned_to,
            'status' => 'pending',
        ]);

        return response()->json($treatment, 201);
    }

    public function updateTreatment(Request $request, $treatmentId)
    {
        $treatment = RiskTreatment::findOrFail($treatmentId);
        
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'completed_at' => 'required_if:status,completed|nullable|date',
        ]);

        $treatment->update([
            'status' => $request->status,
            'completed_at' => $request->completed_at,
        ]);

        return response()->json($treatment);
    }

    public function matrix()
    {
        $matrix = [];
        for ($i = 1; $i <= 5; $i++) {
            for ($j = 1; $j <= 5; $j++) {
                $score = $i * $j;
                $matrix[$i][$j] = [
                    'score' => $score,
                    'level' => $this->getRiskLevel($score),
                    'likelihood' => $i,
                    'impact' => $j,
                ];
            }
        }
        
        return response()->json($matrix);
    }

    public function heatmap()
    {
        $risks = RiskAssessment::selectRaw('likelihood, impact, COUNT(*) as count')
            ->groupBy('likelihood', 'impact')
            ->get();
        
        $heatmap = [];
        foreach ($risks as $risk) {
            $heatmap[$risk->likelihood][$risk->impact] = $risk->count;
        }
        
        return response()->json($heatmap);
    }

    public function dashboard()
    {
        $stats = [
            'total_risks' => RiskAssessment::count(),
            'open_risks' => RiskAssessment::whereHas('treatments', function($q) {
                $q->where('status', '!=', 'completed');
            })->orWhereDoesntHave('treatments')->count(),
            'by_level' => [
                'critical' => RiskAssessment::where('risk_level', 'critical')->count(),
                'high' => RiskAssessment::where('risk_level', 'high')->count(),
                'medium' => RiskAssessment::where('risk_level', 'medium')->count(),
                'low' => RiskAssessment::where('risk_level', 'low')->count(),
            ],
            'top_assets' => RiskAssessment::select('asset_id', \DB::raw('AVG(risk_score) as avg_score'))
                ->with('asset')
                ->groupBy('asset_id')
                ->orderBy('avg_score', 'desc')
                ->limit(5)
                ->get(),
        ];
        
        return response()->json($stats);
    }

    protected function getRiskLevel($score)
    {
        if ($score >= 20) return 'critical';
        if ($score >= 12) return 'high';
        if ($score >= 6) return 'medium';
        return 'low';
    }
}