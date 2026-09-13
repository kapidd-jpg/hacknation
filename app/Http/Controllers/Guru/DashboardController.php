<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Materi;
use App\Models\Paket;
use App\Models\Pendaftaran;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        return view('guru.dashboard', [
            'jumlahKelas' => Kelas::query()->count(),
            'jumlahMateri' => Materi::query()->count(),
            'jumlahPaket' => Paket::query()->count(),
            'jumlahSiswa' => User::query()->where('role', 'siswa')->count(),
            'jumlahPendaftaran' => Pendaftaran::query()->count(),
            'kelasTerbaru' => Kelas::query()->orderBy('id', 'desc')->limit(5)->get(),
            'siswaTerbaru' => User::query()->where('role', 'siswa')->orderBy('id', 'desc')->limit(5)->get(),
        ]);
    }
}