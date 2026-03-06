<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Pengguna Percubaan',
            'email' => 'user@test.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);
    }
}