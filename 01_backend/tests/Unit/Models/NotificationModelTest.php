<?php

// Đường dẫn: C:\xampp\htdocs\security_evaluation_system\01_backend\tests\Unit\Models\NotificationModelTest.php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NotificationModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function notification_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $notification = Notification::factory()->create([
            'notifiable_type' => User::class,
            'notifiable_id' => $user->id,
        ]);

        $this->assertInstanceOf(User::class, $notification->notifiable);
    }

    /** @test */
    public function notification_can_be_marked_as_read(): void
    {
        $notification = Notification::factory()->create(['read_at' => null]);
        $this->assertNull($notification->read_at);

        $notification->markAsRead();
        $this->assertNotNull($notification->fresh()->read_at);
    }
}