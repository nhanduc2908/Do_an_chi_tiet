<?php

namespace Tests\Integration\WebSocket;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PrivateChannelTest extends TestCase
{
    use RefreshDatabase;

    public function test_private_channel_authorization()
    {
        $user = User::factory()->create();
        
        $authorized = Broadcast::auth($user, 'private-user.1');
        
        $this->assertTrue($authorized);
    }

    public function test_private_channel_denies_unauthorized_user()
    {
        $user = User::factory()->create();
        
        $authorized = Broadcast::auth($user, 'private-user.999');
        
        $this->assertFalse($authorized);
    }

    public function test_user_can_listen_to_own_private_channel()
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/broadcasting/auth', [
                             'channel_name' => "private-user.{$user->id}",
                         ]);
        
        $response->assertStatus(200);
        $response->assertJsonStructure(['auth']);
    }
}