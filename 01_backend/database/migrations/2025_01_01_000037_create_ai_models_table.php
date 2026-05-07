<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_models', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->string('version');
            $table->string('path');
            $table->decimal('accuracy', 5, 4)->nullable();
            $table->decimal('precision', 5, 4)->nullable();
            $table->decimal('recall', 5, 4)->nullable();
            $table->decimal('f1_score', 5, 4)->nullable();
            $table->string('status')->default('active');
            $table->timestamp('trained_at')->nullable();
            $table->integer('training_data_size')->nullable();
            $table->timestamps();
            
            $table->index('type');
            $table->index('status');
            $table->index('version');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_models');
    }
};