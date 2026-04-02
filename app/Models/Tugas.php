<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    use HasFactory;

    // Menentukan nama tabel jika diperlukan (opsional jika nama tabel sudah 'tugas')
    protected $table = 'tugas';

    // Konfigurasi Primary Key Custom
    protected $primaryKey = 'kodetugas';
    public $incrementing = false;
    protected $keyType = 'string';

    // Kolom yang boleh diisi (Mass Assignment)
    protected $fillable = [
        'kodetugas',
        'nama_tugas',
        'deskripsi',
        'lampiran',
        'tanggal_mulai',
        'tanggal_selesai',
        'id_admin',
    ];

    /**
     * Relasi ke model User (Admin)
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'id_admin', 'id');
    }
}