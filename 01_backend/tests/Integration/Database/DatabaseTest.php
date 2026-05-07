<?php

namespace Tests\Integration\Database;

use Tests\TestCase;
use App\Models\User;
use App\Models\Evaluation;
use App\Models\EvaluationDetail;
use App\Models\Criteria;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DatabaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_evaluation_relationship()
    {
        $user = User::factory()->create();
        $evaluations = Evaluation::factory()->count(3)->create([
            'user_id' => $user->id,
        ]);

        $this->assertEquals(3, $user->evaluations->count());
        $this->assertInstanceOf(Evaluation::class, $user->evaluations->first());
    }

    public function test_evaluation_detail_relationship()
    {
        $evaluation = Evaluation::factory()->create();
        $criteria = Criteria::factory()->create();
        
        EvaluationDetail::create([
            'evaluation_id' => $evaluation->id,
            'criteria_id' => $criteria->id,
            'score' => 8,
        ]);

        $this->assertEquals(1, $evaluation->details->count());
        $this->assertEquals(8, $evaluation->details->first()->score);
    }

    public function test_cascade_delete()
    {
        $user = User::factory()->create();
        $evaluation = Evaluation::factory()->create(['user_id' => $user->id]);
        
        $user->delete();
        
        $this->assertDatabaseMissing('evaluations', ['id' => $evaluation->id]);
    }
}