<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ComplianceCheck;
use App\Services\Compliance\ComplianceChecker;
use Illuminate\Http\Request;

class ComplianceController extends Controller
{
    protected ComplianceChecker $checker;

    public function __construct(ComplianceChecker $checker)
    {
        $this->checker = $checker;
    }

    public function index()
    {
        $standards = ['iso27001', 'soc2', 'gdpr', 'hipaa', 'pci_dss'];
        $results = [];
        foreach ($standards as $standard) {
            $results[$standard] = $this->checker->checkStandard($standard);
        }
        return response()->json($results);
    }

    public function check(Request $request)
    {
        $request->validate(['standard' => 'required|in:iso27001,soc2,gdpr,hipaa,pci_dss']);
        
        $result = $this->checker->runComplianceCheck($request->standard);
        
        ComplianceCheck::create([
            'standard' => $request->standard,
            'status' => $result['status'],
            'score' => $result['score'],
            'findings' => $result['findings'],
            'checked_by' => auth()->id(),
        ]);
        
        return response()->json($result);
    }

    public function report(Request $request)
    {
        $request->validate(['standard' => 'required|string']);
        $report = $this->checker->generateReport($request->standard);
        return response()->json($report);
    }

    public function history(Request $request)
    {
        $query = ComplianceCheck::with('checker');
        if ($request->has('standard')) {
            $query->where('standard', $request->standard);
        }
        $history = $query->orderBy('created_at', 'desc')->paginate(20);
        return response()->json($history);
    }
}