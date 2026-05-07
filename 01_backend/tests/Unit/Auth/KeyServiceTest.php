<?php

namespace Tests\Unit\Auth;

use Tests\TestCase;
use App\Models\User;
use App\Services\Auth\KeyService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class KeyServiceTest extends TestCase
{
    use RefreshDatabase;

    protected KeyService $keyService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->keyService = new KeyService();
    }

    public function test_create_user_keys()
    {
        $user = User::factory()->create();
        $this->keyService->createUserKeys($user);
        
        $this->assertDatabaseHas('user_keys', [
            'user_id' => $user->id,
            'is_active' => true,
        ]);
    }

    public function test_generate_challenge()
    {
        $user = User::factory()->create();
        $challenge = $this->keyService->generateChallenge($user);
        
        $this->assertIsString($challenge);
        $this->assertEquals(64, strlen($challenge));
    }
}