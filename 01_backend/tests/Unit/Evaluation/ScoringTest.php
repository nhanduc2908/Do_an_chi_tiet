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

    public function test_calculate_score_with_multiple_criteria()
    {
        $evaluation = Evaluation::factory()->create();
        
        $criteria1 = Criteria::factory()->create(['weight' => 10]);
        $criteria2 = Criteria::factory()->create(['weight' => 20]);
        
        EvaluationDetail::create([
            'evaluation_id' => $evaluation->id,
            'criteria_id' => $criteria1->id,
            'score' => 10,
        ]);
        
        EvaluationDetail::create([
            'evaluation_id' => $evaluation->id,
            'criteria_id' => $criteria2->id,
            'score' => 15,
        ]);

        $result = $this->scoringService->calculateScore($evaluation);
        
        $expected = (10 + 15) / (10 + 20) * 100;
        $this->assertEquals($expected, $result['percentage']);
    }
}