<?php

// Đường dẫn: C:\xampp\htdocs\security_evaluation_system\01_backend\tests\Unit\Models\SyncModelTest.php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Sync;
use App\Models\Device;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SyncModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function sync_belongs_to_device(): void
    {
        $device = Device::factory()->create();
        $sync = Sync::factory()->create(['device_id' => $device->id]);

        $this->assertInstanceOf(Device::class, $sync->device);
    }

    /** @test */
    public function sync_has_status(): void
    {
        $sync = Sync::factory()->create(['status' => 'pending']);
        $this->assertEquals('pending', $sync->status);
    }
}