<?php

namespace Tests\Integration\Workflow;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BackupWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_backup_creation_workflow()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $token = $admin->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/backup', [
                             'type' => 'database',
                         ]);
        
        $response->assertStatus(201);
        $this->assertDatabaseHas('backup_histories', [
            'type' => 'database',
            'status' => 'completed',
        ]);
    }

    public function test_backup_restore_workflow()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $token = $admin->createToken('auth_token')->plainTextToken;
        
        // Create backup first
        $this->withHeader('Authorization', 'Bearer ' . $token)
             ->postJson('/api/backup', ['type' => 'database']);
        
        $backup = \App\Models\BackupHistory::latest()->first();
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/backup/restore', [
                             'filename' => $backup->filename,
                         ]);
        
        $response->assertStatus(200);
    }
}