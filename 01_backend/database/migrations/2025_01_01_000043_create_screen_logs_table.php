<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('screen_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('device_id')->nullable();
            $table->integer('screen_width');
            $table->integer('screen_height');
            $table->float('device_pixel_ratio')->default(1);
            $table->enum('orientation', ['portrait', 'landscape'])->default('portrait');
            $table->text('user_agent')->nullable();
            $table->string('session_id')->nullable();
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('device_id');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('screen_logs');
    }
};