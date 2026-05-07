<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_change_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('table_name');
            $table->unsignedBigInteger('record_id');
            $table->enum('action', ['create', 'update', 'delete', 'restore']);
            $table->json('old_data')->nullable();
            $table->json('new_data')->nullable();
            $table->json('changes')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamps();
            
            $table->index(['table_name', 'record_id']);
            $table->index('user_id');
            $table->index('created_at');
            $table->index('action');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_change_logs');
    }
};