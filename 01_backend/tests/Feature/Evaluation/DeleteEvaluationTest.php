<?php

namespace Tests\Feature\Evaluation;

use Tests\TestCase;
use App\Models\User;
use App\Models\Evaluation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteEvaluationTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_user_can_delete_own_draft_evaluation()
    {
        $evaluation = Evaluation::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'draft',
        ]);
        
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->deleteJson("/api/evaluations/{$evaluation->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('evaluations', ['id' => $evaluation->id]);
    }

    public function test_user_cannot_delete_submitted_evaluation()
    {
        $evaluation = Evaluation::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'submitted',
        ]);
        
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->deleteJson("/api/evaluations/{$evaluation->id}");

        $response->assertStatus(422);
    }

    public function test_user_cannot_delete_others_evaluation()
    {
        $otherUser = User::factory()->create();
        $evaluation = Evaluation::factory()->create([
            'user_id' => $otherUser->id,
        ]);
        
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->deleteJson("/api/evaluations/{$evaluation->id}");

        $response->assertStatus(403);
    }
}