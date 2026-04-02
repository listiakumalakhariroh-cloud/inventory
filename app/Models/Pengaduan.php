<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaduan extends Model
{
    use HasFactory;

    // Jika Anda ingin nama tabelnya persis "pengaduan" tanpa huruf 's' di belakang, 
    // hapus tanda komentar pada baris di bawah ini:
    // protected $table = 'pengaduan';

    protected $fillable = [
        'judul_pengaduan',  // Baru
        'deskripsi',        // Baru
        'foto',
        'tanggal_lapor',
        'latitude',
        'longitude',
        'admin_id',
        'petugas_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
        'catatan_petugas',  // Opsional untuk petugas nanti
    ];

    /**
     * Relasi ke model User (Siapa admin yang mendaftarkan aduan)
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Relasi ke model User (Siapa petugas yang ditugaskan)
     */
    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }
}