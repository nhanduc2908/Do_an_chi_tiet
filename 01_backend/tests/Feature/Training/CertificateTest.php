<?php

namespace Tests\Feature\Training;

use Tests\TestCase;
use App\Models\User;
use App\Models\Training;
use App\Models\TrainingAssignment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CertificateTest extends TestCase
{
    use RefreshDatabase;

    public function test_generate_certificate_on_completion()
    {
        $user = User::factory()->create();
        $training = Training::factory()->create(['title' => 'Security Training']);
        $assignment = TrainingAssignment::create([
            'training_id' => $training->id,
            'user_id' => $user->id,
            'assigned_by' => 1,
            'status' => 'assigned',
        ]);
        
        $token = $user->createToken('auth_token')->plainTextToken;
        
        $this->withHeader('Authorization', 'Bearer ' . $token)
             ->postJson("/api/trainings/{$assignment->id}/complete", [
                 'score' => 95,
             ]);
        
        $this->assertDatabaseHas('files', [
            'name' => "Certificate_{$training->id}_{$user->id}.pdf",
        ]);
    }

    public function test_download_certificate()
    {
        $user = User::factory()->create();
        $training = Training::factory()->create();
        
        $token = $user->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson("/api/trainings/{$training->id}/certificate");
        
        $response->assertStatus(200)
                 ->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_verify_certificate_authenticity()
    {
        $user = User::factory()->create();
        $training = Training::factory()->create();
        
        $token = $user->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson("/api/trainings/{$training->id}/certificate/verify");
        
        $response->assertStatus(200);
        $this->assertTrue($response->json('is_valid'));
    }
}