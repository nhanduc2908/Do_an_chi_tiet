<?php

namespace Tests\Feature\Training;

use Tests\TestCase;
use App\Models\User;
use App\Models\Training;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AssignTrainingTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_assign_training_to_user()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();
        $training = Training::factory()->create();
        
        $token = $admin->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson("/api/trainings/{$training->id}/assign", [
                             'user_ids' => [$user->id],
                         ]);
        
        $response->assertStatus(200);
        $this->assertDatabaseHas('training_assignments', [
            'training_id' => $training->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_user_receives_notification_on_assignment()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();
        $training = Training::factory()->create();
        
        $token = $admin->createToken('auth_token')->plainTextToken;
        
        $this->withHeader('Authorization', 'Bearer ' . $token)
             ->postJson("/api/trainings/{$training->id}/assign", [
                 'user_ids' => [$user->id],
             ]);
        
        $this->assertDatabaseHas('notifications', [
            'user_id' => $user->id,
            'type' => 'training_assigned',
        ]);
    }
}