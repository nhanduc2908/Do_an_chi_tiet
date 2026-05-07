<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_findings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_id')->constrained('compliance_checks');
            $table->string('title');
            $table->text('description');
            $table->string('severity');
            $table->text('recommendation')->nullable();
            $table->string('status')->default('open');
            $table->foreignId('assigned_to')->nullable()->constrained('users');
            $table->timestamps();
            
            $table->index('audit_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_findings');
    }
};