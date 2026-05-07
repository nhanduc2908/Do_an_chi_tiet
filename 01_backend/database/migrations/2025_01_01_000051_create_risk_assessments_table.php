<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('risk_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained()->onDelete('cascade');
            $table->string('threat');
            $table->string('vulnerability');
            $table->integer('likelihood')->min(1)->max(5);
            $table->integer('impact')->min(1)->max(5);
            $table->integer('risk_score');
            $table->string('risk_level');
            $table->text('mitigation')->nullable();
            $table->foreignId('owner_id')->nullable()->constrained('users');
            $table->foreignId('assessed_by')->constrained('users');
            $table->timestamp('assessed_at')->useCurrent();
            $table->timestamps();
            
            $table->index('asset_id');
            $table->index('risk_level');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('risk_assessments');
    }
};