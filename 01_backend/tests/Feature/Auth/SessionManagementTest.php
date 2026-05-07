<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SessionManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_active_sessions()
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson('/api/sessions');

        $response->assertStatus(200)
                 ->assertJsonStructure([['id', 'device_name', 'last_activity']]);
    }

    public function test_user_can_revoke_other_sessions()
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;
        
        // Create another session
        $user->createToken('other_device');
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->deleteJson('/api/sessions/revoke-others');

        $response->assertStatus(200);
        $this->assertEquals(1, $user->tokens()->count());
    }

    public function test_session_expires_after_inactivity()
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;
        
        // Simulate session timeout
        $this->travel(2)->hours();
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson('/api/me');

        $response->assertStatus(401);
        
        $this->travelBack();
    }
}