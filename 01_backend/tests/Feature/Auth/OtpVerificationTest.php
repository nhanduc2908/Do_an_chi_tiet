<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use App\Services\Auth\OtpService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OtpVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected $otpService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->otpService = new OtpService();
    }

    public function test_valid_otp_allows_login()
    {
        $user = User::factory()->create();
        $otp = $this->otpService->generate($user);
        
        $response = $this->postJson('/api/verify-otp', [
            'user_id' => $user->id,
            'otp' => $otp,
            'device_id' => 'test_device',
            'session_token' => 'test_session',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['access_token']);
    }

    public function test_invalid_otp_blocks_login()
    {
        $user = User::factory()->create();
        
        $response = $this->postJson('/api/verify-otp', [
            'user_id' => $user->id,
            'otp' => '000000',
            'device_id' => 'test_device',
            'session_token' => 'test_session',
        ]);

        $response->assertStatus(400)
                 ->assertJson(['message' => 'Invalid OTP']);
    }

    public function test_expired_otp_cannot_be_used()
    {
        $user = User::factory()->create();
        $otp = $this->otpService->generate($user);
        
        // Simulate OTP expiration
        $this->travel(6)->minutes();
        
        $response = $this->postJson('/api/verify-otp', [
            'user_id' => $user->id,
            'otp' => $otp,
            'device_id' => 'test_device',
            'session_token' => 'test_session',
        ]);

        $response->assertStatus(400)
                 ->assertJson(['message' => 'Invalid OTP']);
        
        $this->travelBack();
    }
}