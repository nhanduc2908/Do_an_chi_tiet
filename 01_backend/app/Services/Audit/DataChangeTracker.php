<?php

namespace App\Services\Audit;

use App\Models\DataChangeLog;
use Illuminate\Support\Facades\Auth;

class DataChangeTracker
{
    public static function record(string $tableName, int $recordId, string $action, ?array $oldData, ?array $newData, ?int $userId = null): void
    {
        $changes = [];
        if ($oldData && $newData) {
            foreach ($newData as $key => $value) {
                if (isset($oldData[$key]) && $oldData[$key] != $value) {
                    $changes[$key] = ['old' => $oldData[$key], 'new' => $value];
                }
            }
        }

        DataChangeLog::create([
            'user_id' => $userId ?? Auth::id(),
            'table_name' => $tableName,
            'record_id' => $recordId,
            'action' => $action,
            'old_data' => $oldData,
            'new_data' => $newData,
            'changes' => $changes,
            'ip_address' => request()->ip(),
        ]);
    }
}