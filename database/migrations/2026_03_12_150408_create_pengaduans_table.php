<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaduans', function (Blueprint $table) {
            $table->id();
            
            // Data Laporan Awal (Diperbarui)
            $table->string('judul_pengaduan'); // Menjadi judul singkat
            $table->text('deskripsi');         // Menjadi penjelasan detail
            $table->string('foto')->nullable(); 
            $table->date('tanggal_lapor');
            
            // Lokasi GPS
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
            // Relasi ke tabel Users
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('petugas_id')->nullable()->constrained('users')->onDelete('set null');
            
            // Progres & Waktu
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            
            // Status 
            $table->enum('status', ['menunggu', 'ditugaskan', 'diproses', 'selesai', 'dibatalkan'])->default('menunggu');
            
            // (Opsional) Jika nanti petugas tetap butuh mengisi catatan setelah selesai bertugas
            $table->text('catatan_petugas')->nullable(); 
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaduans');
    }
};