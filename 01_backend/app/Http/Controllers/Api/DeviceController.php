<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeviceConnection;
use App\Models\ScreenLog;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function index(Request $request)
    {
        $devices = DeviceConnection::where('user_id', $request->user()->id)
            ->orderBy('last_connected_at', 'desc')
            ->get();
        return response()->json($devices);
    }

    public function register(Request $request)
    {
        $request->validate([
            'device_id' => 'required|string|unique:device_connections',
            'device_name' => 'required|string',
            'device_type' => 'required|in:android,ios,web,desktop',
            'push_token' => 'nullable|string',
        ]);

        $device = DeviceConnection::create([
            'user_id' => $request->user()->id,
            'device_id' => $request->device_id,
            'device_name' => $request->device_name,
            'device_type' => $request->device_type,
            'push_token' => $request->push_token,
            'is_connected' => true,
            'last_connected_at' => now(),
        ]);

        return response()->json($device, 201);
    }

    public function update(Request $request, $deviceId)
    {
        $device = DeviceConnection::where('device_id', $deviceId)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $device->update([
            'push_token' => $request->push_token ?? $device->push_token,
            'last_connected_at' => now(),
        ]);

        return response()->json($device);
    }

    public function unregister($deviceId, Request $request)
    {
        $device = DeviceConnection::where('device_id', $deviceId)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $device->delete();
        return response()->json(['message' => 'Device unregistered']);
    }

    public function heartbeat($deviceId, Request $request)
    {
        $device = DeviceConnection::where('device_id', $deviceId)
            ->where('user_id', $request->user()->id)
            ->first();

        if ($device) {
            $device->update(['last_connected_at' => now()]);
        }

        return response()->json(['status' => 'ok']);
    }

    public function screenLog(Request $request)
    {
        $request->validate([
            'device_id' => 'required|string',
            'screen_width' => 'required|integer',
            'screen_height' => 'required|integer',
            'orientation' => 'required|in:portrait,landscape',
        ]);

        ScreenLog::create([
            'user_id' => $request->user()->id,
            'device_id' => $request->device_id,
            'screen_width' => $request->screen_width,
            'screen_height' => $request->screen_height,
            'orientation' => $request->orientation,
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json(['message' => 'Screen log saved']);
    }
}