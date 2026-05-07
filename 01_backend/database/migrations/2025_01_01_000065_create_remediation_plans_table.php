<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('remediation_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('finding_id')->constrained('audit_findings');
            $table->string('action');
            $table->text('description');
            $table->date('due_date');
            $table->foreignId('assigned_to')->constrained('users');
            $table->string('status')->default('pending');
            $table->timestamp('completed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('finding_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('remediation_plans');
    }
};