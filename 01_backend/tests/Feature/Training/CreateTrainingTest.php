<?php

namespace Tests\Feature\Training;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateTrainingTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_training()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $token = $admin->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/trainings', [
                             'title' => 'Security Awareness',
                             'description' => 'Basic security training',
                             'type' => 'course',
                             'duration' => 120,
                         ]);
        
        $response->assertStatus(201);
        $this->assertDatabaseHas('trainings', [
            'title' => 'Security Awareness',
        ]);
    }

    public function test_hr_can_create_training()
    {
        $hr = User::factory()->create(['role' => 'hr']);
        $token = $hr->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/trainings', [
                             'title' => 'Data Protection',
                             'description' => 'GDPR training',
                             'type' => 'course',
                             'duration' => 90,
                         ]);
        
        $response->assertStatus(201);
    }

    public function test_dev_cannot_create_training()
    {
        $dev = User::factory()->create(['role' => 'dev']);
        $token = $dev->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/trainings', [
                             'title' => 'Security Training',
                             'type' => 'course',
                         ]);
        
        $response->assertStatus(403);
    }
}