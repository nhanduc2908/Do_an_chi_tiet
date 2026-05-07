<?php

namespace Tests\Feature\Evaluation;

use Tests\TestCase;
use App\Models\User;
use App\Models\Evaluation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PermissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_all_evaluations()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        
        Evaluation::factory()->count(2)->create(['user_id' => $user1->id]);
        Evaluation::factory()->count(3)->create(['user_id' => $user2->id]);
        
        $token = $admin->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson('/api/evaluations');

        $response->assertStatus(200)
                 ->assertJsonCount(5, 'data');
    }

    public function test_manager_can_view_team_evaluations()
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $teamMember = User::factory()->create(['company_id' => $manager->company_id]);
        
        Evaluation::factory()->create(['user_id' => $teamMember->id]);
        Evaluation::factory()->create(['user_id' => $manager->id]);
        
        $token = $manager->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson('/api/evaluations');

        $response->assertStatus(200)
                 ->assertJsonCount(2, 'data');
    }

    public function test_user_cannot_view_others_evaluations()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        
        Evaluation::factory()->create(['user_id' => $user2->id]);
        
        $token = $user1->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson('/api/evaluations');

        $response->assertStatus(200)
                 ->assertJsonCount(0, 'data');
    }
}