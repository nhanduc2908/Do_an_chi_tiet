<?php

namespace App\Services\AI;

use App\Models\Evaluation;
use Illuminate\Support\Facades\Http;

class NLPAnalyzer
{
    protected string $aiApiUrl;

    public function __construct()
    {
        $this->aiApiUrl = config('ai.api_url', 'http://ai-service:5000');
    }

    public function analyzeSentiment(string $text): array
    {
        $response = Http::post($this->aiApiUrl . '/api/v1/nlp/sentiment', [
            'text' => $text,
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        return $this->simpleSentimentAnalysis($text);
    }

    public function summarizeEvaluation(Evaluation $evaluation): string
    {
        $comments = $evaluation->details()
            ->whereNotNull('notes')
            ->pluck('notes')
            ->implode(' ');

        $response = Http::post($this->aiApiUrl . '/api/v1/nlp/summarize', [
            'text' => $comments,
            'max_length' => 200,
        ]);

        if ($response->successful()) {
            return $response->json('summary');
        }

        $score = $evaluation->percentage;
        if ($score >= 80) {
            return "Đánh giá tốt, hệ thống bảo mật đáp ứng yêu cầu.";
        } elseif ($score >= 60) {
            return "Đánh giá trung bình, cần cải thiện một số điểm yếu.";
        } else {
            return "Đánh giá thấp, cần khắc phục ngay các lỗ hổng bảo mật.";
        }
    }

    public function extractEntities(string $text): array
    {
        $response = Http::post($this->aiApiUrl . '/api/v1/nlp/entities', [
            'text' => $text,
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        return $this->simpleEntityExtraction($text);
    }

    public function analyzeFeedbackTrend(array $feedbacks): array
    {
        $sentiments = [];
        foreach ($feedbacks as $feedback) {
            $sentiment = $this->analyzeSentiment($feedback['content']);
            $sentiments[] = $sentiment['sentiment'];
        }

        $positive = count(array_filter($sentiments, fn($s) => $s === 'positive'));
        $negative = count(array_filter($sentiments, fn($s) => $s === 'negative'));
        $neutral = count($sentiments) - $positive - $negative;

        return [
            'positive' => $positive,
            'negative' => $negative,
            'neutral' => $neutral,
            'total' => count($sentiments),
            'positive_rate' => count($sentiments) > 0 ? round($positive / count($sentiments) * 100, 2) : 0,
        ];
    }

    protected function simpleSentimentAnalysis(string $text): array
    {
        $positiveWords = ['tốt', 'đẹp', 'hiệu quả', 'hay', 'tuyệt', 'ổn', 'hài lòng'];
        $negativeWords = ['tệ', 'kém', 'chậm', 'lỗi', 'hỏng', 'không tốt', 'thất vọng'];
        
        $positiveCount = 0;
        $negativeCount = 0;
        
        foreach ($positiveWords as $word) {
            $positiveCount += substr_count(strtolower($text), $word);
        }
        foreach ($negativeWords as $word) {
            $negativeCount += substr_count(strtolower($text), $word);
        }
        
        if ($positiveCount > $negativeCount) {
            return ['sentiment' => 'positive', 'confidence' => 0.7];
        } elseif ($negativeCount > $positiveCount) {
            return ['sentiment' => 'negative', 'confidence' => 0.7];
        }
        return ['sentiment' => 'neutral', 'confidence' => 0.6];
    }

    protected function simpleEntityExtraction(string $text): array
    {
        $entities = [];
        $patterns = [
            'email' => '/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/',
            'ip' => '/\b(?:\d{1,3}\.){3}\d{1,3}\b/',
            'date' => '/\d{1,2}[\/\-]\d{1,2}[\/\-]\d{2,4}/',
        ];
        
        foreach ($patterns as $type => $pattern) {
            preg_match_all($pattern, $text, $matches);
            if (!empty($matches[0])) {
                $entities[$type] = $matches[0];
            }
        }
        
        return ['entities' => $entities];
    }
}