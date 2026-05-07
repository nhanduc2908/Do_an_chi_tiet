<?php

namespace Tests\Feature\Training;

use Tests\TestCase;
use App\Models\User;
use App\Models\Training;
use App\Models\TrainingAssignment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TrainingProgressTest extends TestCase
{
    use RefreshDatabase;

    public function test_view_training_progress()
    {
        $user = User::factory()->create();
        $training = Training::factory()->create();
        TrainingAssignment::create([
            'training_id' => $training->id,
            'user_id' => $user->id,
            'assigned_by' => 1,
            'status' => 'in_progress',
            'progress' => 50,
        ]);
        
        $token = $user->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson('/api/trainings/progress');
        
        $response->assertStatus(200);
        $this->assertEquals(50, $response->json()[0]['progress']);
    }

    public function test_update_training_progress()
    {
        $user = User::factory()->create();
        $training = Training::factory()->create();
        $assignment = TrainingAssignment::create([
            'training_id' => $training->id,
            'user_id' => $user->id,
            'assigned_by' => 1,
            'status' => 'in_progress',
            'progress' => 30,
        ]);
        
        $token = $user->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->putJson("/api/trainings/{$assignment->id}/progress", [
                             'progress' => 75,
                         ]);
        
        $response->assertStatus(200);
        $this->assertDatabaseHas('training_assignments', [
            'id' => $assignment->id,
            'progress' => 75,
        ]);
    }

    public function test_manager_can_view_team_training_progress()
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $teamMember = User::factory()->create(['company_id' => $manager->company_id]);
        
        $training = Training::factory()->create();
        TrainingAssignment::create([
            'training_id' => $training->id,
            'user_id' => $teamMember->id,
            'assigned_by' => $manager->id,
            'status' => 'in_progress',
            'progress' => 60,
        ]);
        
        $token = $manager->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson('/api/trainings/team-progress');
        
        $response->assertStatus(200);
        $this->assertCount(1, $response->json());
    }
}