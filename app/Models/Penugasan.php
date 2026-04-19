<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penugasan extends Model
{
    use HasFactory;

    // Menentukan nama tabel secara eksplisit (opsional tapi disarankan)
    protected $table = 'penugasan';

    // Kolom yang dapat diisi secara massal (Mass Assignment)
    protected $fillable = [
        'kodetugas',
        'id_admin',
        'id_penerima', // Pengganti id_yang_ditugaskan
        'batas_waktu_lapor', // Pengganti tanggalmaksimaldilaporkan
    ];

    /**
     * Relasi ke model Tugas.
     * Karena primary key di tabel tugas adalah 'kodetugas', kita harus mendefinisikannya secara spesifik.
     */
    public function tugas()
    {
        return $this->belongsTo(Tugas::class, 'kodetugas', 'kodetugas');
    }

    /**
     * Relasi ke model User sebagai Admin (Pemberi Tugas)
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'id_admin', 'id');
    }

    /**
     * Relasi ke model User sebagai Penerima Tugas (Yang ditugaskan)
     */
    public function penerima()
    {
        return $this->belongsTo(User::class, 'id_penerima', 'id');
    }
}