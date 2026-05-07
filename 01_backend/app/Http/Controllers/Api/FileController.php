<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\SharedFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function index(Request $request)
    {
        $files = File::where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return response()->json($files);
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:20480',
            'evaluation_id' => 'nullable|exists:evaluations,id',
        ]);

        $uploadedFile = $request->file('file');
        $path = $uploadedFile->store('uploads', 'public');

        $file = File::create([
            'name' => $uploadedFile->getClientOriginalName(),
            'original_name' => $uploadedFile->getClientOriginalName(),
            'path' => $path,
            'size' => $uploadedFile->getSize(),
            'mime_type' => $uploadedFile->getMimeType(),
            'user_id' => $request->user()->id,
            'evaluation_id' => $request->evaluation_id,
        ]);

        return response()->json($file, 201);
    }

    public function show($id)
    {
        $file = File::findOrFail($id);
        return response()->json($file);
    }

    public function destroy($id, Request $request)
    {
        $file = File::findOrFail($id);
        
        if ($file->user_id !== $request->user()->id && $request->user()->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        Storage::disk('public')->delete($file->path);
        $file->delete();

        return response()->json(['message' => 'File deleted']);
    }

    public function download($id)
    {
        $file = File::findOrFail($id);
        $file->increment('download_count');
        
        return Storage::disk('public')->download($file->path, $file->original_name);
    }

    public function share(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'email' => 'nullable|email',
            'permission' => 'required|in:view,download,edit',
            'expires_at' => 'nullable|date|after:now',
        ]);

        $file = File::findOrFail($id);
        
        if ($file->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Cannot share file you don\'t own'], 403);
        }

        $shareToken = bin2hex(random_bytes(32));

        $shared = SharedFile::create([
            'file_id' => $id,
            'shared_by' => $request->user()->id,
            'shared_with' => $request->user_id,
            'email' => $request->email,
            'permission' => $request->permission,
            'share_token' => $shareToken,
            'expires_at' => $request->expires_at,
        ]);

        return response()->json([
            'share' => $shared,
            'share_url' => url("/share/{$shareToken}"),
        ]);
    }
}