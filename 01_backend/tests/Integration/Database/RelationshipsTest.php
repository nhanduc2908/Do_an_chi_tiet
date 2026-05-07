<?php

namespace Tests\Integration\Database;

use Tests\TestCase;
use App\Models\User;
use App\Models\Evaluation;
use App\Models\EvaluationDetail;
use App\Models\Criteria;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RelationshipsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_has_many_evaluations()
    {
        $user = User::factory()->create();
        Evaluation::factory()->count(3)->create(['user_id' => $user->id]);
        
        $this->assertEquals(3, $user->evaluations->count());
    }

    public function test_evaluation_belongs_to_user()
    {
        $user = User::factory()->create();
        $evaluation = Evaluation::factory()->create(['user_id' => $user->id]);
        
        $this->assertEquals($user->id, $evaluation->user->id);
    }

    public function test_evaluation_has_many_details()
    {
        $evaluation = Evaluation::factory()->create();
        $criteria = Criteria::factory()->create();
        
        EvaluationDetail::create([
            'evaluation_id' => $evaluation->id,
            'criteria_id' => $criteria->id,
            'score' => 85,
        ]);
        
        $this->assertEquals(1, $evaluation->details->count());
    }

    public function test_cascade_delete()
    {
        $user = User::factory()->create();
        $evaluation = Evaluation::factory()->create(['user_id' => $user->id]);
        
        $user->delete();
        
        $this->assertDatabaseMissing('evaluations', ['id' => $evaluation->id]);
    }
}