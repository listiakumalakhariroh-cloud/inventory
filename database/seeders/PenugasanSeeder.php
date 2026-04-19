<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Penugasan;
use App\Models\Tugas;
use App\Models\User;
use Carbon\Carbon;

class PenugasanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil data acak untuk relasi (Pastikan tabel Users dan Tugas sudah ada isinya)
        $admin = User::whereIn('role', ['admin', 'superadmin'])->first() ?? User::first();
        $penerima = User::where('role', '!=', 'admin')->first() ?? User::orderBy('id', 'desc')->first();
        
        // Ambil beberapa data tugas
        $tugasList = Tugas::take(3)->get();

        if ($tugasList->isEmpty() || !$admin || !$penerima) {
            $this->command->info('Seeder Penugasan dilewati: Pastikan ada minimal 1 Tugas dan 2 User di database.');
            return;
        }

        // Loop untuk membuat beberapa dummy data penugasan
        foreach ($tugasList as $index => $tugas) {
            Penugasan::create([
                'kodetugas'         => $tugas->kodetugas,
                'id_admin'          => $admin->id,
                'id_penerima'       => $penerima->id,
                // Mengatur batas waktu lapor (contoh: 3 sampai 7 hari dari sekarang)
                'batas_waktu_lapor' => Carbon::now()->addDays(3 + $index)->format('Y-m-d'),
            ]);
        }

        $this->command->info('Data Penugasan berhasil di-seed!');
    }
}