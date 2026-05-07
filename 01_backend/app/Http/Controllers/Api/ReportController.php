<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Evaluation;
use App\Services\Evaluation\ReportGenerator;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected ReportGenerator $reportGenerator;

    public function __construct(ReportGenerator $reportGenerator)
    {
        $this->reportGenerator = $reportGenerator;
    }

    public function index(Request $request)
    {
        $query = Report::where('user_id', $request->user()->id)
            ->orWhereIn('user_id', function($q) use ($request) {
                $q->select('id')->from('users')->where('company_id', $request->user()->company_id);
            });
        
        $reports = $query->orderBy('generated_at', 'desc')->paginate(20);
        return response()->json($reports);
    }

    public function generate(Request $request)
    {
        $request->validate([
            'evaluation_id' => 'required|exists:evaluations,id',
            'format' => 'required|in:pdf,excel,csv',
        ]);

        $evaluation = Evaluation::findOrFail($request->evaluation_id);
        
        $filePath = $this->reportGenerator->generate($evaluation, $request->format);
        
        $report = Report::create([
            'evaluation_id' => $evaluation->id,
            'user_id' => $request->user()->id,
            'title' => $evaluation->title,
            'type' => 'evaluation',
            'format' => $request->format,
            'file_path' => $filePath,
            'status' => 'completed',
            'generated_at' => now(),
        ]);

        return response()->json($report, 201);
    }

    public function show($id)
    {
        $report = Report::findOrFail($id);
        return response()->json($report);
    }

    public function download($id)
    {
        $report = Report::findOrFail($id);
        
        if (!file_exists($report->file_path)) {
            return response()->json(['message' => 'File not found'], 404);
        }
        
        return response()->download($report->file_path);
    }

    public function destroy($id)
    {
        $report = Report::findOrFail($id);
        
        if (file_exists($report->file_path)) {
            unlink($report->file_path);
        }
        
        $report->delete();
        return response()->json(['message' => 'Report deleted']);
    }

    public function schedule(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'cron' => 'required|string',
            'recipients' => 'required|array',
            'format' => 'required|in:pdf,excel,csv',
        ]);

        // Logic schedule report
        return response()->json(['message' => 'Report scheduled'], 201);
    }
}