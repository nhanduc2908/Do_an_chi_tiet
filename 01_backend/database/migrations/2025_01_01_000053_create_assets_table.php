<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->string('classification');
            $table->foreignId('owner_id')->nullable()->constrained('users');
            $table->string('location')->nullable();
            $table->decimal('value', 15, 2)->nullable();
            $table->string('status')->default('active');
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->index('type');
            $table->index('classification');
            $table->index('owner_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};