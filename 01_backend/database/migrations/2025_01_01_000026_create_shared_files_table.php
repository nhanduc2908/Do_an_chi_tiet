<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shared_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_id')->constrained()->onDelete('cascade');
            $table->foreignId('shared_by')->constrained('users');
            $table->foreignId('shared_with')->nullable()->constrained('users');
            $table->string('email')->nullable();
            $table->enum('permission', ['view', 'download', 'edit']);
            $table->string('share_token')->unique();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('accessed_at')->nullable();
            $table->timestamps();
            
            $table->index('file_id');
            $table->index('share_token');
            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shared_files');
    }
};