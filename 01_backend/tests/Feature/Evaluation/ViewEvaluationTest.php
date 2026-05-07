<?php

namespace Tests\Feature\Evaluation;

use Tests\TestCase;
use App\Models\User;
use App\Models\Evaluation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewEvaluationTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_user_can_view_own_evaluations()
    {
        Evaluation::factory()->count(3)->create([
            'user_id' => $this->user->id,
        ]);
        
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson('/api/evaluations');

        $response->assertStatus(200)
                 ->assertJsonCount(3, 'data');
    }

    public function test_admin_can_view_all_evaluations()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Evaluation::factory()->count(5)->create();
        
        $token = $admin->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson('/api/evaluations');

        $response->assertStatus(200)
                 ->assertJsonCount(5, 'data');
    }

    public function test_user_can_view_evaluation_details()
    {
        $evaluation = Evaluation::factory()->create([
            'user_id' => $this->user->id,
        ]);
        
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson("/api/evaluations/{$evaluation->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'id' => $evaluation->id,
                     'title' => $evaluation->title,
                 ]);
    }
}