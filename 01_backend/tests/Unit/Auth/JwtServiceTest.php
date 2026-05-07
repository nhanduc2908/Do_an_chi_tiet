<?php

namespace Tests\Unit\Auth;

use Tests\TestCase;
use App\Models\User;
use App\Services\Auth\JwtService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class JwtServiceTest extends TestCase
{
    use RefreshDatabase;

    protected JwtService $jwtService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->jwtService = new JwtService();
    }

    public function test_generate_token_returns_string()
    {
        $user = User::factory()->create();
        $token = $this->jwtService->generateToken($user);
        
        $this->assertIsString($token);
    }

    public function test_verify_token_returns_decoded()
    {
        $user = User::factory()->create();
        $token = $this->jwtService->generateToken($user);
        $decoded = $this->jwtService->verifyToken($token);
        
        $this->assertEquals($user->id, $decoded->sub);
    }

    public function test_refresh_token_returns_new_token()
    {
        $user = User::factory()->create();
        $token = $this->jwtService->generateToken($user);
        $newToken = $this->jwtService->refreshToken($token);
        
        $this->assertNotEquals($token, $newToken);
    }
}