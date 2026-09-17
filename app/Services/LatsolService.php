<?php

namespace App\Services;

use App\Models\Jawaban;
use App\Models\Materi;
use App\Models\Nilai;
use App\Models\Pengerjaan;
use App\Models\Soal;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class LatsolService
{
    public function passingThreshold(): int
    {
        return (int) config('latsol.passing_threshold', 70);
    }

    public function statistikTtl(): int
    {
        return (int) config('latsol.statistik_ttl', 3600);
    }

    /**
     * Hitung breakdown satu attempt (murni, bisa di-unit test).
     *
     * @param  \Illuminate\Database\Eloquent\Collection<int, Soal>  $soals
     * @param  array<int|string, mixed>  $jawabanInput  map soal_id => pilihan
     */
    public function hitungBreakdown(Collection $soals, array $jawabanInput): array
    {
        $benar = 0;
        $salah = 0;
        $kosong = 0;
        $total = $soals->count();
        $statusRows = [];

        foreach ($soals as $soal) {
            $pilihan = isset($jawabanInput[$soal->id]) ? (int) $jawabanInput[$soal->id] : null;

            if ($pilihan === null) {
                $status = 'kosong';
                $kosong++;
            } elseif ($pilihan === (int) $soal->kunci) {
                $status = 'benar';
                $benar++;
            } else {
                $status = 'salah';
                $salah++;
            }

            $statusRows[] = [
                'soal_id' => $soal->id,
                'pilihan' => $pilihan,
                'benar' => $status === 'benar',
                'status' => $status,
            ];
        }

        // SNBT (2023+): tanpa minus. Benar = 1, salah/kosong = 0.
        $skor = $total ? (int) round($benar / $total * 100) : 0;
        // Akurasi mengukur benar di antara soal yang DUAWAB (kosong dikeluarkan).
        $dijawab = $benar + $salah;
        $akurasi = $dijawab ? (int) round($benar / $dijawab * 100) : 0;

        return compact('benar', 'salah', 'kosong', 'total', 'skor', 'akurasi', 'statusRows');
    }

    /**
     * Simpan satu attempt (transaksi): pengerjaan + jawaban + nilai.
     * Kembalikan ringkasan, atau null bila set kosong/tidak ditemukan.
     *
     * @param  array<int|string, mixed>  $jawabanInput
     * @return array{attempt: Pengerjaan, tuntas: bool, breakdown: array}|null
     */
    public function buatAttempt(User $user, int $kelasId, string $set, array $jawabanInput, ?Carbon $waktuMulai = null): ?array
    {
        $soals = Soal::query()
            ->where('kelas_id', $kelasId)
            ->where('set_label', $set)
            ->where('aktif', true)
            ->orderBy('urutan')
            ->get();

        if ($soals->isEmpty()) {
            return null;
        }

        $breakdown = $this->hitungBreakdown($soals, $jawabanInput);
        $materiId = $soals->pluck('materi_id')->filter()->first();
        $waktuSelesai = now();
        $waktuMulai = $waktuMulai ?? $waktuSelesai;

        $attempt = DB::transaction(function () use ($user, $kelasId, $materiId, $set, $breakdown, $waktuMulai, $waktuSelesai) {
            $pengerjaan = Pengerjaan::create([
                'user_id' => $user->id,
                'kelas_id' => $kelasId,
                'materi_id' => $materiId,
                'set_label' => $set,
                'tipe' => 'latsol',
                'skor' => $breakdown['skor'],
                'akurasi' => $breakdown['akurasi'],
                'benar' => $breakdown['benar'],
                'salah' => $breakdown['salah'],
                'kosong' => $breakdown['kosong'],
                'total' => $breakdown['total'],
                'waktu_mulai' => $waktuMulai,
                'waktu_selesai' => $waktuSelesai,
            ]);

            foreach ($breakdown['statusRows'] as $row) {
                $row['user_id'] = $user->id;
                $row['pengerjaan_id'] = $pengerjaan->id;
                Jawaban::create($row);
            }

            Nilai::create([
                'user_id' => $user->id,
                'kelas_id' => $kelasId,
                'skor' => $breakdown['skor'],
                'akurasi' => $breakdown['akurasi'],
                'tanggal' => $waktuSelesai->toDateString(),
            ]);

            return $pengerjaan;
        });

        return [
            'attempt' => $attempt,
            'tuntas' => $breakdown['skor'] >= $this->passingThreshold(),
            'breakdown' => $breakdown,
        ];
    }

    public function getNilaiTerkini(int $siswaId, int $kelasId, string $set): ?Pengerjaan
    {
        return Pengerjaan::query()
            ->where('user_id', $siswaId)
            ->where('kelas_id', $kelasId)
            ->where('set_label', $set)
            ->orderByDesc('id')
            ->first();
    }

    public function getNilaiTerkiniPerMateri(int $siswaId, int $materiId): ?Pengerjaan
    {
        return Pengerjaan::query()
            ->where('user_id', $siswaId)
            ->where('materi_id', $materiId)
            ->orderByDesc('id')
            ->first();
    }

    /**
     * Histori semua attempt sebelumnya untuk satu set (urutan naik) → tren.
     *
     * @return Collection<int, Pengerjaan>
     */
    public function getHistoriAttempt(int $siswaId, int $kelasId, string $set): Collection
    {
        return Pengerjaan::query()
            ->where('user_id', $siswaId)
            ->where('kelas_id', $kelasId)
            ->where('set_label', $set)
            ->orderBy('created_at')
            ->orderBy('id')
            ->get();
    }

    /**
     * Rata-rata skor per materi (relasi soal → materi), untuk bar chart Laporan.
     *
     * @return array<int, array{id: int, materi: string, skor: int}>
     */
    public function getRataRataPerMateri(int $siswaId): array
    {
        return Pengerjaan::query()
            ->where('user_id', $siswaId)
            ->whereNotNull('materi_id')
            ->with('materi:id,judul')
            ->get()
            ->groupBy('materi_id')
            ->map(fn (Collection $items) => [
                'id' => (int) $items->first()->materi_id,
                'materi' => $items->first()->materi?->judul ?: 'Materi #' . $items->first()->materi_id,
                'skor' => (int) round($items->avg('skor')),
            ])
            ->sortByDesc('skor')
            ->values()
            ->all();
    }

    /**
     * Statistik global untuk landing page — hasil di-cache TTL 1 jam
     * (tidak dihitung real-time setiap request).
     *
     * @return array{rata_rata: int|null, total_ujian: int}
     */
    public function getStatistikGlobal(): array
    {
        return Cache::remember('latsol.statistik-global', now()->addSeconds($this->statistikTtl()), function () {
            $stat = Pengerjaan::query()
                ->selectRaw('AVG(skor) as rata, COUNT(*) as total')
                ->first();

            return [
                'rata_rata' => $stat?->rata !== null ? (int) round((float) $stat->rata) : null,
                'total_ujian' => (int) ($stat?->total ?? 0),
            ];
        });
    }

    public function clearStatistikGlobal(): void
    {
        Cache::forget('latsol.statistik-global');
    }

    /**
     * Gate progres modul: materi boleh ditandai tuntas jika siswa punya attempt
     * set latsol terkait dengan skor >= threshold. Materi tanpa set latsol = bebas.
     */
    public function gateProgres(User $user, Materi $materi): array
    {
        $punyaSet = Soal::query()->where('materi_id', $materi->id)->exists();
        if (! $punyaSet) {
            return ['lulus' => true, 'skor' => null];
        }

        $terkini = $this->getNilaiTerkiniPerMateri($user->id, $materi->id);
        $skor = $terkini?->skor;

        return [
            'lulus' => $skor !== null && $skor >= $this->passingThreshold(),
            'skor' => $skor,
        ];
    }
}