<?php

namespace Tests\Integration\Workflow;

use Tests\TestCase;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuditWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_audit_log_workflow()
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;
        
        // Perform actions that generate audit logs
        $this->withHeader('Authorization', 'Bearer ' . $token)
             ->postJson('/api/evaluations', [
                 'title' => 'Audit Test',
                 'domain_id' => 1,
             ]);
        
        $this->assertDatabaseHas('audit_logs', [
            'event_type' => 'create',
            'user_id' => $user->id,
        ]);
    }

    public function test_audit_retention_workflow()
    {
        // Create old audit log
        AuditLog::create([
            'user_id' => 1,
            'event_type' => 'test',
            'description' => 'Old log',
            'created_at' => now()->subDays(400),
        ]);
        
        // Run cleanup
        \Artisan::call('audit:cleanup');
        
        $this->assertDatabaseMissing('audit_logs', [
            'description' => 'Old log',
        ]);
    }
}