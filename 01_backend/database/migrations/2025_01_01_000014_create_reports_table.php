<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('type');
            $table->string('format');
            $table->foreignId('evaluation_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('user_id')->constrained();
            $table->string('file_path');
            $table->integer('file_size')->nullable();
            $table->string('status')->default('pending');
            $table->timestamp('generated_at')->nullable();
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('type');
            $table->index('status');
            $table->index('generated_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};