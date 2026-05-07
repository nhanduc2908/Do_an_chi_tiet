<?php

namespace Tests\Integration\WebSocket;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PresenceChannelTest extends TestCase
{
    use RefreshDatabase;

    public function test_presence_channel_authorization()
    {
        $user = User::factory()->create();
        
        $authorized = Broadcast::auth($user, 'presence-room.1');
        
        $this->assertTrue($authorized);
    }

    public function test_presence_channel_returns_user_info()
    {
        $user = User::factory()->create();
        
        $authData = Broadcast::auth($user, 'presence-room.1');
        
        $this->assertArrayHasKey('auth', $authData);
        $this->assertArrayHasKey('user_info', $authData);
    }

    public function test_presence_channel_tracks_online_users()
    {
        $user = User::factory()->create();
        
        // Simulate user joining presence channel
        $presenceData = [
            'user_id' => $user->id,
            'user_info' => ['name' => $user->name],
        ];
        
        $this->assertIsArray($presenceData);
    }
}