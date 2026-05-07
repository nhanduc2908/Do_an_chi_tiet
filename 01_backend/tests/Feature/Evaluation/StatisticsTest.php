<?php

namespace Tests\Feature\Evaluation;

use Tests\TestCase;
use App\Models\User;
use App\Models\Evaluation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StatisticsTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_user_can_view_evaluation_statistics()
    {
        Evaluation::factory()->count(5)->create([
            'user_id' => $this->user->id,
            'status' => 'approved',
            'percentage' => 85,
        ]);
        
        Evaluation::factory()->count(2)->create([
            'user_id' => $this->user->id,
            'status' => 'approved',
            'percentage' => 65,
        ]);
        
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson('/api/evaluations/statistics');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'total',
                     'avg_score',
                     'by_status',
                     'by_domain',
                     'trend',
                 ]);
    }

    public function test_statistics_show_correct_average()
    {
        Evaluation::factory()->create([
            'user_id' => $this->user->id,
            'percentage' => 80,
        ]);
        Evaluation::factory()->create([
            'user_id' => $this->user->id,
            'percentage' => 100,
        ]);
        
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson('/api/evaluations/statistics');

        $response->assertStatus(200);
        $this->assertEquals(90, $response->json('avg_score'));
    }
}