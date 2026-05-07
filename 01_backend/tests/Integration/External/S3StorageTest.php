<?php

namespace Tests\Integration\External;

use Tests\TestCase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class S3StorageTest extends TestCase
{
    public function test_s3_connection()
    {
        $disk = Storage::disk('s3');
        $this->assertNotNull($disk);
    }

    public function test_s3_file_upload()
    {
        Storage::disk('s3')->put('test.txt', 'Test content');
        
        $exists = Storage::disk('s3')->exists('test.txt');
        $this->assertTrue($exists);
    }

    public function test_s3_file_download()
    {
        Storage::disk('s3')->put('download.txt', 'Download content');
        
        $content = Storage::disk('s3')->get('download.txt');
        $this->assertEquals('Download content', $content);
    }

    public function test_s3_file_delete()
    {
        Storage::disk('s3')->put('delete.txt', 'To be deleted');
        Storage::disk('s3')->delete('delete.txt');
        
        $exists = Storage::disk('s3')->exists('delete.txt');
        $this->assertFalse($exists);
    }
}