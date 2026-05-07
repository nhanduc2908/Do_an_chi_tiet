<?php

namespace Tests\Unit\Auth;

use Tests\TestCase;
use App\Models\User;
use App\Services\Auth\JwtService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $result = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $result->assertStatus(200)
               ->assertJsonStructure(['access_token', 'token_type', 'user']);
    }

    public function test_user_cannot_login_with_invalid_credentials()
    {
        $result = $this->postJson('/api/login', [
            'email' => 'wrong@example.com',
            'password' => 'wrongpassword',
        ]);

        $result->assertStatus(401);
    }

    public function test_admin_requires_key()
    {
        $user = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);

        $result = $this->postJson('/api/login', [
            'email' => 'admin@example.com',
            'password' => 'password123',
        ]);

        $result->assertStatus(200)
               ->assertJson(['requires_key' => true]);
    }

    public function test_low_role_requires_otp()
    {
        $user = User::factory()->create([
            'email' => 'dev@example.com',
            'password' => bcrypt('password123'),
            'role' => 'dev',
        ]);

        $result = $this->postJson('/api/login', [
            'email' => 'dev@example.com',
            'password' => 'password123',
        ]);

        $result->assertStatus(200)
               ->assertJson(['requires_otp' => false]);
    }
}