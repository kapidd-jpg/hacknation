<?php

namespace App\Http\Controllers;

use App\Models\Kelas;

use App\Models\Paket;

use App\Services\LatsolService;

use Illuminate\Http\Response;

class HalamanController extends Controller
{
    public function home(LatsolService $service)
    {
        return $this->cachedView('halaman.landing', [
            'statistikGlobal' => $service->getStatistikGlobal(),
        ], 120);
    }

    public function tentang()
    {
        return $this->cachedView('halaman.tentang', [], 300);
    }

    public function kontak()
    {
        return $this->cachedView('halaman.kontak', [], 300);
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

        return $this->cachedView('halaman.kelas', [
            'kelas' => $kelas,
            'kategori' => $kategori,
        ], 120);
    }

    private function cachedView(string $view, array $data, int $seconds): Response
    {
        $response = response()->view($view, $data);

        if (! auth()->check()) {
            $response->header('Cache-Control', 'public, max-age=' . $seconds . ', s-maxage=' . $seconds . ', stale-while-revalidate=' . ($seconds * 5));
        }

        return $response;
    }
}