<?php

namespace Tests\Unit\Auth;

use Tests\TestCase;
use App\Models\User;
use App\Services\Auth\OtpService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OtpServiceTest extends TestCase
{
    use RefreshDatabase;

    protected OtpService $otpService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->otpService = new OtpService();
    }

    public function test_generate_returns_6_digit_code()
    {
        $user = User::factory()->create();
        $otp = $this->otpService->generate($user);
        
        $this->assertMatchesRegularExpression('/^\d{6}$/', $otp);
    }

    public function test_verify_valid_otp_returns_true()
    {
        $user = User::factory()->create();
        $otp = $this->otpService->generate($user);
        
        $result = $this->otpService->verify($user, $otp);
        $this->assertTrue($result);
    }

    public function test_verify_invalid_otp_returns_false()
    {
        $user = User::factory()->create();
        $this->otpService->generate($user);
        
        $result = $this->otpService->verify($user, '000000');
        $this->assertFalse($result);
    }
}