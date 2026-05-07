<?php

namespace Tests\Feature\Security;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SessionSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_session_expires_after_inactivity()
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;
        
        $this->travel(2)->hours();
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson('/api/me');
        
        $response->assertStatus(401);
    }

    public function test_concurrent_session_limit()
    {
        $user = User::factory()->create(['role' => 'dev', 'max_sessions' => 2]);
        
        $token1 = $user->createToken('device1')->plainTextToken;
        $token2 = $user->createToken('device2')->plainTextToken;
        $token3 = $user->createToken('device3')->plainTextToken;
        
        $response1 = $this->withHeader('Authorization', 'Bearer ' . $token1)
                          ->getJson('/api/me');
        
        $response1->assertStatus(200);
        
        // Oldest session should be invalidated
        $responseOld = $this->withHeader('Authorization', 'Bearer ' . $token1)
                            ->getJson('/api/me');
        
        $responseOld->assertStatus(401);
    }
}