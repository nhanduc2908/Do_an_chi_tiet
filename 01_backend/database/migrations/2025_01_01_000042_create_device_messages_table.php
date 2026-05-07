<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('device_messages', function (Blueprint $table) {
            $table->id();
            $table->string('from_device_id');
            $table->string('to_device_id');
            $table->string('message_type');
            $table->json('payload');
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
            
            $table->index('from_device_id');
            $table->index('to_device_id');
            $table->index('is_read');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('device_messages');
    }
};