<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('risk_treatments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('risk_assessment_id')->constrained()->onDelete('cascade');
            $table->string('action');
            $table->text('description');
            $table->date('due_date');
            $table->string('status')->default('pending');
            $table->foreignId('assigned_to')->constrained('users');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            $table->index('risk_assessment_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('risk_treatments');
    }
};