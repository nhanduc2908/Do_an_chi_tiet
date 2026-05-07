<?php

namespace Tests\Feature\Evaluation;

use Tests\TestCase;
use App\Models\User;
use App\Models\Domain;
use App\Models\Evaluation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateEvaluationTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $domain;
    protected $evaluation;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->domain = Domain::factory()->create();
        $this->evaluation = Evaluation::factory()->create([
            'user_id' => $this->user->id,
            'domain_id' => $this->domain->id,
            'status' => 'draft',
        ]);
    }

    public function test_user_can_update_own_evaluation()
    {
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->putJson("/api/evaluations/{$this->evaluation->id}", [
                             'title' => 'Updated Title',
                             'notes' => 'Updated notes',
                         ]);

        $response->assertStatus(200);
        
        $this->assertDatabaseHas('evaluations', [
            'id' => $this->evaluation->id,
            'title' => 'Updated Title',
            'notes' => 'Updated notes',
        ]);
    }

    public function test_user_cannot_update_submitted_evaluation()
    {
        $evaluation = Evaluation::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'submitted',
        ]);
        
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->putJson("/api/evaluations/{$evaluation->id}", [
                             'title' => 'Updated Title',
                         ]);

        $response->assertStatus(422);
    }

    public function test_user_cannot_update_others_evaluation()
    {
        $otherUser = User::factory()->create();
        $evaluation = Evaluation::factory()->create([
            'user_id' => $otherUser->id,
        ]);
        
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->putJson("/api/evaluations/{$evaluation->id}", [
                             'title' => 'Updated Title',
                         ]);

        $response->assertStatus(403);
    }
}