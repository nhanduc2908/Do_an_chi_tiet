<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('role')->default('dev');
            $table->string('avatar')->nullable();
            $table->string('status')->default('active');
            $table->foreignId('company_id')->nullable()->constrained();
            $table->foreignId('department_id')->nullable()->constrained();
            $table->foreignId('team_id')->nullable()->constrained();
            $table->foreignId('rank_id')->nullable()->constrained();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip')->nullable();
            $table->rememberToken();
            $table->timestamps();
            
            $table->index('role');
            $table->index('status');
            $table->index('company_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};