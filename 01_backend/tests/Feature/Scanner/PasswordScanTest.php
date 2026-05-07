<?php

namespace Tests\Feature\Scanner;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PasswordScanTest extends TestCase
{
    use RefreshDatabase;

    public function test_check_password_strength()
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/scanner/password/check', [
                             'password' => 'Weak123!',
                         ]);
        
        $response->assertStatus(200)
                 ->assertJsonStructure(['strength', 'score', 'feedback']);
    }

    public function test_detect_compromised_password()
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/scanner/password/check', [
                             'password' => 'password123',
                         ]);
        
        $response->assertStatus(200);
        $this->assertTrue($response->json('is_compromised'));
    }

    public function test_password_policy_enforcement()
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/scanner/password/check', [
                             'password' => 'short',
                         ]);
        
        $response->assertStatus(200);
        $this->assertLessThan(50, $response->json('score'));
    }
}