<?php

namespace Tests\Feature\Evaluation;

use Tests\TestCase;
use App\Models\User;
use App\Models\Domain;
use App\Models\Criteria;
use App\Models\Evaluation;
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

    public function test_user_can_create_evaluation()
    {
        $response = $this->actingAs($this->user)
                         ->postJson('/api/evaluations', [
                             'title' => 'Security Assessment',
                             'domain_id' => $this->domain->id,
                         ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('evaluations', [
            'title' => 'Security Assessment',
            'user_id' => $this->user->id,
        ]);
    }

    public function test_user_can_submit_evaluation()
    {
        $evaluation = Evaluation::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'draft',
        ]);
        
        $criteria = Criteria::factory()->create(['domain_id' => $this->domain->id]);
        
        $evaluation->details()->create([
            'criteria_id' => $criteria->id,
            'score' => 8,
        ]);

        $response = $this->actingAs($this->user)
                         ->postJson("/api/evaluations/{$evaluation->id}/submit");

        $response->assertStatus(200);
        $this->assertDatabaseHas('evaluations', [
            'id' => $evaluation->id,
            'status' => 'submitted',
        ]);
    }

    public function test_admin_can_approve_evaluation()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $evaluation = Evaluation::factory()->create([
            'status' => 'submitted',
        ]);

        $response = $this->actingAs($admin)
                         ->postJson("/api/evaluations/{$evaluation->id}/approve", [
                             'approver_note' => 'Approved',
                         ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('evaluations', [
            'id' => $evaluation->id,
            'status' => 'approved',
        ]);
    }
}