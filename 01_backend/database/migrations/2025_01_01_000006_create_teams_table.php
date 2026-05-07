<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->foreignId('department_id')->constrained()->onDelete('cascade');
            $table->foreignId('lead_id')->nullable()->constrained('users');
            $table->timestamps();
            
            $table->index('department_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};