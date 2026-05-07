<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\VendorAssessment;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        $query = Vendor::query();
        
        if ($request->has('risk_level')) {
            $query->where('risk_level', $request->risk_level);
        }
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        $vendors = $query->paginate(20);
        return response()->json($vendors);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:vendors',
            'contact_name' => 'nullable|string',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string',
            'website' => 'nullable|url',
            'risk_level' => 'nullable|in:low,medium,high,critical',
        ]);

        $vendor = Vendor::create($validated);
        return response()->json($vendor, 201);
    }

    public function show(Vendor $vendor)
    {
        $vendor->load(['assessments.assessor', 'contracts']);
        return response()->json($vendor);
    }

    public function update(Request $request, Vendor $vendor)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|unique:vendors,name,' . $vendor->id,
            'contact_name' => 'nullable|string',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string',
            'website' => 'nullable|url',
            'risk_level' => 'nullable|in:low,medium,high,critical',
            'status' => 'nullable|in:active,inactive',
        ]);

        $vendor->update($validated);
        return response()->json($vendor);
    }

    public function destroy(Vendor $vendor)
    {
        $vendor->delete();
        return response()->json(['message' => 'Vendor deleted']);
    }

    public function assess(Request $request, Vendor $vendor)
    {
        $request->validate([
            'score' => 'required|numeric|min:0|max:100',
            'findings' => 'nullable|array',
            'recommendations' => 'nullable|string',
        ]);

        $assessment = VendorAssessment::create([
            'vendor_id' => $vendor->id,
            'assessor_id' => auth()->id(),
            'score' => $request->score,
            'findings' => $request->findings,
            'recommendations' => $request->recommendations,
            'assessed_at' => now(),
            'next_assessment_date' => now()->addMonths(6),
        ]);

        // Update vendor risk level based on score
        $riskLevel = $this->getRiskLevelFromScore($request->score);
        $vendor->update(['risk_level' => $riskLevel]);

        return response()->json($assessment, 201);
    }

    public function assessments(Vendor $vendor)
    {
        $assessments = $vendor->assessments()->with('assessor')->orderBy('assessed_at', 'desc')->get();
        return response()->json($assessments);
    }

    protected function getRiskLevelFromScore($score)
    {
        if ($score >= 80) return 'low';
        if ($score >= 60) return 'medium';
        if ($score >= 40) return 'high';
        return 'critical';
    }
}