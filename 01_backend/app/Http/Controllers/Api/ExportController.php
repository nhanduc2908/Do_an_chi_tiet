<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use App\Services\Evaluation\ExportService;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    protected ExportService $exportService;

    public function __construct(ExportService $exportService)
    {
        $this->exportService = $exportService;
    }

    public function exportEvaluation($id, Request $request)
    {
        $request->validate(['format' => 'required|in:pdf,excel,csv']);
        
        $evaluation = Evaluation::findOrFail($id);
        $file = $this->exportService->export($evaluation, $request->format);
        
        return response()->download($file)->deleteFileAfterSend(true);
    }

    public function exportAllEvaluations(Request $request)
    {
        $request->validate(['format' => 'required|in:pdf,excel,csv']);
        
        $evaluations = Evaluation::where('user_id', auth()->id())->get();
        $file = $this->exportService->exportMultiple($evaluations, $request->format);
        
        return response()->download($file)->deleteFileAfterSend(true);
    }

    public function exportCriteria(Request $request)
    {
        $request->validate(['format' => 'required|in:excel,csv']);
        
        $file = $this->exportService->exportCriteria($request->format);
        return response()->download($file)->deleteFileAfterSend(true);
    }

    public function exportReport($reportId)
    {
        $report = \App\Models\Report::findOrFail($reportId);
        
        if (!file_exists($report->file_path)) {
            return response()->json(['message' => 'File not found'], 404);
        }
        
        return response()->download($report->file_path);
    }
}