<?php

namespace App\Services\Evaluation;

use Illuminate\Http\UploadedFile;

class ImportService
{
    public function import(UploadedFile $file, int $domainId, int $userId): array
    {
        $data = $this->parseFile($file);
        $success = 0;
        $failed = 0;
        $errors = [];

        foreach ($data as $row) {
            try {
                // Logic import
                $success++;
            } catch (\Exception $e) {
                $failed++;
                $errors[] = $e->getMessage();
            }
        }

        return ['success' => $success, 'failed' => $failed, 'errors' => $errors];
    }

    protected function parseFile(UploadedFile $file): array 
    { 
        return []; 
    }
}