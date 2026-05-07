<?php

namespace Tests\Feature\Evaluation;

use Tests\TestCase;
use App\Models\User;
use App\Models\Domain;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateEvaluationTest extends TestCase
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
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/evaluations', [
                             'title' => 'Security Assessment Q1',
                             'domain_id' => $this->domain->id,
                             'notes' => 'This is a test evaluation',
                         ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'id', 'title', 'domain_id', 'user_id', 'status', 'created_at'
                 ]);
        
        $this->assertDatabaseHas('evaluations', [
            'title' => 'Security Assessment Q1',
            'user_id' => $this->user->id,
            'status' => 'draft',
        ]);
    }

    public function test_user_cannot_create_evaluation_without_title()
    {
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/evaluations', [
                             'domain_id' => $this->domain->id,
                         ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['title']);
    }

    public function test_user_cannot_create_evaluation_with_invalid_domain()
    {
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/evaluations', [
                             'title' => 'Test',
                             'domain_id' => 99999,
                         ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['domain_id']);
    }

    public function test_creating_evaluation_creates_default_details()
    {
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/evaluations', [
                             'title' => 'Test Evaluation',
                             'domain_id' => $this->domain->id,
                         ]);

        $evaluationId = $response->json('id');
        
        $this->assertDatabaseHas('evaluation_details', [
            'evaluation_id' => $evaluationId,
        ]);
    }
}