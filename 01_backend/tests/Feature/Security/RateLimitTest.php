<?php

namespace Tests\Feature\Security;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RateLimitTest extends TestCase
{
    use RefreshDatabase;

    public function test_rate_limit_exceeded()
    {
        for ($i = 0; $i < 61; $i++) {
            $response = $this->get('/api/health');
        }
        
        $response->assertStatus(429);
    }

    public function test_login_rate_limit()
    {
        for ($i = 0; $i < 6; $i++) {
            $response = $this->postJson('/api/login', [
                'email' => 'test@example.com',
                'password' => 'wrongpassword',
            ]);
        }
        
        $response->assertStatus(429);
    }

    public function test_api_rate_limit_by_user()
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;
        
        for ($i = 0; $i < 61; $i++) {
            $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                             ->get('/api/me');
        }
        
        $response->assertStatus(429);
    }
}