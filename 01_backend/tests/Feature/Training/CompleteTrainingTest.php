<?php

namespace Tests\Feature\Training;

use Tests\TestCase;
use App\Models\User;
use App\Models\Training;
use App\Models\TrainingAssignment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CompleteTrainingTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_complete_assigned_training()
    {
        $user = User::factory()->create();
        $training = Training::factory()->create();
        $assignment = TrainingAssignment::create([
            'training_id' => $training->id,
            'user_id' => $user->id,
            'assigned_by' => 1,
            'status' => 'assigned',
        ]);
        
        $token = $user->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson("/api/trainings/{$assignment->id}/complete", [
                             'score' => 95,
                             'feedback' => 'Great course!',
                         ]);
        
        $response->assertStatus(200);
        $this->assertDatabaseHas('training_assignments', [
            'id' => $assignment->id,
            'status' => 'completed',
            'score' => 95,
        ]);
    }

    public function test_certificate_generated_on_completion()
    {
        $user = User::factory()->create();
        $training = Training::factory()->create();
        $assignment = TrainingAssignment::create([
            'training_id' => $training->id,
            'user_id' => $user->id,
            'assigned_by' => 1,
            'status' => 'assigned',
        ]);
        
        $token = $user->createToken('auth_token')->plainTextToken;
        
        $this->withHeader('Authorization', 'Bearer ' . $token)
             ->postJson("/api/trainings/{$assignment->id}/complete", [
                 'score' => 100,
             ]);
        
        $this->assertDatabaseHas('files', [
            'name' => "Certificate_{$training->id}_{$user->id}.pdf",
        ]);
    }
}