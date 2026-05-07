<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use App\Services\Auth\OtpService;
use App\Services\Auth\KeyService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TwoFactorAuthTest extends TestCase
{
    use RefreshDatabase;

    protected $otpService;
    protected $keyService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->otpService = new OtpService();
        $this->keyService = new KeyService();
    }

    public function test_admin_login_requires_key_verification()
    {
        $user = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'admin@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
                 ->assertJson(['requires_key' => true]);
    }

    public function test_manager_login_requires_otp_verification()
    {
        $user = User::factory()->create([
            'email' => 'manager@example.com',
            'password' => bcrypt('password123'),
            'role' => 'manager',
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'manager@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
                 ->assertJson(['requires_otp' => true]);
    }

    public function test_can_enable_two_factor_auth()
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;
        
        $this->keyService->createUserKeys($user);
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/2fa/enable');

        $response->assertStatus(200);
        $this->assertDatabaseHas('user_keys', [
            'user_id' => $user->id,
            'is_active' => true,
        ]);
    }
}