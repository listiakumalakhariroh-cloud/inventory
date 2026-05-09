<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\TugasController;
use App\Http\Controllers\PenugasanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PengaduanController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Rute Penugasan User
    Route::get('/penugasan', [PenugasanController::class, 'indexUser'])->name('penugasan.index');
    Route::get('/penugasan/{id}', [PenugasanController::class, 'show'])->name('penugasan.show');

    // Rute Laporan User
    Route::get('/laporan', [LaporanController::class, 'indexUser'])->name('laporan.index');
    Route::get('/laporan/buat/{id_penugasan}', [LaporanController::class, 'create'])->name('laporan.create');
    Route::post('/laporan/simpan', [LaporanController::class, 'store'])->name('laporan.store');
    Route::get('/laporan/{id}', [LaporanController::class, 'show'])->name('laporan.show');
    Route::post('/laporan/{id}/chat', [LaporanController::class, 'storeChat'])->name('laporan.chat.store');

    // Grup Admin (Khusus Admin & Superadmin)
    Route::middleware(['role:admin,superadmin'])->prefix('admin')->group(function () {
        
        Route::get('/dashboard', function () {
            return view('admin.dashboardadmin');
        })->name('admin.dashboard');

        // Manajemen Tugas
        Route::get('/tugas', [TugasController::class, 'index'])->name('admin.tugas.index');
        Route::get('/tugas/tambah', [TugasController::class, 'create'])->name('admin.tugas.create');
        Route::post('/tugas/simpan', [TugasController::class, 'store'])->name('admin.tugas.store');
        Route::get('/tugas/{id}/edit', [TugasController::class, 'edit'])->name('admin.tugas.edit');
        Route::put('/tugas/{id}/update', [TugasController::class, 'update'])->name('admin.tugas.update');
        Route::delete('/tugas/{id}/hapus', [TugasController::class, 'destroy'])->name('admin.tugas.destroy');
        Route::get('/tugas/import', [TugasController::class, 'showImport'])->name('admin.tugas.import');
        Route::post('/tugas/import/proses', [TugasController::class, 'processImport'])->name('admin.tugas.import.proses');
        

        // Manajemen Penugasan
        Route::get('/penugasan-kerja', [PenugasanController::class, 'index'])->name('admin.penugasan.index');
        Route::get('/penugasan-kerja/tambah', [PenugasanController::class, 'create'])->name('admin.penugasan.create');
        Route::post('/penugasan-kerja/simpan', [PenugasanController::class, 'store'])->name('admin.penugasan.store');
        Route::get('/penugasan-kerja/{id}/detail', [PenugasanController::class, 'showAdmin'])->name('admin.penugasan.show');
        Route::delete('/penugasan-kerja/{id}/hapus', [PenugasanController::class, 'destroy'])->name('admin.penugasan.destroy');

        // Manajemen Laporan
        Route::get('/manajemen-laporan', [LaporanController::class, 'index'])->name('admin.laporan.index');
        Route::get('/manajemen-laporan/{id}', [LaporanController::class, 'showAdmin'])->name('admin.laporan.show');
        Route::patch('/manajemen-laporan/{id}/status', [LaporanController::class, 'updateStatus'])->name('admin.laporan.status');
        Route::post('/manajemen-laporan/{id}/revisi', [LaporanController::class, 'setRevision'])->name('laporan.set_revision');
        Route::post('/manajemen-laporan/{id}/setujui', [LaporanController::class, 'approve'])->name('laporan.approve');
        
        // Manajemen Pengaduan
        Route::get('/pengaduan', [PengaduanController::class, 'index'])->name('admin.pengaduan.index');
        Route::get('/pengaduan/tambah', [PengaduanController::class, 'create'])->name('admin.pengaduan.create');
        Route::post('/pengaduan/simpan', [PengaduanController::class, 'store'])->name('admin.pengaduan.store');
    });
});