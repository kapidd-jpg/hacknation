<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Guru\DashboardController as GuruDashboardController;
use App\Http\Controllers\Guru\KelasController as GuruKelasController;
use App\Http\Controllers\Guru\MateriController as GuruMateriController;
use App\Http\Controllers\Guru\PaketController as GuruPaketController;
use App\Http\Controllers\Guru\SiswaController as GuruSiswaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PintarKuy — minimalis: 2 role (Siswa + Guru) + auth database
|--------------------------------------------------------------------------
*/

// ---------- Publik ----------
Route::get('/', fn () => view('halaman.landing'))->name('home');

Route::view('/tentang', 'halaman.tentang')->name('about');
Route::view('/kelas', 'halaman.kelas')->name('classes');
Route::view('/kontak', 'halaman.kontak')->name('contact');

// ---------- Auth ----------
Route::middleware('guest')->group(function () {
    Route::get('/masuk', [LoginController::class, 'create'])->name('login');
    Route::post('/masuk', [LoginController::class, 'store'])->middleware('throttle:5,1')->name('login.attempt');
    Route::get('/daftar', [RegisterController::class, 'create'])->name('register');
    Route::post('/daftar', [RegisterController::class, 'store'])->middleware('throttle:5,1')->name('register.attempt');
});

Route::post('/logout', LogoutController::class)->name('logout');

// ---------- Dashboard Siswa (perlu login) ----------
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/kelas', [DashboardController::class, 'kelas'])->name('dashboard.kelas');
    Route::get('/dashboard/materi', [DashboardController::class, 'materi'])->name('dashboard.materi');
    Route::get('/dashboard/katalog', [DashboardController::class, 'katalog'])->name('dashboard.katalog');
    Route::post('/dashboard/katalog/daftar', [DashboardController::class, 'katalogDaftar'])->name('dashboard.katalog.daftar');
    Route::get('/dashboard/nilai', [DashboardController::class, 'nilai'])->name('dashboard.nilai');
    Route::get('/dashboard/laporan', [DashboardController::class, 'laporan'])->name('dashboard.laporan');
    Route::get('/dashboard/pengaturan', [DashboardController::class, 'pengaturan'])->name('dashboard.pengaturan');
    Route::post('/dashboard/pengaturan', [DashboardController::class, 'pengaturanUpdate'])->name('dashboard.pengaturan.update');
    Route::post('/dashboard/paket/upgrade', [DashboardController::class, 'paketUpgrade'])->name('dashboard.paket.upgrade');
});

// ---------- Dashboard Guru (perlu login + role guru/admin) ----------
Route::middleware(['auth', 'role:guru,admin'])->prefix('dashboard-guru')->name('guru.')->group(function () {
    Route::get('/', [GuruDashboardController::class, 'index'])->name('dashboard');
    Route::get('/kelas', [GuruKelasController::class, 'index'])->name('kelas.index');
    Route::get('/kelas/create', [GuruKelasController::class, 'create'])->name('kelas.create');
    Route::post('/kelas', [GuruKelasController::class, 'store'])->name('kelas.store');
    Route::get('/kelas/{kelas}/edit', [GuruKelasController::class, 'edit'])->name('kelas.edit');
    Route::put('/kelas/{kelas}', [GuruKelasController::class, 'update'])->name('kelas.update');
    Route::delete('/kelas/{kelas}', [GuruKelasController::class, 'destroy'])->name('kelas.destroy');

    Route::get('/materi', [GuruMateriController::class, 'index'])->name('materi.index');
    Route::get('/materi/create', [GuruMateriController::class, 'create'])->name('materi.create');
    Route::post('/materi', [GuruMateriController::class, 'store'])->name('materi.store');
    Route::get('/materi/{materi}/edit', [GuruMateriController::class, 'edit'])->name('materi.edit');
    Route::put('/materi/{materi}', [GuruMateriController::class, 'update'])->name('materi.update');
    Route::delete('/materi/{materi}', [GuruMateriController::class, 'destroy'])->name('materi.destroy');

    // Paket & hapus siswa: hanya admin
    Route::middleware('role:admin')->group(function () {
        Route::get('/paket', [GuruPaketController::class, 'index'])->name('paket.index');
        Route::get('/paket/create', [GuruPaketController::class, 'create'])->name('paket.create');
        Route::post('/paket', [GuruPaketController::class, 'store'])->name('paket.store');
        Route::get('/paket/{paket}/edit', [GuruPaketController::class, 'edit'])->name('paket.edit');
        Route::put('/paket/{paket}', [GuruPaketController::class, 'update'])->name('paket.update');
        Route::delete('/paket/{paket}', [GuruPaketController::class, 'destroy'])->name('paket.destroy');

        Route::delete('/siswa/{siswa}', [GuruSiswaController::class, 'destroy'])->name('siswa.destroy');
    });

    Route::get('/siswa', [GuruSiswaController::class, 'index'])->name('siswa');
});