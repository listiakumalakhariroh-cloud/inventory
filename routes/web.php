<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

// 1. Menampilkan halaman login (welcome.blade.php)
Route::get('/', function () {
    return view('welcome');
})->name('login');

// 2. Menangani proses pengiriman data login (POST)
Route::post('/login', [LoginController::class, 'authenticate']);

// 3. Menangani proses logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// 4. Halaman Dashboard (hanya bisa diakses jika sudah login)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard'); 
    })->name('dashboard');
});