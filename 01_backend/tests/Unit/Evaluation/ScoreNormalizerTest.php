<?php

namespace Tests\Unit\Evaluation;

use Tests\TestCase;
use App\Services\Evaluation\ScoreNormalizer;

class ScoreNormalizerTest extends TestCase
{
    protected ScoreNormalizer $normalizer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->normalizer = new ScoreNormalizer();
    }

    public function test_normalize()
    {
        $score = 50;
        $min = 0;
        $max = 100;
        
        $result = $this->normalizer->normalize($score, $min, $max);
        
        $this->assertEquals(50, $result);
    }

    public function test_z_score()
    {
        $score = 85;
        $mean = 70;
        $stdDev = 10;
        
        $result = $this->normalizer->zScore($score, $mean, $stdDev);
        
        $this->assertEquals(1.5, $result);
    }
}