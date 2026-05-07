<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ScanResult;
use App\Models\Vulnerability;
use App\Services\Scanner\CodeScanner;
use App\Services\Scanner\WebScanner;
use App\Services\Scanner\DatabaseScanner;
use Illuminate\Http\Request;

class ScannerController extends Controller
{
    protected CodeScanner $codeScanner;
    protected WebScanner $webScanner;
    protected DatabaseScanner $dbScanner;

    public function __construct(
        CodeScanner $codeScanner,
        WebScanner $webScanner,
        DatabaseScanner $dbScanner
    ) {
        $this->codeScanner = $codeScanner;
        $this->webScanner = $webScanner;
        $this->dbScanner = $dbScanner;
    }

    public function scanCode(Request $request)
    {
        $request->validate([
            'repository_url' => 'required|url',
            'branch' => 'nullable|string',
        ]);

        $scan = ScanResult::create([
            'type' => 'code',
            'target' => $request->repository_url,
            'user_id' => auth()->id(),
            'status' => 'running',
            'started_at' => now(),
        ]);

        try {
            $result = $this->codeScanner->scan($request->repository_url, $request->branch);
            
            $scan->update([
                'status' => 'completed',
                'result' => $result,
                'vulnerabilities' => $result['vulnerabilities'] ?? [],
                'completed_at' => now(),
                'summary' => $result['summary'] ?? [],
            ]);

            foreach ($result['vulnerabilities'] ?? [] as $vuln) {
                Vulnerability::create([
                    'scan_result_id' => $scan->id,
                    'cve_id' => $vuln['cve_id'] ?? null,
                    'title' => $vuln['title'],
                    'severity' => $vuln['severity'],
                    'description' => $vuln['description'],
                    'recommendation' => $vuln['recommendation'],
                ]);
            }

        } catch (\Exception $e) {
            $scan->update(['status' => 'failed']);
            return response()->json(['message' => $e->getMessage()], 500);
        }

        return response()->json($scan);
    }

    public function scanWeb(Request $request)
    {
        $request->validate([
            'url' => 'required|url',
            'depth' => 'nullable|integer|min:1|max:10',
        ]);

        $scan = ScanResult::create([
            'type' => 'web',
            'target' => $request->url,
            'user_id' => auth()->id(),
            'status' => 'running',
            'started_at' => now(),
        ]);

        try {
            $result = $this->webScanner->scan($request->url, $request->depth ?? 3);
            
            $scan->update([
                'status' => 'completed',
                'result' => $result,
                'vulnerabilities' => $result['vulnerabilities'] ?? [],
                'completed_at' => now(),
                'summary' => $result['summary'] ?? [],
            ]);

        } catch (\Exception $e) {
            $scan->update(['status' => 'failed']);
            return response()->json(['message' => $e->getMessage()], 500);
        }

        return response()->json($scan);
    }

    public function scanDatabase(Request $request)
    {
        $request->validate([
            'database_name' => 'required|string',
        ]);

        $scan = ScanResult::create([
            'type' => 'database',
            'target' => $request->database_name,
            'user_id' => auth()->id(),
            'status' => 'running',
            'started_at' => now(),
        ]);

        try {
            $result = $this->dbScanner->scan($request->database_name);
            
            $scan->update([
                'status' => 'completed',
                'result' => $result,
                'vulnerabilities' => $result['vulnerabilities'] ?? [],
                'completed_at' => now(),
                'summary' => $result['summary'] ?? [],
            ]);

        } catch (\Exception $e) {
            $scan->update(['status' => 'failed']);
            return response()->json(['message' => $e->getMessage()], 500);
        }

        return response()->json($scan);
    }

    public function scanPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $result = $this->passwordScanner->checkStrength($request->password);
        
        return response()->json($result);
    }

    public function getScans(Request $request)
    {
        $scans = ScanResult::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return response()->json($scans);
    }

    public function getScan($id)
    {
        $scan = ScanResult::with('vulnerabilitiesList')->findOrFail($id);
        return response()->json($scan);
    }

    public function getVulnerabilities(Request $request)
    {
        $query = Vulnerability::with('scanResult');
        
        if ($request->has('severity')) {
            $query->where('severity', $request->severity);
        }
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        $vulnerabilities = $query->orderBy('created_at', 'desc')->paginate(20);
        return response()->json($vulnerabilities);
    }

    public function fixVulnerability($id, Request $request)
    {
        $vulnerability = Vulnerability::findOrFail($id);
        
        $vulnerability->update([
            'status' => 'fixed',
            'fixed_at' => now(),
            'fixed_by' => auth()->id(),
        ]);

        return response()->json(['message' => 'Vulnerability marked as fixed']);
    }
}