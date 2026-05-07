<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rank;
use Illuminate\Http\Request;

class RankController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    public function index()
    {
        $ranks = Rank::orderBy('level', 'asc')->get();
        return response()->json($ranks);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'code' => 'required|string|unique:ranks',
            'level' => 'required|integer|min:1|max:10',
        ]);

        $rank = Rank::create($validated);
        return response()->json($rank, 201);
    }

    public function update(Request $request, Rank $rank)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string',
            'level' => 'sometimes|integer|min:1|max:10',
        ]);

        $rank->update($validated);
        return response()->json($rank);
    }

    public function destroy(Rank $rank)
    {
        if ($rank->users()->count() > 0) {
            return response()->json(['message' => 'Cannot delete rank with assigned users'], 422);
        }
        
        $rank->delete();
        return response()->json(['message' => 'Rank deleted']);
    }
}