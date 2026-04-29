<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jabatan;

class JabatanSeeder extends Seeder
{
    public function run(): void
    {
        $jabatans = [
            ['nama_jabatan' => 'Ketua Tim'],
            ['nama_jabatan' => 'Sekretaris'],
            ['nama_jabatan' => 'Anggota Eksekutor'],
            ['nama_jabatan' => 'Pengawas']
        ];

        foreach ($jabatans as $jabatan) {
            Jabatan::create($jabatan);
        }
    }
}