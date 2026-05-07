<?php

namespace Tests\Unit\Evaluation;

use Tests\TestCase;
use App\Models\Evaluation;
use App\Services\Evaluation\ExportService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExportServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ExportService $exportService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->exportService = new ExportService();
    }

    public function test_export_single_evaluation()
    {
        $evaluation = Evaluation::factory()->create();
        $file = $this->exportService->export($evaluation, 'csv');
        
        $this->assertStringEndsWith('.csv', $file);
    }
}