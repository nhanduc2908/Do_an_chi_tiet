<?php

namespace Tests\Unit\Evaluation;

use Tests\TestCase;
use App\Services\Evaluation\WeightCalculator;

class WeightCalculatorTest extends TestCase
{
    protected WeightCalculator $calculator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->calculator = new WeightCalculator();
    }

    public function test_normalize_weights()
    {
        $weights = [10, 20, 30];
        $normalized = $this->calculator->normalizeWeights($weights);
        
        $this->assertEquals(10/60, $normalized[0]);
        $this->assertEquals(20/60, $normalized[1]);
        $this->assertEquals(30/60, $normalized[2]);
    }

    public function test_calculate_weighted_score()
    {
        $scores = [80, 90, 70];
        $weights = [0.2, 0.3, 0.5];
        
        $result = $this->calculator->calculateWeightedScore($scores, $weights);
        
        $expected = (80 * 0.2) + (90 * 0.3) + (70 * 0.5);
        $this->assertEquals($expected, $result);
    }
}