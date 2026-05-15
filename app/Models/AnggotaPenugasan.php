<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnggotaPenugasan extends Model
{
    use HasFactory;

    protected $table = 'anggota_penugasans';

    protected $fillable = [
        'id_penugasan',
        'id_user',
        // id_jabatan telah dihapus dari sini
    ];

    /**
     * Relasi ke model Penugasan
     */
    public function penugasan()
    {
        return $this->belongsTo(Penugasan::class, 'id_penugasan');
    }

    /**
     * Relasi ke model User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // Method relasi jabatan() telah dihapus
}