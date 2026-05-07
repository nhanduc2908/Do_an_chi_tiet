<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('evaluation_id')->constrained();
            $table->integer('rating')->min(1)->max(5);
            $table->text('comment')->nullable();
            $table->string('type')->default('general');
            $table->boolean('is_anonymous')->default(false);
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('evaluation_id');
            $table->index('rating');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
    }
};