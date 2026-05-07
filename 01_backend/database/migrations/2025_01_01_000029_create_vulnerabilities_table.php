<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vulnerabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scan_result_id')->constrained()->onDelete('cascade');
            $table->string('cve_id')->nullable();
            $table->string('title');
            $table->enum('severity', ['critical', 'high', 'medium', 'low', 'info']);
            $table->text('description');
            $table->text('recommendation');
            $table->decimal('cvss_score', 3, 1)->nullable();
            $table->string('status')->default('open');
            $table->timestamp('fixed_at')->nullable();
            $table->foreignId('fixed_by')->nullable()->constrained('users');
            $table->timestamps();
            
            $table->index('scan_result_id');
            $table->index('severity');
            $table->index('status');
            $table->index('cve_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vulnerabilities');
    }
};