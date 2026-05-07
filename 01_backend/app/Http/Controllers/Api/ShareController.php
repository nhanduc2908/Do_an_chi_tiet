<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SharedFile;
use App\Models\File;
use Illuminate\Http\Request;

class ShareController extends Controller
{
    public function sharedWithMe(Request $request)
    {
        $shared = SharedFile::where('shared_with', $request->user()->id)
            ->orWhere('email', $request->user()->email)
            ->with('file', 'sharedBy')
            ->where(function($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->get();
        
        return response()->json($shared);
    }

    public function sharedByMe(Request $request)
    {
        $shared = SharedFile::where('shared_by', $request->user()->id)
            ->with('file', 'sharedWith')
            ->get();
        
        return response()->json($shared);
    }

    public function revoke($token, Request $request)
    {
        $share = SharedFile::where('share_token', $token)
            ->where('shared_by', $request->user()->id)
            ->firstOrFail();
        
        $share->delete();
        return response()->json(['message' => 'Share revoked']);
    }

    public function access($token)
    {
        $share = SharedFile::where('share_token', $token)
            ->where(function($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->firstOrFail();
        
        $share->update(['accessed_at' => now()]);
        
        $file = File::find($share->file_id);
        
        return response()->json([
            'file' => $file,
            'permission' => $share->permission,
            'shared_by' => $share->sharedBy->name,
        ]);
    }

    public function download($token)
    {
        $share = SharedFile::where('share_token', $token)
            ->where(function($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->firstOrFail();
        
        if (!in_array($share->permission, ['download', 'edit'])) {
            return response()->json(['message' => 'No download permission'], 403);
        }
        
        $file = File::find($share->file_id);
        $file->increment('download_count');
        
        return response()->download(storage_path('app/public/' . $file->path));
    }
}