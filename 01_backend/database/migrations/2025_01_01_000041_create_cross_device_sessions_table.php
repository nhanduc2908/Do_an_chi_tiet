<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cross_device_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('session_token')->unique();
            $table->json('connected_devices');
            $table->boolean('active')->default(true);
            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('session_token');
            $table->index('active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cross_device_sessions');
    }
};