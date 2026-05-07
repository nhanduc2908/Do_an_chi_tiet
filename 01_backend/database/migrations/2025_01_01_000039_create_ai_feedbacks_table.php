<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prediction_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained();
            $table->boolean('is_accurate')->default(false);
            $table->decimal('actual_score', 5, 2)->nullable();
            $table->text('feedback')->nullable();
            $table->boolean('used_for_retraining')->default(false);
            $table->timestamps();
            
            $table->index('prediction_id');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_feedbacks');
    }
};