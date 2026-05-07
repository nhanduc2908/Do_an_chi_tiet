<?php

// Đường dẫn: C:\xampp\htdocs\security_evaluation_system\01_backend\tests\Unit\Models\IncidentModelTest.php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Incident;
use App\Models\Device;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IncidentModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function incident_belongs_to_device(): void
    {
        $device = Device::factory()->create();
        $incident = Incident::factory()->create(['device_id' => $device->id]);

        $this->assertInstanceOf(Device::class, $incident->device);
    }

    /** @test */
    public function incident_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $incident = Incident::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $incident->user);
    }

    /** @test */
    public function incident_can_be_created(): void
    {
        $incident = Incident::factory()->create([
            'title' => 'Security Breach',
            'severity' => 'high',
            'status' => 'open'
        ]);

        $this->assertDatabaseHas('incidents', ['title' => 'Security Breach']);
    }
}