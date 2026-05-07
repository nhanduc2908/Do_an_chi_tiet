<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user()->load(['company', 'department', 'team']);
        return response()->json($user);
    }

    public function update(Request $request)
    {
        $user = $request->user();
        
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'phone' => 'nullable|string',
            'avatar' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $path;
        }

        $user->update($validated);
        return response()->json($user);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Current password is incorrect'], 400);
        }

        $user->update(['password' => Hash::make($request->new_password)]);

        return response()->json(['message' => 'Password changed successfully']);
    }

    public function devices(Request $request)
    {
        $devices = $request->user()->devices()->orderBy('last_login_at', 'desc')->get();
        return response()->json($devices);
    }

    public function revokeDevice($deviceId, Request $request)
    {
        $device = $request->user()->devices()->where('device_id', $deviceId)->firstOrFail();
        $device->delete();
        return response()->json(['message' => 'Device revoked']);
    }

    public function activityLog(Request $request)
    {
        $logs = $request->user()->loginHistories()
            ->orderBy('login_at', 'desc')
            ->paginate(20);
        return response()->json($logs);
    }
}