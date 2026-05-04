<?php

namespace App\Console\Commands\Setup;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    protected $signature = 'admin:create {--email=admin@example.com} {--name=Admin}';
    protected $description = 'Tạo tài khoản admin';

    public function handle()
    {
        $email = $this->option('email');
        $name = $this->option('name');
        $password = $this->secret('Nhập mật khẩu:');

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => 'admin',
            'status' => 'active',
        ]);

        $this->info("✅ Tài khoản admin {$email} đã được tạo!");
    }
}