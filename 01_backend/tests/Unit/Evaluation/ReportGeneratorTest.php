<?php

namespace Tests\Unit\Evaluation;

use Tests\TestCase;
use App\Models\Evaluation;
use App\Services\Evaluation\ReportGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReportGeneratorTest extends TestCase
{
    use RefreshDatabase;

    protected ReportGenerator $generator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->generator = new ReportGenerator();
    }

    public function test_generate_pdf_report()
    {
        $evaluation = Evaluation::factory()->create();
        $file = $this->generator->generate($evaluation, 'pdf');
        
        $this->assertStringEndsWith('.pdf', $file);
    }

    public function test_generate_excel_report()
    {
        $evaluation = Evaluation::factory()->create();
        $file = $this->generator->generate($evaluation, 'excel');
        
        $this->assertStringEndsWith('.xlsx', $file);
    }
}