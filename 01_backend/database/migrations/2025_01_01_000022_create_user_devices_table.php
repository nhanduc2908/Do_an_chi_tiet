<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('device_id')->unique();
            $table->string('device_name');
            $table->string('device_type');
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('session_token')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_active')->default(true);
            $table->string('push_token')->nullable();
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('device_id');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_devices');
    }
};