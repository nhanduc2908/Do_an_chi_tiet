<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendor_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
            $table->foreignId('assessor_id')->constrained('users');
            $table->decimal('score', 5, 2);
            $table->json('findings')->nullable();
            $table->text('recommendations')->nullable();
            $table->timestamp('assessed_at')->useCurrent();
            $table->date('next_assessment_date')->nullable();
            $table->timestamps();
            
            $table->index('vendor_id');
            $table->index('assessor_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_assessments');
    }
};