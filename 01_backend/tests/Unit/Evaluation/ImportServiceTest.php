<?php

namespace Tests\Unit\Evaluation;

use Tests\TestCase;
use App\Services\Evaluation\ImportService;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ImportServiceTest extends TestCase
{
    protected ImportService $importService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->importService = new ImportService();
    }

    public function test_import_csv_file()
    {
        $content = "title,score\nTest,85\nDemo,90";
        $file = UploadedFile::fake()->createWithContent('test.csv', $content);
        
        $result = $this->importService->import($file, 1, 1);
        
        $this->assertArrayHasKey('success', $result);
    }
}