<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Jalankan pangkalan data seeder.
     */
    public function run(): void
    {
        // Mencipta satu pengguna spesifik
        User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'), // Pastikan kata laluan di-hash
        ]);

        // Atau menggunakan Factory untuk mencipta 10 pengguna rawak
        // User::factory(10)->create();
    }
}