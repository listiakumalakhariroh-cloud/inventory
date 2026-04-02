<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tugas', function (Blueprint $table) {
            // Menggunakan kodetugas sebagai primary key (string)
            $table->string('kodetugas', 10)->primary();
            $table->string('nama_tugas');
            $table->text('deskripsi');
            $table->string('lampiran')->nullable();
            $table->dateTime('tanggal_mulai');
            $table->dateTime('tanggal_selesai');
            
            // Relasi ke tabel users (admin yang membuat)
            $table->unsignedBigInteger('id_admin');
            $table->foreign('id_admin')->references('id')->on('users')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tugas');
    }
};