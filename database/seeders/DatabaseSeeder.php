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
        // Membuat atau mengupdate Admin
        User::updateOrCreate(
            ['email' => 'superadmin@example.com'], // Kunci pencarian
            [
                'name' => 'Admin Utama',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'role' => 'superadmin', // Menambahkan role admin
            ]
        );

        // Memanggil UserSeeder agar ikut dijalankan
        $this->call([
            UserSeeder::class,
        ]);
    }
}