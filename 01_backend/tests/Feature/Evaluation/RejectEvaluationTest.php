<?php

namespace Tests\Feature\Evaluation;

use Tests\TestCase;
use App\Models\User;
use App\Models\Evaluation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RejectEvaluationTest extends TestCase
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

    public function test_admin_can_reject_evaluation_with_reason()
    {
        $token = $this->admin->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson("/api/evaluations/{$this->evaluation->id}/reject", [
                             'rejection_reason' => 'Insufficient evidence',
                         ]);

        $response->assertStatus(200);
        
        $this->assertDatabaseHas('evaluations', [
            'id' => $this->evaluation->id,
            'status' => 'rejected',
            'rejection_reason' => 'Insufficient evidence',
        ]);
    }

    public function test_rejection_requires_reason()
    {
        $token = $this->admin->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson("/api/evaluations/{$this->evaluation->id}/reject", []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['rejection_reason']);
    }
}