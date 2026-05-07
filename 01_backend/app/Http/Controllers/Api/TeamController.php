<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        $query = Team::with(['department', 'lead']);
        
        if ($request->has('department_id')) {
            $query->where('department_id', $request->department_id);
        }
        
        $teams = $query->paginate(20);
        return response()->json($teams);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'code' => 'required|string|unique:teams',
            'department_id' => 'required|exists:departments,id',
            'lead_id' => 'nullable|exists:users,id',
        ]);

        $team = Team::create($validated);
        return response()->json($team, 201);
    }

    public function show(Team $team)
    {
        $team->load(['department', 'lead', 'members']);
        return response()->json($team);
    }

    public function update(Request $request, Team $team)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string',
            'lead_id' => 'nullable|exists:users,id',
        ]);

        $team->update($validated);
        return response()->json($team);
    }

    public function destroy(Team $team)
    {
        $team->members()->detach();
        $team->delete();
        return response()->json(['message' => 'Team deleted']);
    }

    public function members(Team $team)
    {
        $members = $team->members()->paginate(20);
        return response()->json($members);
    }

    public function addMember(Request $request, Team $team)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $team->members()->attach($request->user_id);
        return response()->json(['message' => 'Member added']);
    }

    public function removeMember(Team $team, $userId)
    {
        $team->members()->detach($userId);
        return response()->json(['message' => 'Member removed']);
    }

    public function evaluations(Team $team)
    {
        $evaluations = $team->members()->with('evaluations')->get()
            ->flatMap(fn($user) => $user->evaluations)
            ->sortByDesc('created_at');
        
        return response()->json($evaluations);
    }
}