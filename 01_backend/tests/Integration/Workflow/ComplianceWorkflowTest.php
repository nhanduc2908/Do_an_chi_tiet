<?php

namespace Tests\Integration\Workflow;

use Tests\TestCase;
use App\Models\User;
use App\Models\ComplianceCheck;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ComplianceWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_compliance_check_workflow()
    {
        $auditor = User::factory()->create(['role' => 'auditor']);
        $token = $auditor->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/compliance/check', [
                             'standard' => 'iso27001',
                         ]);
        
        $response->assertStatus(200);
        $this->assertDatabaseHas('compliance_checks', [
            'standard' => 'iso27001',
            'checked_by' => $auditor->id,
        ]);
    }

    public function test_remediation_workflow()
    {
        $auditor = User::factory()->create(['role' => 'auditor']);
        $finding = \App\Models\AuditFinding::create([
            'audit_id' => 1,
            'title' => 'Test Finding',
            'description' => 'Need remediation',
            'severity' => 'high',
        ]);
        
        $token = $auditor->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson("/api/compliance/findings/{$finding->id}/remediate", [
                             'action' => 'Fix vulnerability',
                             'due_date' => now()->addDays(30),
                             'assigned_to' => $auditor->id,
                         ]);
        
        $response->assertStatus(201);
        $this->assertDatabaseHas('remediation_plans', [
            'finding_id' => $finding->id,
            'status' => 'pending',
        ]);
    }
}