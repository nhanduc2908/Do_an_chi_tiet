<?php

namespace App\Services\Evaluation;

use App\Models\Evaluation;

class ReportGenerator
{
    public function generate(Evaluation $evaluation, string $format = 'pdf'): string
    {
        $data = $this->prepareData($evaluation);
        
        return match($format) {
            'pdf' => $this->generatePdf($data),
            'excel' => $this->generateExcel($data),
            'csv' => $this->generateCsv($data),
            default => json_encode($data),
        };
    }

    protected function prepareData(Evaluation $evaluation): array
    {
        return [
            'id' => $evaluation->id,
            'title' => $evaluation->title,
            'percentage' => $evaluation->percentage,
            'status' => $evaluation->status,
            'domain' => $evaluation->domain->name,
            'company' => $evaluation->company->name,
            'evaluator' => $evaluation->user->name,
            'criteria_scores' => $evaluation->details->map(fn($d) => [
                'criteria' => $d->criteria->name,
                'score' => $d->score,
            ]),
        ];
    }

    protected function generatePdf(array $data): string 
    { 
        return storage_path("reports/report_{$data['id']}.pdf"); 
    }

    protected function generateExcel(array $data): string 
    { 
        return storage_path("reports/report_{$data['id']}.xlsx"); 
    }

    protected function generateCsv(array $data): string 
    { 
        return storage_path("reports/report_{$data['id']}.csv"); 
    }
}