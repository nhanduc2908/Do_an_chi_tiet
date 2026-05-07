<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rate_limits', function (Blueprint $table) {
            $table->id();
            $table->string('key')->index();
            $table->integer('attempts')->default(0);
            $table->integer('max_attempts')->default(60);
            $table->integer('decay_minutes')->default(1);
            $table->timestamp('blocked_at')->nullable();
            $table->string('ip_address')->nullable();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->timestamps();
            
            $table->index('key');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rate_limits');
    }
};