<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('criteria', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_en')->nullable();
            $table->string('code')->nullable();
            $table->foreignId('domain_id')->constrained()->onDelete('cascade');
            $table->string('criteria_group')->nullable();
            $table->decimal('weight', 5, 2)->default(1);
            $table->enum('priority', ['high', 'medium', 'low'])->default('medium');
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->string('status')->default('active');
            $table->timestamps();
            
            $table->index('domain_id');
            $table->index('code');
            $table->index('status');
            $table->index('priority');
            $table->index(['domain_id', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('criteria');
    }
};