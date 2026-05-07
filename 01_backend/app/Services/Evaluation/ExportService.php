<?php

namespace App\Services\Evaluation;

use App\Models\Evaluation;

class ExportService
{
    public function export(Evaluation $evaluation, string $format): string
    {
        $data = $this->prepareExportData($evaluation);
        return (new ReportGenerator)->generate($evaluation, $format);
    }

    public function bulkExport(array $evaluationIds, string $format): array
    {
        $files = [];
        foreach ($evaluationIds as $id) {
            $files[] = $this->export(Evaluation::find($id), $format);
        }
        return $files;
    }

    protected function prepareExportData(Evaluation $evaluation): array 
    { 
        return []; 
    }
}