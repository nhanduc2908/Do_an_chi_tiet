<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Benchmark;
use App\Models\Domain;
use Illuminate\Http\Request;

class BenchmarkController extends Controller
{
    public function index(Request $request)
    {
        $benchmarks = Benchmark::with('domain')->get();
        return response()->json($benchmarks);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'domain_id' => 'required|exists:domains,id',
            'industry' => 'required|string',
            'score' => 'required|numeric|min:0|max:100',
            'year' => 'required|integer',
        ]);

        $benchmark = Benchmark::create($validated);
        return response()->json($benchmark, 201);
    }

    public function show(Benchmark $benchmark)
    {
        return response()->json($benchmark);
    }

    public function update(Request $request, Benchmark $benchmark)
    {
        $validated = $request->validate([
            'score' => 'sometimes|numeric|min:0|max:100',
            'year' => 'sometimes|integer',
        ]);

        $benchmark->update($validated);
        return response()->json($benchmark);
    }

    public function compare(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'industry' => 'required|string',
        ]);

        $companyScores = Domain::with(['evaluations' => function($q) use ($request) {
            $q->where('company_id', $request->company_id);
        }])->get()->map(fn($d) => [
            'domain' => $d->name,
            'score' => round($d->evaluations->avg('percentage'), 2),
        ]);

        $benchmarks = Benchmark::where('industry', $request->industry)
            ->with('domain')
            ->get()
            ->map(fn($b) => [
                'domain' => $b->domain->name,
                'benchmark' => $b->score,
            ]);

        $comparison = [];
        foreach ($companyScores as $company) {
            $bench = $benchmarks->firstWhere('domain', $company['domain']);
            $comparison[] = [
                'domain' => $company['domain'],
                'company_score' => $company['score'],
                'benchmark_score' => $bench['benchmark'] ?? 0,
                'gap' => round(($bench['benchmark'] ?? 0) - $company['score'], 2),
            ];
        }

        return response()->json($comparison);
    }

    public function industryAverage(Request $request)
    {
        $request->validate(['industry' => 'required|string']);
        
        $averages = Benchmark::where('industry', $request->industry)
            ->with('domain')
            ->get()
            ->map(fn($b) => [
                'domain' => $b->domain->name,
                'average' => $b->score,
            ]);

        return response()->json($averages);
    }
}