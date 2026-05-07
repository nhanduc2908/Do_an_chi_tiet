<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
            $table->string('contract_number')->unique();
            $table->string('title');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('value', 15, 2)->nullable();
            $table->string('status')->default('active');
            $table->string('file_path')->nullable();
            $table->timestamps();
            
            $table->index('vendor_id');
            $table->index('contract_number');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};