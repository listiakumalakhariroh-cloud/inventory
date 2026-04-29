<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnggotaPenugasan extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_penugasan',
        'id_user',
        'id_jabatan'
    ];

    public function penugasan()
    {
        return $this->belongsTo(Penugasan::class, 'id_penugasan');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'id_jabatan');
    }
}