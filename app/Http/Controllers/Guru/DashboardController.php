<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Materi;
use App\Models\Paket;
use App\Models\Pendaftaran;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Non-admin: statistik sebatas mapel yang diampu (hindari info disclosure global).
        $kelasIds = $user->isAdmin()
            ? null
            : $user->kelasDiampu()->pluck('kelas.id');

        $kelasQuery = Kelas::query();
        $materiQuery = Materi::query();
        $pendaftaranQuery = Pendaftaran::query();

        if ($kelasIds !== null) {
            $kelasQuery->whereIn('id', $kelasIds);
            $materiQuery->whereIn('kelas_id', $kelasIds);
            $pendaftaranQuery->whereIn('kelas_id', $kelasIds);
        }

        $siswaTerbaru = User::query()->where('role', 'siswa');

        if ($kelasIds !== null) {
            $siswaTerbaru->whereHas('kelasTerdaftar', fn ($q) => $q->whereIn('kelas.id', $kelasIds));
        }

        return view('guru.dashboard', [
            'jumlahKelas' => (clone $kelasQuery)->count(),
            'jumlahMateri' => (clone $materiQuery)->count(),
            'jumlahPaket' => Paket::query()->count(),
            'jumlahSiswa' => (clone $pendaftaranQuery)->distinct()->count('user_id'),
            'jumlahPendaftaran' => (clone $pendaftaranQuery)->count(),
            'kelasTerbaru' => (clone $kelasQuery)->orderBy('id', 'desc')->limit(5)->get(),
            'siswaTerbaru' => $siswaTerbaru->orderBy('id', 'desc')->limit(5)->get(),
        ]);
    }
}