<?php

namespace Tests\Integration\Workflow;

use Tests\TestCase;
use App\Models\User;
use App\Models\Evaluation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApprovalWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_approval_workflow_with_multiple_approvers()
    {
        $user = User::factory()->create();
        $manager = User::factory()->create(['role' => 'manager']);
        $ciso = User::factory()->create(['role' => 'ciso']);
        
        $evaluation = Evaluation::factory()->create([
            'user_id' => $user->id,
            'status' => 'submitted',
        ]);
        
        $userToken = $user->createToken('auth_token')->plainTextToken;
        $managerToken = $manager->createToken('auth_token')->plainTextToken;
        $cisoToken = $ciso->createToken('auth_token')->plainTextToken;
        
        // Manager approves first
        $this->withHeader('Authorization', 'Bearer ' . $managerToken)
             ->postJson("/api/evaluations/{$evaluation->id}/approve", [
                 'approver_note' => 'Manager approved',
             ]);
        
        // CISO approves second
        $this->withHeader('Authorization', 'Bearer ' . $cisoToken)
             ->postJson("/api/evaluations/{$evaluation->id}/approve", [
                 'approver_note' => 'CISO approved',
             ]);
        
        $this->assertDatabaseHas('evaluations', [
            'id' => $evaluation->id,
            'status' => 'approved',
            'approved_by' => $ciso->id,
        ]);
    }

    public function test_rejection_workflow()
    {
        $user = User::factory()->create();
        $manager = User::factory()->create(['role' => 'manager']);
        
        $evaluation = Evaluation::factory()->create([
            'user_id' => $user->id,
            'status' => 'submitted',
        ]);
        
        $managerToken = $manager->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $managerToken)
                         ->postJson("/api/evaluations/{$evaluation->id}/reject", [
                             'rejection_reason' => 'Insufficient evidence',
                         ]);
        
        $response->assertStatus(200);
        $this->assertDatabaseHas('evaluations', [
            'id' => $evaluation->id,
            'status' => 'rejected',
            'rejection_reason' => 'Insufficient evidence',
        ]);
    }
}