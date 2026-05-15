<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Penugasan;
use App\Models\AnggotaPenugasan;
use App\Models\Tugas;
use App\Models\User;
use Carbon\Carbon;

class PenugasanSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ambil beberapa data contoh yang sudah ada dari database
        $tugas = Tugas::first(); 
        $admin = User::where('role', 'admin')->first() ?? User::first();
        $users = User::where('role', '!=', 'admin')->get();

        // Pastikan data referensi ada sebelum membuat seed
        if (!$tugas || !$admin || $users->isEmpty()) {
            $this->command->info('Data referensi (Tugas/User) belum lengkap. Tidak dapat membuat seeder penugasan.');
            return;
        }

        // 2. Buat data Induk Penugasan
        $penugasan = Penugasan::create([
            'kodetugas'         => $tugas->kodetugas,
            'id_admin'          => $admin->id,
            'batas_waktu_lapor' => Carbon::now()->addDays(7), // Batas waktu 7 hari ke depan
        ]);

        // 3. Masukkan Anggota 1 
        if (isset($users[0])) {
            AnggotaPenugasan::create([
                'id_penugasan' => $penugasan->id,
                'id_user'      => $users[0]->id,
            ]);
        }

        // 4. Masukkan Anggota 2 - Jika ada
        if (isset($users[1])) {
            AnggotaPenugasan::create([
                'id_penugasan' => $penugasan->id,
                'id_user'      => $users[1]->id,
            ]);
        }
    }
}