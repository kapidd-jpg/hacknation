<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| StudyServer — routes for the 3 pages built from Figma
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
    return view('dashboard');
})->name('dashboard');
