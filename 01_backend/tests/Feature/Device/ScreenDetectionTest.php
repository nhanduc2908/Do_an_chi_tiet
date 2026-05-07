<?php

namespace Tests\Feature\Device;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ScreenDetectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_detect_mobile_screen()
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/device/screen/detect', [
                             'width' => 375,
                             'height' => 667,
                             'device_pixel_ratio' => 2,
                             'orientation' => 'portrait',
                         ]);
        
        $response->assertStatus(200);
        $this->assertEquals('mobile', $response->json('device'));
    }

    public function test_detect_tablet_screen()
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/device/screen/detect', [
                             'width' => 1024,
                             'height' => 768,
                             'orientation' => 'landscape',
                         ]);
        
        $response->assertStatus(200);
        $this->assertEquals('tablet', $response->json('device'));
    }

    public function test_detect_desktop_screen()
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/device/screen/detect', [
                             'width' => 1920,
                             'height' => 1080,
                             'orientation' => 'landscape',
                         ]);
        
        $response->assertStatus(200);
        $this->assertEquals('desktop', $response->json('device'));
    }
}