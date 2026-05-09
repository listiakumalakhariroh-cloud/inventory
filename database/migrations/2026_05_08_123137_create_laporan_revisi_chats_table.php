<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_revisi_chats', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('id_laporan');
            $table->foreign('id_laporan')->references('id')->on('laporans')->onDelete('cascade');

            // ID User yang mengirim pesan (Bisa Admin, bisa Anggota)
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');

            $table->text('pesan');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_revisi_chats');
    }
};