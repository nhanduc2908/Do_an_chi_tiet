<?php

namespace Tests\Feature\Evaluation;

use Tests\TestCase;
use App\Models\User;
use App\Models\Domain;
use App\Models\Criteria;
use App\Models\Evaluation;
use App\Models\EvaluationDetail;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EvaluationFlowTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $domain;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->domain = Domain::factory()->create();
    }

    public function test_complete_evaluation_flow()
    {
        $token = $this->user->createToken('auth_token')->plainTextToken;
        
        // 1. Create evaluation
        $createResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
                              ->postJson('/api/evaluations', [
                                  'title' => 'Complete Flow Test',
                                  'domain_id' => $this->domain->id,
                              ]);
        
        $createResponse->assertStatus(201);
        $evaluationId = $createResponse->json('id');
        
        // 2. Add criteria details
        $criteria = Criteria::factory()->create(['domain_id' => $this->domain->id]);
        
        $detailResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
                               ->putJson("/api/evaluations/{$evaluationId}/details/{$criteria->id}", [
                                   'score' => 9,
                                   'notes' => 'Good job',
                               ]);
        
        $detailResponse->assertStatus(200);
        
        // 3. Submit evaluation
        $submitResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
                               ->postJson("/api/evaluations/{$evaluationId}/submit");
        
        $submitResponse->assertStatus(200);
        
        // 4. Approve evaluation (as admin)
        $admin = User::factory()->create(['role' => 'admin']);
        $adminToken = $admin->createToken('auth_token')->plainTextToken;
        
        $approveResponse = $this->withHeader('Authorization', 'Bearer ' . $adminToken)
                                ->postJson("/api/evaluations/{$evaluationId}/approve", [
                                    'approver_note' => 'Approved',
                                ]);
        
        $approveResponse->assertStatus(200);
        
        // Verify final status
        $this->assertDatabaseHas('evaluations', [
            'id' => $evaluationId,
            'status' => 'approved',
        ]);
    }
}