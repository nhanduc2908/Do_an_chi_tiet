<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    public function index()
    {
        $backups = $this->getBackupList();
        return response()->json(['backups' => $backups]);
    }

    public function store(Request $request)
    {
        $request->validate(['type' => 'required|in:full,database,files']);
        
        Artisan::call('db:backup', ['--filename' => $this->generateBackupName($request->type)]);
        
        return response()->json(['message' => 'Backup created'], 201);
    }

    public function download($filename)
    {
        $path = storage_path("app/backups/{$filename}");
        if (!file_exists($path)) {
            return response()->json(['message' => 'File not found'], 404);
        }
        return response()->download($path);
    }

    public function destroy($filename)
    {
        $path = storage_path("app/backups/{$filename}");
        if (file_exists($path)) {
            unlink($path);
        }
        return response()->json(['message' => 'Backup deleted']);
    }

    public function restore(Request $request)
    {
        $request->validate(['filename' => 'required|string']);
        Artisan::call('db:restore', ['--file' => $request->filename]);
        return response()->json(['message' => 'Database restored']);
    }

    protected function getBackupList(): array
    {
        $backupDir = storage_path('app/backups');
        if (!is_dir($backupDir)) return [];
        
        $files = glob($backupDir . '/*.sql*');
        $backups = [];
        foreach ($files as $file) {
            $backups[] = [
                'filename' => basename($file),
                'size' => round(filesize($file) / 1024 / 1024, 2) . ' MB',
                'created_at' => date('Y-m-d H:i:s', filemtime($file)),
            ];
        }
        usort($backups, fn($a, $b) => strtotime($b['created_at']) - strtotime($a['created_at']));
        return $backups;
    }

    protected function generateBackupName(string $type): string
    {
        return "backup_{$type}_" . now()->format('Ymd_His') . ".sql";
    }
}