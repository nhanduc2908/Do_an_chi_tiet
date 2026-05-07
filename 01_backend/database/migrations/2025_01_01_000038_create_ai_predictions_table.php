<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_predictions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_id')->constrained()->onDelete('cascade');
            $table->foreignId('model_id')->nullable()->constrained('ai_models');
            $table->decimal('predicted_score', 5, 2);
            $table->decimal('confidence', 5, 4);
            $table->string('trend')->nullable();
            $table->json('data')->nullable();
            $table->boolean('is_used')->default(false);
            $table->timestamps();
            
            $table->index('evaluation_id');
            $table->index('model_id');
            $table->index('is_used');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_predictions');
    }
};