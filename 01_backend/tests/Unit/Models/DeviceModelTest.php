<?php

// Đường dẫn: C:\xampp\htdocs\security_evaluation_system\01_backend\tests\Unit\Models\DeviceModelTest.php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Device;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeviceModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function device_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $device = Device::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $device->user);
        $this->assertEquals($user->id, $device->user->id);
    }

    /** @test */
    public function device_can_be_created(): void
    {
        $device = Device::factory()->create([
            'name' => 'Test Device',
            'type' => 'mobile',
            'status' => 'active'
        ]);

        $this->assertDatabaseHas('devices', [
            'name' => 'Test Device',
            'type' => 'mobile'
        ]);
    }
}