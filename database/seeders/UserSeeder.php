<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Membuat atau mengupdate User biasa
        User::updateOrCreate(
            ['email' => 'user@test.com'], // Kunci pencarian
            [
                'name' => 'Pengguna Percubaan',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'user', // Role user
            ]
        );

        // 2. Menambahkan akun admin1 baru
        User::updateOrCreate(
            ['email' => 'admin1@test.com'], // Silakan ubah email jika diperlukan
            [
                'name' => 'Admin 1',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'), // Silakan ubah password jika diperlukan
                'role' => 'admin', // Role admin
            ]
        );
    }
}