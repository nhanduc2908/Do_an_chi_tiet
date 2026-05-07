<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\WebSocket\WebSocketManager;
use Illuminate\Http\Request;

class WebSocketController extends Controller
{
    protected WebSocketManager $wsManager;

    public function __construct(WebSocketManager $wsManager)
    {
        $this->wsManager = $wsManager;
    }

    public function auth(Request $request)
    {
        $request->validate([
            'device_id' => 'required|string',
        ]);

        $user = $request->user();
        $token = $this->wsManager->generateAuthToken($user->id, $request->device_id);

        return response()->json(['token' => $token]);
    }

    public function connections(Request $request)
    {
        $connections = $this->wsManager->getUserConnections($request->user()->id);
        return response()->json($connections);
    }

    public function disconnect($connectionId, Request $request)
    {
        $this->wsManager->disconnect($connectionId);
        return response()->json(['message' => 'Disconnected']);
    }

    public function broadcast(Request $request)
    {
        $request->validate([
            'channel' => 'required|string',
            'event' => 'required|string',
            'data' => 'required|array',
        ]);

        $this->wsManager->broadcast($request->channel, $request->event, $request->data);
        
        return response()->json(['message' => 'Broadcast sent']);
    }

    public function stats()
    {
        $stats = $this->wsManager->getStats();
        return response()->json($stats);
    }
}