<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('penugasan', function (Blueprint $table) {
            $table->id(); // Primary Key

            // Sesuaikan tipe data dengan kodetugas di tabel tugas (biasanya string)
            $table->string('kodetugas', 10);
            $table->foreign('kodetugas')->references('kodetugas')->on('tugas')->onDelete('cascade');

            $table->unsignedBigInteger('id_admin');
            $table->foreign('id_admin')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('id_penerima');
            $table->foreign('id_penerima')->references('id')->on('users')->onDelete('cascade');

            $table->date('batas_waktu_lapor'); // Atau dateTime() jika butuh jam

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penugasan');
    }
};
