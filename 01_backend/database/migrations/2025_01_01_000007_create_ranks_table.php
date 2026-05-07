<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ranks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->integer('level')->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->index('code');
            $table->index('level');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ranks');
    }
};