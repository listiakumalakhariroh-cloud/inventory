<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanRevisiChat extends Model
{
    use HasFactory;

    protected $table = 'laporan_revisi_chats';

    protected $fillable = [
        'id_laporan', 
        'id_user', 
        'pesan'
    ];

    public function laporan()
    {
        return $this->belongsTo(Laporan::class, 'id_laporan');
    }

    // Relasi ke tabel user untuk mengetahui siapa pengirim pesannya
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}