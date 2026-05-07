<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('threat_intel', function (Blueprint $table) {
            $table->id();
            $table->string('indicator');
            $table->string('type');
            $table->string('severity');
            $table->text('description');
            $table->json('sources')->nullable();
            $table->timestamp('first_seen')->nullable();
            $table->timestamp('last_seen')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index('indicator');
            $table->index('type');
            $table->index('severity');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('threat_intel');
    }
};