<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PengaduanController;

Route::get('/', function () {
    return view('welcome');
});

// Ganti Auth::routes() dengan rute manual ini:
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
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

    Route::get('/admin/pengaduan', function () {
        if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'superadmin') {
            abort(403, 'Akses ditolak.');
        }
        return view('admin.manajemenpengaduan');
    })->name('admin.pengaduan');

    Route::get('/admin/pengaduan/tambah', [PengaduanController::class, 'create'])->name('admin.pengaduan.create');
    Route::post('/admin/pengaduan/simpan', [PengaduanController::class, 'store'])->name('admin.pengaduan.store');
    Route::get('/admin/pengaduan', [PengaduanController::class, 'index'])->name('admin.pengaduan');

});