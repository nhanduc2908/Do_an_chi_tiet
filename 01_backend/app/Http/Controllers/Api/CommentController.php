<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        $request->validate(['evaluation_id' => 'required|exists:evaluations,id']);
        
        $comments = Comment::where('evaluation_id', $request->evaluation_id)
            ->with('user')
            ->whereNull('parent_id')
            ->with('replies.user')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($comments);
    }

    public function store(Request $request)
    {
        $request->validate([
            'evaluation_id' => 'required|exists:evaluations,id',
            'content' => 'required|string',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $comment = Comment::create([
            'user_id' => auth()->id(),
            'evaluation_id' => $request->evaluation_id,
            'parent_id' => $request->parent_id,
            'content' => $request->content,
        ]);

        return response()->json($comment->load('user'), 201);
    }

    public function update(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);
        
        if ($comment->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $request->validate(['content' => 'required|string']);
        
        $comment->update([
            'content' => $request->content,
            'is_edited' => true,
        ]);

        return response()->json($comment);
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        
        if ($comment->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $comment->delete();
        return response()->json(['message' => 'Comment deleted']);
    }
}