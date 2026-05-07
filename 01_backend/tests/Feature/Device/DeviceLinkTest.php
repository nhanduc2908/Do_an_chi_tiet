<?php

namespace Tests\Feature\Device;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeviceLinkTest extends TestCase
{
    use RefreshDatabase;

    public function test_generate_link_code()
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/device/link/generate');
        
        $response->assertStatus(200)
                 ->assertJsonStructure(['code', 'expires_in']);
    }

    public function test_link_device_with_code()
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;
        
        $codeResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
                             ->postJson('/api/device/link/generate');
        
        $code = $codeResponse->json('code');
        
        $response = $this->postJson('/api/device/link', [
            'code' => $code,
            'device_id' => 'NEW_DEVICE',
            'device_name' => 'Android Phone',
            'device_type' => 'android',
        ]);
        
        $response->assertStatus(200);
        $this->assertDatabaseHas('user_devices', [
            'device_id' => 'NEW_DEVICE',
            'user_id' => $user->id,
        ]);
    }

    public function test_link_code_expires()
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;
        
        $codeResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
                             ->postJson('/api/device/link/generate');
        
        $code = $codeResponse->json('code');
        
        $this->travel(6)->minutes();
        
        $response = $this->postJson('/api/device/link', [
            'code' => $code,
            'device_id' => 'NEW_DEVICE',
            'device_name' => 'Android Phone',
            'device_type' => 'android',
        ]);
        
        $response->assertStatus(400);
    }
}