<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('siem_alerts', function (Blueprint $table) {
            $table->id();
            $table->string('alert_id')->unique();
            $table->string('source');
            $table->string('severity');
            $table->string('rule_name');
            $table->text('description');
            $table->json('raw_data')->nullable();
            $table->timestamp('occurred_at');
            $table->boolean('is_resolved')->default(false);
            $table->timestamps();
            
            $table->index('alert_id');
            $table->index('severity');
            $table->index('occurred_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siem_alerts');
    }
};