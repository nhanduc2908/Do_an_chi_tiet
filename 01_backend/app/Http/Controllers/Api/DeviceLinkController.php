<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeviceConnection;
use App\Services\WebSocket\DeviceConnectionManager;
use Illuminate\Http\Request;

class DeviceLinkController extends Controller
{
    protected DeviceConnectionManager $connectionManager;

    public function __construct(DeviceConnectionManager $connectionManager)
    {
        $this->connectionManager = $connectionManager;
    }

    public function generateCode(Request $request)
    {
        $code = strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
        
        cache()->put("device_link_code_{$code}", [
            'user_id' => $request->user()->id,
            'expires_at' => now()->addMinutes(5),
        ], 300);

        return response()->json(['code' => $code, 'expires_in' => 300]);
    }

    public function linkDevice(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:8',
            'device_id' => 'required|string',
            'device_name' => 'required|string',
            'device_type' => 'required|in:android,ios,web,desktop',
        ]);

        $linkData = cache()->get("device_link_code_{$request->code}");

        if (!$linkData || $linkData['expires_at'] < now()) {
            return response()->json(['message' => 'Invalid or expired code'], 400);
        }

        $device = DeviceConnection::create([
            'user_id' => $linkData['user_id'],
            'device_id' => $request->device_id,
            'device_name' => $request->device_name,
            'device_type' => $request->device_type,
            'is_connected' => true,
            'last_connected_at' => now(),
        ]);

        cache()->forget("device_link_code_{$request->code}");

        return response()->json(['message' => 'Device linked successfully', 'device' => $device]);
    }

    public function unlinkDevice($deviceId, Request $request)
    {
        $device = DeviceConnection::where('device_id', $deviceId)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $device->delete();
        
        $this->connectionManager->unregister($deviceId);

        return response()->json(['message' => 'Device unlinked']);
    }

    public function linkedDevices(Request $request)
    {
        $devices = DeviceConnection::where('user_id', $request->user()->id)
            ->orderBy('last_connected_at', 'desc')
            ->get();

        return response()->json($devices);
    }
}