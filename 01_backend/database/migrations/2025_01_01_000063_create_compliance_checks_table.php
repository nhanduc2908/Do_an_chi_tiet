<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('compliance_checks', function (Blueprint $table) {
            $table->id();
            $table->string('standard');
            $table->string('status');
            $table->decimal('score', 5, 2);
            $table->json('findings')->nullable();
            $table->json('recommendations')->nullable();
            $table->foreignId('checked_by')->constrained('users');
            $table->timestamp('checked_at')->useCurrent();
            $table->date('next_check_date')->nullable();
            $table->timestamps();
            
            $table->index('standard');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('compliance_checks');
    }
};