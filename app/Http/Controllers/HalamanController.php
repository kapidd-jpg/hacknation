<?php

namespace App\Http\Controllers;

use App\Models\Kelas;

class HalamanController extends Controller
{
    public function kelas()
    {
        $kelas = Kelas::query()
            ->where('aktif', true)
            ->orderBy('cat')
            ->orderBy('id')
            ->get()
            ->map(fn (Kelas $k) => [
                'cat' => $k->cat,
                'ico' => $k->ico,
                'name' => $k->name,
                'meta' => $k->meta,
                'desc' => $k->desc,
                'modul' => $k->modul,
                'durasi' => $k->durasi,
                'siswa' => $k->siswa,
                'price' => $k->price,
                'old' => $k->old,
                'iconBg' => $k->bg ?: 'rgba(94,234,212,0.4)',
                'iconColor' => $k->color ?: '#0F766E',
            ])
            ->values()
            ->all();

        $kategori = Kelas::query()
            ->where('aktif', true)
            ->distinct()
            ->orderBy('cat')
            ->pluck('cat')
            ->all();

        return view('halaman.kelas', [
            'kelas' => $kelas,
            'kategori' => $kategori,
        ]);
    }
}