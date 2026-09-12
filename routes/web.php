<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PintarKuy — routes for the 3 pages built from Figma
|--------------------------------------------------------------------------
| Drop this into your existing routes/web.php (or merge with what's
| already there). Controllers are left out on purpose — these are plain
| view routes so you can wire up real auth/data logic yourself.
*/

Route::get('/', function () {
    return view('pages.landing');
})->name('home');

Route::get('/masuk', function () {
    return view('auth.login');
})->name('login');

// Placeholder POST target for the login form — replace with real auth
// (e.g. Auth::attempt(...) via Laravel Breeze/Fortify) once you wire it up.
Route::post('/masuk', function () {
    return redirect()->route('dashboard');
})->name('login.attempt');

// Placeholder — point this at your actual registration route/page.
Route::get('/daftar', function () {
    return redirect()->route('login');
})->name('register');

Route::get('/dashboard', function () {
    return view('dashboard.index');
})->name('dashboard');

Route::view('/dashboard/kelas', 'dashboard.kelas')->name('dashboard.kelas');
Route::view('/dashboard/katalog', 'dashboard.katalog')->name('dashboard.katalog');
Route::view('/dashboard/nilai', 'dashboard.nilai')->name('dashboard.nilai');
Route::view('/dashboard/laporan', 'dashboard.laporan')->name('dashboard.laporan');
Route::view('/dashboard/pengaturan', 'dashboard.pengaturan')->name('dashboard.pengaturan');

Route::view('/tentang', 'pages.tentang')->name('about');
Route::view('/kelas', 'pages.kelas')->name('classes');
Route::view('/kontak', 'pages.kontak')->name('contact');
