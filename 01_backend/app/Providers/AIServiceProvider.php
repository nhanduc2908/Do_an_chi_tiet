vr<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\AI\AIScoringEngine;
use App\Services\AI\PredictiveAnalytics;
use App\Services\AI\AnomalyDetector;
use App\Services\AI\RecommendationAI;
use App\Services\AI\NLPAnalyzer;
use App\Services\AI\RiskClassifier;
use App\Services\AI\ThreatIntelligenceAI;
use App\Services\AI\ReportSummarizer;
use App\Services\AI\ModelManager;
use App\Services\AI\TrainingPipeline;
use App\Services\Evaluation\HybridScoringService;
use App\Services\Evaluation\AIScoringService;

class AIServiceProvider extends ServiceProvider
{
    protected array $aiModels = [
        'security_scoring' => [
            'version' => '2.1.0',
            'path' => 'models/security_scoring_model.pkl',
            'accuracy' => 0.94,
        ],
        'vulnerability_detector' => [
            'version' => '1.8.0',
            'path' => 'models/vulnerability_detector.h5',
            'accuracy' => 0.89,
        ],
        'anomaly_detector' => [
            'version' => '1.2.0',
            'path' => 'models/anomaly_detector.pkl',
            'accuracy' => 0.91,
        ],
        'risk_classifier' => [
            'version' => '2.0.0',
            'path' => 'models/risk_classifier.joblib',
            'accuracy' => 0.87,
        ],
        'recommendation_engine' => [
            'version' => '1.5.0',
            'path' => 'models/recommendation_engine.pkl',
            'accuracy' => 0.83,
        ],
        'nlp_analyzer' => [
            'version' => '1.0.0',
            'path' => 'models/nlp_analyzer.bin',
            'accuracy' => 0.88,
        ],
        'threat_intel' => [
            'version' => '2.1.0',
            'path' => 'models/threat_intel_model.onnx',
            'accuracy' => 0.92,
        ],
    ];

    public function register(): void
    {
        // AI Scoring Engine
        $this->app->singleton(AIScoringEngine::class, function ($app) {
            return new AIScoringEngine();
        });

        // Predictive Analytics
        $this->app->singleton(PredictiveAnalytics::class, function ($app) {
            return new PredictiveAnalytics();
        });

        // Anomaly Detector
        $this->app->singleton(AnomalyDetector::class, function ($app) {
            return new AnomalyDetector();
        });

        // Recommendation AI
        $this->app->singleton(RecommendationAI::class, function ($app) {
            return new RecommendationAI();
        });

        // NLP Analyzer
        $this->app->singleton(NLPAnalyzer::class, function ($app) {
            return new NLPAnalyzer();
        });

        // Risk Classifier
        $this->app->singleton(RiskClassifier::class, function ($app) {
            return new RiskClassifier();
        });

        // Threat Intelligence AI
        $this->app->singleton(ThreatIntelligenceAI::class, function ($app) {
            return new ThreatIntelligenceAI();
        });

        // Report Summarizer
        $this->app->singleton(ReportSummarizer::class, function ($app) {
            return new ReportSummarizer();
        });

        // Model Manager
        $this->app->singleton(ModelManager::class, function ($app) {
            return new ModelManager($this->aiModels);
        });

        // Training Pipeline
        $this->app->singleton(TrainingPipeline::class, function ($app) {
            return new TrainingPipeline();
        });

        // AI Scoring Service
        $this->app->singleton(AIScoringService::class, function ($app) {
            return new AIScoringService();
        });

        // Hybrid Scoring Service (AI + Rule)
        $this->app->singleton(HybridScoringService::class, function ($app) {
            return new HybridScoringService(
                $app->make(AIScoringEngine::class),
                $app->make(\App\Services\Evaluation\ScoringService::class)
            );
        });
    }

    public function boot(): void
    {
        // Load AI models on boot
        $this->loadAIModels();
        
        // Register AI commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                \App\Console\Commands\AI\TrainAIModels::class,
                \App\Console\Commands\AI\UpdateAIModels::class,
                \App\Console\Commands\AI\EvaluateAIModels::class,
                \App\Console\Commands\AI\RunAIPredictions::class,
            ]);
        }
    }

    protected function loadAIModels(): void
    {
        $modelManager = $this->app->make(ModelManager::class);
        
        foreach ($this->aiModels as $name => $config) {
            if ($modelManager->isModelAvailable($name)) {
                $modelManager->loadModel($name);
                logger()->info("AI Model {$name} loaded successfully", [
                    'version' => $config['version'],
                    'accuracy' => $config['accuracy'],
                ]);
            } else {
                logger()->warning("AI Model {$name} not available", [
                    'path' => $config['path'],
                ]);
            }
        }
    }
}