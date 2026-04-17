<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PengaduanController;
use App\Http\Controllers\TugasController; // Tambahan: Import TugasController

Route::get('/', function () {
    return view('welcome');
});

Route::get('login', function () {
    return view('auth.login');
})->name('login');

Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/admin/dashboard', function () {
        if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'superadmin') {
            abort(403, 'Anda tidak memiliki hak akses ke halaman Administrator ini.');
        }

        return view('admin.dashboardadmin');
    })->name('admin.dashboard');

    // Manajemen Pengaduan
    Route::get('/admin/pengaduan', function () {
        if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'superadmin') {
            abort(403, 'Akses ditolak.');
        }
        return view('admin.manajemenpengaduan');
    })->name('admin.pengaduan');

    Route::get('/admin/pengaduan/tambah', [PengaduanController::class, 'create'])->name('admin.pengaduan.create');
    Route::post('/admin/pengaduan/simpan', [PengaduanController::class, 'store'])->name('admin.pengaduan.store');
    Route::get('/admin/pengaduan', [PengaduanController::class, 'index'])->name('admin.pengaduan');

    // Manajemen Tugas
    Route::get('/admin/tugas', [TugasController::class, 'index'])->name('admin.tugas.index');
    Route::get('/admin/tugas/template', [TugasController::class, 'template'])->name('admin.tugas.template');
    Route::get('/admin/tugas/export', [TugasController::class, 'export'])->name('admin.tugas.export');
    Route::get('/admin/tugas/tambah', [TugasController::class, 'create'])->name('admin.tugas.create');
    Route::post('/admin/tugas/store', [TugasController::class, 'store'])->name('admin.tugas.store');
    Route::delete('/admin/tugas/{kodetugas}', [TugasController::class, 'destroy'])->name('admin.tugas.destroy');
    Route::get('/admin/tugas/{kodetugas}/edit', [TugasController::class, 'edit'])->name('admin.tugas.edit');
    Route::put('/admin/tugas/{kodetugas}', [TugasController::class, 'update'])->name('admin.tugas.update');
    Route::get('/admin/tugas/{kodetugas}', [TugasController::class, 'show'])->name('admin.tugas.show');
    Route::post('/admin/tugas/import-process', [\App\Http\Controllers\TugasController::class, 'importProcess'])->name('admin.tugas.importProcess');
});

