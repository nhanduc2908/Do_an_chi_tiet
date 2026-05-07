<?php

// Đường dẫn: C:\xampp\htdocs\security_evaluation_system\01_backend\tests\Unit\Models\TrainingModelTest.php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Training;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TrainingModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function training_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $training = Training::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $training->user);
    }

    /** @test */
    public function training_can_be_completed(): void
    {
        $training = Training::factory()->create(['completed_at' => null]);
        $this->assertNull($training->completed_at);

        $training->update(['completed_at' => now()]);
        $this->assertNotNull($training->fresh()->completed_at);
    }
}