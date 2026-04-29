<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Penugasan;
use App\Models\AnggotaPenugasan;
use App\Models\Tugas;
use App\Models\User;
use App\Models\Jabatan;
use Carbon\Carbon;

class PenugasanSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ambil beberapa data contoh yang sudah ada dari database
        $tugas = Tugas::first(); 
        $admin = User::where('role', 'admin')->first() ?? User::first();
        $users = User::where('role', '!=', 'admin')->get();
        $jabatanKetua = Jabatan::where('nama_jabatan', 'Ketua Tim')->first();
        $jabatanAnggota = Jabatan::where('nama_jabatan', 'Anggota Eksekutor')->first();

        // Pastikan data referensi ada sebelum membuat seed
        if (!$tugas || !$admin || $users->isEmpty() || !$jabatanKetua) {
            $this->command->info('Data referensi (Tugas/User/Jabatan) belum lengkap. Tidak dapat membuat seeder penugasan.');
            return;
        }

        // 2. Buat data Induk Penugasan (Tanpa id_penerima)
        $penugasan = Penugasan::create([
            'kodetugas'         => $tugas->kodetugas,
            'id_admin'          => $admin->id,
            'batas_waktu_lapor' => Carbon::now()->addDays(7), // Batas waktu 7 hari ke depan
        ]);

        // 3. Masukkan Anggota 1 (Sebagai Ketua Tim)
        if (isset($users[0])) {
            AnggotaPenugasan::create([
                'id_penugasan' => $penugasan->id,
                'id_user'      => $users[0]->id,
                'id_jabatan'   => $jabatanKetua->id,
            ]);
        }

        // 4. Masukkan Anggota 2 (Sebagai Anggota Eksekutor) - Jika ada
        if (isset($users[1]) && $jabatanAnggota) {
            AnggotaPenugasan::create([
                'id_penugasan' => $penugasan->id,
                'id_user'      => $users[1]->id,
                'id_jabatan'   => $jabatanAnggota->id,
            ]);
        }
    }
}