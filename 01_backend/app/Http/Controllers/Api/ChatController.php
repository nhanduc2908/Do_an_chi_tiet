<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\ChatRoom;
use App\Events\MessageSent;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function rooms(Request $request)
    {
        $rooms = ChatRoom::whereHas('users', function($q) use ($request) {
            $q->where('user_id', $request->user()->id);
        })->get();
        
        return response()->json($rooms);
    }

    public function createRoom(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $room = ChatRoom::create([
            'name' => $request->name,
            'created_by' => auth()->id(),
        ]);

        $userIds = array_merge($request->user_ids, [auth()->id()]);
        $room->users()->attach($userIds);

        return response()->json($room, 201);
    }

    public function messages($roomId, Request $request)
    {
        $room = ChatRoom::findOrFail($roomId);
        
        if (!$room->users->contains($request->user()->id)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $messages = ChatMessage::where('chat_room_id', $roomId)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return response()->json($messages);
    }

    public function sendMessage(Request $request, $roomId)
    {
        $request->validate(['message' => 'required|string']);

        $room = ChatRoom::findOrFail($roomId);
        
        if (!$room->users->contains($request->user()->id)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $message = ChatMessage::create([
            'chat_room_id' => $roomId,
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return response()->json($message, 201);
    }

    public function markAsRead($roomId, Request $request)
    {
        $room = ChatRoom::findOrFail($roomId);
        
        ChatMessage::where('chat_room_id', $roomId)
            ->where('user_id', '!=', $request->user()->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['message' => 'Marked as read']);
    }

    public function unreadCount(Request $request)
    {
        $count = ChatMessage::whereHas('room.users', function($q) use ($request) {
            $q->where('user_id', $request->user()->id);
        })->where('user_id', '!=', $request->user()->id)
          ->whereNull('read_at')
          ->count();

        return response()->json(['unread_count' => $count]);
    }
}