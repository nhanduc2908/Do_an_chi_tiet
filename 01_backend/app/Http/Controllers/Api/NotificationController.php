<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $query = Notification::where('user_id', $request->user()->id);
        
        if ($request->has('unread')) {
            $query->where('is_read', false);
        }
        
        $notifications = $query->orderBy('created_at', 'desc')->paginate(20);
        return response()->json($notifications);
    }

    public function unreadCount(Request $request)
    {
        $count = Notification::where('user_id', $request->user()->id)
            ->where('is_read', false)
            ->count();
        return response()->json(['unread_count' => $count]);
    }

    public function markAsRead($id, Request $request)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();
        
        $notification->markAsRead();
        return response()->json(['message' => 'Marked as read']);
    }

    public function markAllAsRead(Request $request)
    {
        Notification::where('user_id', $request->user()->id)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);
        
        return response()->json(['message' => 'All notifications marked as read']);
    }

    public function destroy($id, Request $request)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();
        
        $notification->delete();
        return response()->json(['message' => 'Notification deleted']);
    }

    public function send(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string',
            'content' => 'required|string',
            'priority' => 'nullable|in:low,medium,high,critical',
        ]);

        $notification = Notification::create([
            'user_id' => $request->user_id,
            'type' => 'manual',
            'title' => $request->title,
            'content' => $request->content,
            'priority' => $request->priority ?? 'medium',
        ]);

        return response()->json($notification, 201);
    }
}