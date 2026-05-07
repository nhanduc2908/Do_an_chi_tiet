<?php

namespace Tests\Feature\Evaluation;

use Tests\TestCase;
use App\Models\User;
use App\Models\Evaluation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApproveEvaluationTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $evaluation;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->evaluation = Evaluation::factory()->create([
            'status' => 'submitted',
        ]);
    }

    public function test_admin_can_approve_evaluation()
    {
        $token = $this->admin->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson("/api/evaluations/{$this->evaluation->id}/approve", [
                             'approver_note' => 'Evaluation meets all requirements',
                         ]);

        $response->assertStatus(200);
        
        $this->assertDatabaseHas('evaluations', [
            'id' => $this->evaluation->id,
            'status' => 'approved',
            'approved_by' => $this->admin->id,
            'approver_note' => 'Evaluation meets all requirements',
        ]);
    }

    public function test_manager_can_approve_evaluation()
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $token = $manager->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson("/api/evaluations/{$this->evaluation->id}/approve");

        $response->assertStatus(200);
    }

    public function test_dev_cannot_approve_evaluation()
    {
        $dev = User::factory()->create(['role' => 'dev']);
        $token = $dev->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson("/api/evaluations/{$this->evaluation->id}/approve");

        $response->assertStatus(403);
    }

    public function test_cannot_approve_non_submitted_evaluation()
    {
        $evaluation = Evaluation::factory()->create(['status' => 'draft']);
        $token = $this->admin->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson("/api/evaluations/{$evaluation->id}/approve");

        $response->assertStatus(422);
    }
}