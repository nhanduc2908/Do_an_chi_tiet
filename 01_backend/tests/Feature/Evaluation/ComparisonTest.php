<?php

namespace Tests\Feature\Evaluation;

use Tests\TestCase;
use App\Models\User;
use App\Models\Evaluation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ComparisonTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_user_can_compare_two_evaluations()
    {
        $eval1 = Evaluation::factory()->create([
            'user_id' => $this->user->id,
            'title' => 'Evaluation A',
            'percentage' => 85,
        ]);
        
        $eval2 = Evaluation::factory()->create([
            'user_id' => $this->user->id,
            'title' => 'Evaluation B',
            'percentage' => 92,
        ]);
        
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson("/api/evaluations/compare/{$eval1->id}/{$eval2->id}");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'evaluation_1',
                     'evaluation_2',
                     'criteria_comparison',
                 ]);
    }

    public function test_comparison_shows_differences()
    {
        $eval1 = Evaluation::factory()->create([
            'user_id' => $this->user->id,
            'percentage' => 70,
        ]);
        
        $eval2 = Evaluation::factory()->create([
            'user_id' => $this->user->id,
            'percentage' => 90,
        ]);
        
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson("/api/evaluations/compare/{$eval1->id}/{$eval2->id}");

        $response->assertStatus(200);
        $this->assertEquals(70, $response->json('evaluation_1.percentage'));
        $this->assertEquals(90, $response->json('evaluation_2.percentage'));
    }
}