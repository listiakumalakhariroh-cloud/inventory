<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\TugasController;
use App\Http\Controllers\PenugasanController;
use App\Http\Controllers\LaporanController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {

    // Dashboard User
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

        // Manajemen Tugas (Versi Lengkap)
        Route::prefix('tugas')->name('admin.tugas.')->group(function () {
            Route::get('/', [TugasController::class, 'index'])->name('index');
            Route::get('/template', [TugasController::class, 'template'])->name('template');
            Route::get('/export', [TugasController::class, 'export'])->name('export');
            Route::get('/tambah', [TugasController::class, 'create'])->name('create');
            Route::post('/store', [TugasController::class, 'store'])->name('store');
            Route::post('/import-process', [TugasController::class, 'importProcess'])->name('importProcess');
            Route::delete('/{kodetugas}', [TugasController::class, 'destroy'])->name('destroy');
            Route::get('/{kodetugas}/edit', [TugasController::class, 'edit'])->name('edit');
            Route::put('/{kodetugas}', [TugasController::class, 'update'])->name('update');
            Route::get('/{kodetugas}', [TugasController::class, 'show'])->name('show');
        });

        // Manajemen Penugasan (Versi Lengkap)
        Route::prefix('penugasan')->name('admin.penugasan.')->group(function () {
            Route::get('/', [PenugasanController::class, 'index'])->name('index');
            Route::get('/template', [PenugasanController::class, 'template'])->name('template');
            Route::get('/export', [PenugasanController::class, 'export'])->name('export');
            Route::get('/tambah', [PenugasanController::class, 'create'])->name('create');
            Route::post('/store', [PenugasanController::class, 'store'])->name('store');
            Route::post('/import-process', [PenugasanController::class, 'importProcess'])->name('importProcess');
            Route::get('/check-existing/{kodetugas}', [PenugasanController::class, 'checkExisting'])->name('checkExisting');
            Route::get('/{id}', [PenugasanController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [PenugasanController::class, 'edit'])->name('edit');
            Route::put('/{id}', [PenugasanController::class, 'update'])->name('update');
            Route::delete('/{id}', [PenugasanController::class, 'destroy'])->name('destroy');
        });

        // Manajemen Laporan & Diskusi Revisi
        Route::get('/manajemen-laporan', [LaporanController::class, 'index'])->name('admin.laporan.index');
        Route::get('/manajemen-laporan/{id}', [LaporanController::class, 'showAdmin'])->name('admin.laporan.show');
        Route::patch('/manajemen-laporan/{id}/status', [LaporanController::class, 'updateStatus'])->name('admin.laporan.status');
        Route::post('/manajemen-laporan/{id}/revisi', [LaporanController::class, 'setRevision'])->name('laporan.set_revision');
        Route::post('/manajemen-laporan/{id}/setujui', [LaporanController::class, 'approve'])->name('laporan.approve');
    });
});
