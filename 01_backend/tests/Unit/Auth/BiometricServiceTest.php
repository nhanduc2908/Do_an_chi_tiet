<?php

namespace Tests\Unit\Evaluation;

use Tests\TestCase;
use App\Models\Evaluation;
use App\Models\Criteria;
use App\Models\EvaluationDetail;
use App\Services\Evaluation\ScoringService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ScoringTest extends TestCase
{
    use RefreshDatabase;

    protected ScoringService $scoringService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->scoringService = new ScoringService();
    }

    public function test_calculate_score_returns_correct_percentage()
    {
        $evaluation = Evaluation::factory()->create();
        $criteria = Criteria::factory()->create(['weight' => 10]);
        
        EvaluationDetail::create([
            'evaluation_id' => $evaluation->id,
            'criteria_id' => $criteria->id,
            'score' => 8,
        ]);

        $result = $this->scoringService->calculateScore($evaluation);
        
        $this->assertEquals(80, $result['percentage']);
    }

    public function test_score_updates_evaluation_record()
    {
        $evaluation = Evaluation::factory()->create([
            'total_score' => null,
            'percentage' => null,
        ]);
        
        $criteria = Criteria::factory()->create(['weight' => 10]);
        
        EvaluationDetail::create([
            'evaluation_id' => $evaluation->id,
            'criteria_id' => $criteria->id,
            'score' => 7,
        ]);

        $this->scoringService->calculateScore($evaluation);
        $evaluation->refresh();
        
        $this->assertEquals(7, $evaluation->total_score);
        $this->assertEquals(70, $evaluation->percentage);
    }
}