<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeviceConnection;
use App\Models\CrossDeviceSession;
use App\Models\DeviceMessage;
use App\Services\WebSocket\MessageBroadcaster;
use Illuminate\Http\Request;

class CrossDeviceController extends Controller
{
    protected MessageBroadcaster $broadcaster;

    public function __construct(MessageBroadcaster $broadcaster)
    {
        $this->broadcaster = $broadcaster;
    }

    public function sessions(Request $request)
    {
        $sessions = CrossDeviceSession::where('user_id', $request->user()->id)
            ->where('active', true)
            ->get();
        return response()->json($sessions);
    }

    public function createSession(Request $request)
    {
        $session = CrossDeviceSession::create([
            'user_id' => $request->user()->id,
            'session_token' => bin2hex(random_bytes(32)),
            'connected_devices' => [$request->device_id],
            'active' => true,
            'started_at' => now(),
        ]);
        return response()->json($session, 201);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'to_device_id' => 'required|string',
            'message_type' => 'required|string',
            'payload' => 'required|array',
        ]);

        $message = DeviceMessage::create([
            'from_device_id' => $request->device_id,
            'to_device_id' => $request->to_device_id,
            'message_type' => $request->message_type,
            'payload' => $request->payload,
        ]);

        $this->broadcaster->sendToDevice($request->to_device_id, [
            'type' => 'cross_device',
            'message_id' => $message->id,
            'from_device_id' => $request->device_id,
            'message_type' => $request->message_type,
            'payload' => $request->payload,
        ]);

        return response()->json($message);
    }

    public function getMessages(Request $request)
    {
        $messages = DeviceMessage::where('to_device_id', $request->device_id)
            ->where('is_read', false)
            ->get();
        return response()->json($messages);
    }

    public function markAsRead($id, Request $request)
    {
        $message = DeviceMessage::findOrFail($id);
        $message->markAsRead();
        return response()->json(['message' => 'Marked as read']);
    }

    public function endSession($sessionId)
    {
        $session = CrossDeviceSession::findOrFail($sessionId);
        $session->update(['active' => false, 'ended_at' => now()]);
        return response()->json(['message' => 'Session ended']);
    }
}