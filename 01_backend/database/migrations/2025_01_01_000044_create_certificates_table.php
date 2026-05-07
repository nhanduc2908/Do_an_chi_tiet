<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('domain')->unique();
            $table->string('certificate_path');
            $table->string('private_key_path');
            $table->timestamp('expires_at');
            $table->foreignId('created_by')->constrained('users');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index('domain');
            $table->index('expires_at');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};