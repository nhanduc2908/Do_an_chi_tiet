<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use App\Services\Auth\KeyService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class KeyVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected $keyService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->keyService = new KeyService();
    }

    public function test_valid_key_allows_login()
    {
        $user = User::factory()->create();
        $this->keyService->createUserKeys($user);
        $challenge = $this->keyService->generateChallenge($user);
        
        $response = $this->postJson('/api/verify-key', [
            'user_id' => $user->id,
            'signature' => 'test_signature',
            'challenge' => $challenge,
            'device_id' => 'test_device',
            'session_token' => 'test_session',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['access_token']);
    }

    public function test_invalid_key_blocks_login()
    {
        $user = User::factory()->create();
        $this->keyService->createUserKeys($user);
        
        $response = $this->postJson('/api/verify-key', [
            'user_id' => $user->id,
            'signature' => 'invalid_signature',
            'challenge' => 'invalid_challenge',
            'device_id' => 'test_device',
            'session_token' => 'test_session',
        ]);

        $response->assertStatus(400)
                 ->assertJson(['message' => 'Invalid key']);
    }
}