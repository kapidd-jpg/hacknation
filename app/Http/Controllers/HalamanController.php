<?php

namespace App\Http\Controllers;

use App\Models\Kelas;

use App\Models\Paket;

use App\Services\LatsolService;

class HalamanController extends Controller
{
    public function home(LatsolService $service)
    {
        return view('halaman.landing', [
            'statistikGlobal' => $service->getStatistikGlobal(),
        ]);
    }

    public function kelas()
    {
        $catPrice = Paket::pricesByKategori();

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
                'price' => Paket::formatHarga($catPrice[$k->cat]['harga'] ?? $k->price),
                'old' => Paket::formatHarga($catPrice[$k->cat]['harga_lama'] ?? $k->old),
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