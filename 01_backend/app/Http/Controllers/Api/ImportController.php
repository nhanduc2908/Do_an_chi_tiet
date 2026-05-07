<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Evaluation\ImportService;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    protected ImportService $importService;

    public function __construct(ImportService $importService)
    {
        $this->importService = $importService;
    }

    public function importEvaluations(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv',
            'domain_id' => 'required|exists:domains,id',
        ]);

        $result = $this->importService->import(
            $request->file('file'),
            $request->domain_id,
            auth()->id()
        );

        return response()->json([
            'message' => 'Import completed',
            'success' => $result['success'],
            'failed' => $result['failed'],
            'errors' => $result['errors'] ?? [],
        ]);
    }

    public function importCriteria(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv',
            'domain_id' => 'required|exists:domains,id',
        ]);

        $result = $this->importService->importCriteria(
            $request->file('file'),
            $request->domain_id
        );

        return response()->json([
            'message' => 'Import completed',
            'imported' => $result['imported'],
        ]);
    }

    public function template($type)
    {
        $types = ['evaluations', 'criteria'];
        
        if (!in_array($type, $types)) {
            return response()->json(['message' => 'Invalid template type'], 400);
        }

        $template = $this->importService->getTemplate($type);
        
        return response()->download($template)->deleteFileAfterSend(true);
    }
}