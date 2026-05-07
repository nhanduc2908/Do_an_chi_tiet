<?php

namespace Tests\Integration\Workflow;

use Tests\TestCase;
use App\Models\User;
use App\Models\Domain;
use App\Models\Criteria;
use App\Models\Evaluation;
use App\Models\EvaluationDetail;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EvaluationWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_complete_evaluation_workflow()
    {
        $user = User::factory()->create();
        $domain = Domain::factory()->create();
        $criteria = Criteria::factory()->create(['domain_id' => $domain->id]);
        
        $token = $user->createToken('auth_token')->plainTextToken;
        
        // 1. Create evaluation
        $createResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
                              ->postJson('/api/evaluations', [
                                  'title' => 'Workflow Test',
                                  'domain_id' => $domain->id,
                              ]);
        
        $createResponse->assertStatus(201);
        $evaluationId = $createResponse->json('id');
        
        // 2. Add details
        $this->withHeader('Authorization', 'Bearer ' . $token)
             ->putJson("/api/evaluations/{$evaluationId}/details/{$criteria->id}", [
                 'score' => 85,
                 'notes' => 'Good',
             ]);
        
        // 3. Submit
        $this->withHeader('Authorization', 'Bearer ' . $token)
             ->postJson("/api/evaluations/{$evaluationId}/submit");
        
        // 4. Approve (as admin)
        $admin = User::factory()->create(['role' => 'admin']);
        $adminToken = $admin->createToken('auth_token')->plainTextToken;
        
        $this->withHeader('Authorization', 'Bearer ' . $adminToken)
             ->postJson("/api/evaluations/{$evaluationId}/approve");
        
        // Verify final state
        $this->assertDatabaseHas('evaluations', [
            'id' => $evaluationId,
            'status' => 'approved',
        ]);
    }
}