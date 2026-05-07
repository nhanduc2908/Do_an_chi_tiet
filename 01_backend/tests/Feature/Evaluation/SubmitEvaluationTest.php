<?php

namespace Tests\Feature\Evaluation;

use Tests\TestCase;
use App\Models\User;
use App\Models\Domain;
use App\Models\Criteria;
use App\Models\Evaluation;
use App\Models\EvaluationDetail;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubmitEvaluationTest extends TestCase
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

    public function test_user_can_submit_completed_evaluation()
    {
        $evaluation = Evaluation::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'draft',
        ]);
        
        $criteria = Criteria::factory()->create([
            'domain_id' => $this->domain->id,
        ]);
        
        EvaluationDetail::create([
            'evaluation_id' => $evaluation->id,
            'criteria_id' => $criteria->id,
            'score' => 8,
        ]);
        
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson("/api/evaluations/{$evaluation->id}/submit");

        $response->assertStatus(200);
        
        $this->assertDatabaseHas('evaluations', [
            'id' => $evaluation->id,
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);
    }

    public function test_user_cannot_submit_incomplete_evaluation()
    {
        $evaluation = Evaluation::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'draft',
        ]);
        
        // No details created
        
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson("/api/evaluations/{$evaluation->id}/submit");

        $response->assertStatus(422);
    }

    public function test_cannot_submit_already_submitted_evaluation()
    {
        $evaluation = Evaluation::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'submitted',
        ]);
        
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson("/api/evaluations/{$evaluation->id}/submit");

        $response->assertStatus(422);
    }
}