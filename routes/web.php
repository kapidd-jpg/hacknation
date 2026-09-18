<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HalamanController;
use App\Http\Controllers\KontakController;
use App\Http\Controllers\Guru\DashboardController as GuruDashboardController;
use App\Http\Controllers\Guru\KelasController as GuruKelasController;
use App\Http\Controllers\Guru\MateriController as GuruMateriController;
use App\Http\Controllers\Guru\PaketController as GuruPaketController;
use App\Http\Controllers\Guru\PengampuController as GuruPengampuController;
use App\Http\Controllers\Guru\SiswaController as GuruSiswaController;
use App\Http\Controllers\Guru\SoalController as GuruSoalController;
use App\Http\Controllers\PaketPilihanController;
use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PintarKuy - minimalis: 2 role (Siswa + Guru) + auth database
|--------------------------------------------------------------------------
*/

// ---------- Publik ----------
Route::get('/', [HalamanController::class, 'home'])->name('home');
Route::get('/tentang', [HalamanController::class, 'tentang'])->name('about');
Route::get('/kelas', [HalamanController::class, 'kelas'])->name('classes');
Route::get('/kontak', [HalamanController::class, 'kontak'])->name('contact');

// ---------- Auth ----------
Route::middleware('guest')->group(function () {
    Route::get('/masuk', [LoginController::class, 'create'])->name('login');
    Route::post('/masuk', [LoginController::class, 'store'])->middleware('throttle:5,1')->name('login.attempt');
    Route::get('/daftar', [RegisterController::class, 'create'])->name('register');
    Route::post('/daftar', [RegisterController::class, 'store'])->middleware('throttle:5,1')->name('register.attempt');
});

Route::post('/logout', LogoutController::class)->middleware('auth')->name('logout');

Route::post('/kontak', [KontakController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('contact.send');

// ---------- Dashboard Siswa (perlu login) ----------
Route::middleware('auth')->group(function () {
    Route::middleware('redirect.staff')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/kelas', [DashboardController::class, 'kelas'])->name('dashboard.kelas');
        Route::get('/dashboard/materi', [DashboardController::class, 'materi'])->name('dashboard.materi');
        Route::get('/dashboard/katalog', [DashboardController::class, 'katalog'])->name('dashboard.katalog');
        Route::get('/dashboard/nilai', [DashboardController::class, 'nilai'])->name('dashboard.nilai');
        Route::get('/dashboard/laporan', [DashboardController::class, 'laporan'])->name('dashboard.laporan');
        Route::get('/dashboard/latsol', [DashboardController::class, 'latsol'])->name('dashboard.latsol');
        Route::get('/dashboard/latsol/mulai/{kelas}/{set}', [DashboardController::class, 'latsolMulai'])->name('dashboard.latsol.mulai');
        Route::post('/dashboard/latsol/kirim', [DashboardController::class, 'latsolKirim'])->name('dashboard.latsol.kirim');
        Route::get('/dashboard/latsol/hasil/{pengerjaan}', [DashboardController::class, 'latsolHasil'])->name('dashboard.latsol.hasil');
        Route::get('/dashboard/pengaturan', [DashboardController::class, 'pengaturan'])->name('dashboard.pengaturan');

        Route::get('/pilih-paket', [PaketPilihanController::class, 'index'])->name('paket.index');
        Route::get('/pilih-paket/{key}', [PaketPilihanController::class, 'checkout'])->name('paket.checkout');
        Route::post('/pilih-paket/bayar', [PaketPilihanController::class, 'bayar'])->middleware('throttle:5,1')->name('paket.bayar');
        Route::get('/paket-berhasil', [PaketPilihanController::class, 'berhasil'])->name('paket.berhasil');

        Route::get('/ruang/{room}', [RoomController::class, 'show'])->name('room.show');

        Route::post('/dashboard/katalog/daftar', [DashboardController::class, 'katalogDaftar'])->middleware('throttle:20,1')->name('dashboard.katalog.daftar');
        Route::post('/dashboard/progres-modul', [DashboardController::class, 'progresModul'])->middleware('throttle:20,1')->name('dashboard.progres.modul');
        Route::post('/dashboard/pengaturan', [DashboardController::class, 'pengaturanUpdate'])->middleware('throttle:20,1')->name('dashboard.pengaturan.update');
        Route::post('/dashboard/pengaturan/keamanan', [DashboardController::class, 'pengaturanKeamanan'])->middleware('throttle:20,1')->name('dashboard.pengaturan.keamanan');
    });

    // Gambar avatar dipakai juga layout guru/admin, jadi butuh akses staf.
    Route::get('/akun/foto', [DashboardController::class, 'foto'])->name('user.foto');

    Route::prefix('ruang')->name('room.')->group(function () {
        Route::get('/', [RoomController::class, 'index'])->name('index');
        Route::get('/{room}/state', [RoomController::class, 'state'])->name('state');
        Route::post('/token', [RoomController::class, 'token'])->middleware('throttle:20,1')->name('token');
        Route::post('/presence', [RoomController::class, 'presence'])->middleware('throttle:20,1')->name('presence');
        Route::post('/kontrol', [RoomController::class, 'kontrol'])->middleware(['role:guru,admin', 'throttle:30,1'])->name('kontrol');
    });

    Route::post('/ruang/materi', [RoomController::class, 'materi'])
        ->middleware(['role:guru,admin', 'throttle:20,1'])
        ->name('room.materi');

    Route::post('/dashboard/katalog/daftar', [DashboardController::class, 'katalogDaftar'])->middleware('throttle:20,1')->name('dashboard.katalog.daftar');
    Route::post('/dashboard/progres-modul', [DashboardController::class, 'progresModul'])->middleware('throttle:20,1')->name('dashboard.progres.modul');
    Route::get('/akun/foto', [DashboardController::class, 'foto'])->name('user.foto');
    Route::post('/dashboard/pengaturan', [DashboardController::class, 'pengaturanUpdate'])->middleware('throttle:20,1')->name('dashboard.pengaturan.update');
    Route::post('/dashboard/pengaturan/keamanan', [DashboardController::class, 'pengaturanKeamanan'])->middleware('throttle:20,1')->name('dashboard.pengaturan.keamanan');
});

// ---------- Dashboard Guru (perlu login + role guru/admin) ----------
Route::middleware(['auth', 'role:guru,admin'])->prefix('dashboard-guru')->name('guru.')->group(function () {
    Route::get('/', [GuruDashboardController::class, 'index'])->name('dashboard');
    Route::get('/kelas', [GuruKelasController::class, 'index'])->name('kelas.index');
    Route::get('/kelas/create', [GuruKelasController::class, 'create'])->name('kelas.create');
    Route::post('/kelas', [GuruKelasController::class, 'store'])->middleware('throttle:20,1')->name('kelas.store');
    Route::get('/kelas/{kelas}/edit', [GuruKelasController::class, 'edit'])->name('kelas.edit');
    Route::put('/kelas/{kelas}', [GuruKelasController::class, 'update'])->middleware('throttle:20,1')->name('kelas.update');
    Route::delete('/kelas/{kelas}', [GuruKelasController::class, 'destroy'])->middleware('throttle:20,1')->name('kelas.destroy');

    Route::get('/materi', [GuruMateriController::class, 'index'])->name('materi.index');
    Route::get('/materi/create', [GuruMateriController::class, 'create'])->name('materi.create');
    Route::post('/materi', [GuruMateriController::class, 'store'])->middleware('throttle:20,1')->name('materi.store');
    Route::get('/materi/{materi}/edit', [GuruMateriController::class, 'edit'])->name('materi.edit');
    Route::put('/materi/{materi}', [GuruMateriController::class, 'update'])->middleware('throttle:20,1')->name('materi.update');
    Route::delete('/materi/{materi}', [GuruMateriController::class, 'destroy'])->middleware('throttle:20,1')->name('materi.destroy');

    Route::get('/soal', [GuruSoalController::class, 'index'])->name('soal.index');
    Route::get('/soal/create', [GuruSoalController::class, 'create'])->name('soal.create');
    Route::post('/soal', [GuruSoalController::class, 'store'])->middleware('throttle:20,1')->name('soal.store');
    Route::get('/soal/{soal}/edit', [GuruSoalController::class, 'edit'])->name('soal.edit');
    Route::put('/soal/{soal}', [GuruSoalController::class, 'update'])->middleware('throttle:20,1')->name('soal.update');
    Route::delete('/soal/{soal}', [GuruSoalController::class, 'destroy'])->middleware('throttle:20,1')->name('soal.destroy');
    Route::get('/soal/materi/{kelas}', [GuruSoalController::class, 'materiByKelas'])->name('soal.materi.bykelas');

    // Paket & hapus siswa & pengampu mapel guru: hanya admin
    Route::middleware('role:admin')->group(function () {
        Route::get('/paket', [GuruPaketController::class, 'index'])->name('paket.index');
        Route::get('/paket/create', [GuruPaketController::class, 'create'])->name('paket.create');
        Route::post('/paket', [GuruPaketController::class, 'store'])->middleware('throttle:20,1')->name('paket.store');
        Route::get('/paket/{paket}/edit', [GuruPaketController::class, 'edit'])->name('paket.edit');
        Route::put('/paket/{paket}', [GuruPaketController::class, 'update'])->middleware('throttle:20,1')->name('paket.update');
        Route::delete('/paket/{paket}', [GuruPaketController::class, 'destroy'])->middleware('throttle:20,1')->name('paket.destroy');

        Route::delete('/siswa/{siswa}', [GuruSiswaController::class, 'destroy'])->middleware('throttle:20,1')->name('siswa.destroy');

        Route::get('/pengampu', [GuruPengampuController::class, 'index'])->name('pengampu.index');
        Route::post('/pengampu', [GuruPengampuController::class, 'update'])->middleware('throttle:20,1')->name('pengampu.update');
    });

    Route::get('/siswa', [GuruSiswaController::class, 'index'])->name('siswa');

    // Room (LiveKit + sync materi)
    Route::get('/ruang', [RoomController::class, 'index'])->name('room.index');
    Route::get('/ruang/{room}', [RoomController::class, 'show'])->name('room.show');
    Route::get('/ruang/{room}/kelas/{kelas}/materi', [RoomController::class, 'materiList'])->name('room.materi.list');
});