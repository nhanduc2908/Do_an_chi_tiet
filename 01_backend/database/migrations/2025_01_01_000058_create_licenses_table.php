<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('licenses', function (Blueprint $table) {
            $table->id();
            $table->string('license_key')->unique();
            $table->string('product_name');
            $table->string('version');
            $table->integer('max_users');
            $table->timestamp('expires_at');
            $table->boolean('is_active')->default(true);
            $table->foreignId('company_id')->constrained();
            $table->timestamps();
            
            $table->index('license_key');
            $table->index('expires_at');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('licenses');
    }
};