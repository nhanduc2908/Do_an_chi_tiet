<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scan_results', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('target');
            $table->string('status')->default('pending');
            $table->json('result')->nullable();
            $table->json('vulnerabilities')->nullable();
            $table->foreignId('user_id')->constrained();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->json('summary')->nullable();
            $table->timestamps();
            
            $table->index('type');
            $table->index('status');
            $table->index('user_id');
            $table->index('started_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scan_results');
    }
};