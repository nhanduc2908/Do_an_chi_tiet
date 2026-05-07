<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Admin User', 'email' => 'admin@example.com', 'role' => 'admin', 'password' => Hash::make('password')],
            ['name' => 'Dev User', 'email' => 'dev@example.com', 'role' => 'dev', 'password' => Hash::make('password')],
            ['name' => 'BA User', 'email' => 'ba@example.com', 'role' => 'ba', 'password' => Hash::make('password')],
            ['name' => 'DA User', 'email' => 'da@example.com', 'role' => 'da', 'password' => Hash::make('password')],
            ['name' => 'HR User', 'email' => 'hr@example.com', 'role' => 'hr', 'password' => Hash::make('password')],
            ['name' => 'QA User', 'email' => 'qa@example.com', 'role' => 'qa', 'password' => Hash::make('password')],
            ['name' => 'SecOps User', 'email' => 'secops@example.com', 'role' => 'secops', 'password' => Hash::make('password')],
            ['name' => 'Auditor User', 'email' => 'auditor@example.com', 'role' => 'auditor', 'password' => Hash::make('password')],
            ['name' => 'Manager User', 'email' => 'manager@example.com', 'role' => 'manager', 'password' => Hash::make('password')],
            ['name' => 'CISO User', 'email' => 'ciso@example.com', 'role' => 'ciso', 'password' => Hash::make('password')],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(['email' => $user['email']], $user);
        }
    }
}