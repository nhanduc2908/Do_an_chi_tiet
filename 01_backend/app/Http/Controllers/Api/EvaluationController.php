<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use App\Models\EvaluationDetail;
use App\Models\Criteria;
use App\Services\Evaluation\ScoringService;
use App\Services\AI\AIScoringEngine;
use App\Services\Audit\DataChangeTracker;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    protected ScoringService $scoringService;
    protected AIScoringEngine $aiEngine;

    public function __construct(ScoringService $scoringService, AIScoringEngine $aiEngine)
    {
        $this->scoringService = $scoringService;
        $this->aiEngine = $aiEngine;
    }

    public function index(Request $request)
    {
        $query = Evaluation::with(['user', 'domain', 'company']);
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        if ($request->has('domain_id')) {
            $query->where('domain_id', $request->domain_id);
        }
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        $evaluations = $query->orderBy('created_at', 'desc')->paginate(20);
        
        $stats = [
            'total' => Evaluation::count(),
            'draft' => Evaluation::where('status', 'draft')->count(),
            'submitted' => Evaluation::where('status', 'submitted')->count(),
            'approved' => Evaluation::where('status', 'approved')->count(),
            'rejected' => Evaluation::where('status', 'rejected')->count(),
            'avg_score' => round(Evaluation::avg('percentage'), 2),
        ];
        
        return response()->json(['evaluations' => $evaluations, 'statistics' => $stats]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'domain_id' => 'required|exists:domains,id',
            'notes' => 'nullable|string',
        ]);

        $evaluation = Evaluation::create([
            'title' => $validated['title'],
            'domain_id' => $validated['domain_id'],
            'user_id' => $request->user()->id,
            'status' => 'draft',
        ]);

        $criteriaList = Criteria::where('domain_id', $validated['domain_id'])->get();
        foreach ($criteriaList as $criteria) {
            EvaluationDetail::create([
                'evaluation_id' => $evaluation->id,
                'criteria_id' => $criteria->id,
                'score' => 0,
            ]);
        }

        return response()->json($evaluation, 201);
    }

    public function show($id)
    {
        $evaluation = Evaluation::with(['user', 'domain', 'details.criteria.conditions'])->findOrFail($id);
        
        if ($evaluation->percentage === null && $evaluation->status !== 'draft') {
            $this->scoringService->calculateScore($evaluation);
            $evaluation->refresh();
        }
        
        return response()->json($evaluation);
    }

    public function update(Request $request, $id)
    {
        $evaluation = Evaluation::findOrFail($id);
        
        if (!in_array($evaluation->status, ['draft', 'rejected'])) {
            return response()->json(['message' => 'Cannot edit this evaluation'], 422);
        }

        $oldData = $evaluation->toArray();
        $evaluation->update($request->only(['title', 'notes']));
        
        DataChangeTracker::record('evaluations', $evaluation->id, 'update', $oldData, $evaluation->toArray(), $request->user()->id);
        
        return response()->json($evaluation);
    }

    public function destroy($id, Request $request)
    {
        $evaluation = Evaluation::findOrFail($id);
        
        if ($evaluation->status !== 'draft') {
            return response()->json(['message' => 'Only draft evaluations can be deleted'], 422);
        }
        
        $evaluation->delete();
        return response()->json(['message' => 'Evaluation deleted']);
    }

    public function updateDetail(Request $request, $evaluationId, $detailId)
    {
        $request->validate([
            'score' => 'nullable|numeric|min:0|max:100',
            'notes' => 'nullable|string',
        ]);

        $evaluation = Evaluation::findOrFail($evaluationId);
        
        if (!in_array($evaluation->status, ['draft', 'rejected'])) {
            return response()->json(['message' => 'Cannot update scores'], 422);
        }

        $detail = EvaluationDetail::where('evaluation_id', $evaluationId)
            ->where('id', $detailId)
            ->firstOrFail();

        $oldData = $detail->toArray();
        $detail->update($request->only(['score', 'notes']));
        
        $this->scoringService->calculateScore($evaluation);
        
        DataChangeTracker::record('evaluation_details', $detailId, 'update', $oldData, $detail->toArray(), $request->user()->id);
        
        return response()->json($detail);
    }

    public function submit($id, Request $request)
    {
        $evaluation = Evaluation::findOrFail($id);
        
        if ($evaluation->status !== 'draft') {
            return response()->json(['message' => 'Evaluation already submitted'], 422);
        }

        $totalCriteria = EvaluationDetail::where('evaluation_id', $evaluation->id)->count();
        $scoredCriteria = EvaluationDetail::where('evaluation_id', $evaluation->id)
            ->where('score', '>', 0)
            ->count();

        if ($scoredCriteria < $totalCriteria) {
            return response()->json([
                'message' => 'Please complete all criteria before submission',
                'remaining' => $totalCriteria - $scoredCriteria,
            ], 422);
        }

        $this->scoringService->calculateScore($evaluation);
        
        $evaluation->update([
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        // AI Scoring tự động
        $this->aiEngine->autoScore($evaluation);

        return response()->json(['message' => 'Evaluation submitted', 'evaluation' => $evaluation]);
    }

    public function approve($id, Request $request)
    {
        $request->validate(['approver_note' => 'nullable|string']);
        
        $evaluation = Evaluation::findOrFail($id);
        
        if ($evaluation->status !== 'submitted') {
            return response()->json(['message' => 'Evaluation not submitted'], 422);
        }

        $evaluation->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => $request->user()->id,
            'approver_note' => $request->approver_note,
        ]);

        return response()->json(['message' => 'Evaluation approved']);
    }

    public function reject($id, Request $request)
    {
        $request->validate(['rejection_reason' => 'required|string']);
        
        $evaluation = Evaluation::findOrFail($id);
        
        if ($evaluation->status !== 'submitted') {
            return response()->json(['message' => 'Evaluation not submitted'], 422);
        }

        $evaluation->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejected_by' => $request->user()->id,
            'rejection_reason' => $request->rejection_reason,
        ]);

        return response()->json(['message' => 'Evaluation rejected']);
    }

    public function statistics(Request $request)
    {
        $query = Evaluation::query();
        
        if ($request->has('start_date')) {
            $query->where('created_at', '>=', $request->start_date);
        }
        if ($request->has('end_date')) {
            $query->where('created_at', '<=', $request->end_date);
        }

        $stats = [
            'overview' => [
                'total' => $query->count(),
                'avg_score' => round($query->avg('percentage'), 2),
                'max_score' => round($query->max('percentage'), 2),
                'min_score' => round($query->min('percentage'), 2),
            ],
            'by_status' => $query->selectRaw('status, count(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status'),
            'by_domain' => $query->selectRaw('domain_id, count(*) as total, avg(percentage) as avg_score')
                ->with('domain:id,name')
                ->groupBy('domain_id')
                ->get()
                ->map(fn($item) => [
                    'domain_name' => $item->domain->name,
                    'total' => $item->total,
                    'avg_score' => round($item->avg_score, 2),
                ]),
            'trend' => $query->selectRaw('DATE(created_at) as date, count(*) as total, avg(percentage) as avg_score')
                ->groupBy('date')
                ->orderBy('date', 'desc')
                ->limit(30)
                ->get(),
        ];

        return response()->json($stats);
    }

    public function dashboard(Request $request)
    {
        $user = $request->user();
        
        $data = match($user->role) {
            'admin' => [
                'total_evaluations' => Evaluation::count(),
                'pending_approvals' => Evaluation::where('status', 'submitted')->count(),
                'avg_score' => round(Evaluation::avg('percentage'), 2),
            ],
            'manager' => [
                'team_evaluations' => Evaluation::where('company_id', $user->company_id)->count(),
                'pending_approvals' => Evaluation::where('company_id', $user->company_id)
                    ->where('status', 'submitted')->count(),
                'team_avg_score' => round(Evaluation::where('company_id', $user->company_id)->avg('percentage'), 2),
            ],
            default => [
                'my_evaluations' => Evaluation::where('user_id', $user->id)->count(),
                'my_avg_score' => round(Evaluation::where('user_id', $user->id)->avg('percentage'), 2),
            ],
        };
        
        return response()->json($data);
    }
}
