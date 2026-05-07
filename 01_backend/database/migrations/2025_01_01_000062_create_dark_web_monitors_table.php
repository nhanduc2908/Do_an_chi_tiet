<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dark_web_monitors', function (Blueprint $table) {
            $table->id();
            $table->string('keyword');
            $table->string('source');
            $table->text('content');
            $table->string('severity')->default('medium');
            $table->timestamp('detected_at');
            $table->boolean('is_reviewed')->default(false);
            $table->timestamps();
            
            $table->index('keyword');
            $table->index('severity');
            $table->index('detected_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dark_web_monitors');
    }
};