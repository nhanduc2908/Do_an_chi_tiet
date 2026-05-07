<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conditions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('criteria_id')->constrained()->onDelete('cascade');
            $table->decimal('weight', 5, 2)->default(0);
            $table->integer('order')->default(0);
            $table->timestamps();
            
            $table->index('criteria_id');
            $table->index('order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conditions');
    }
};