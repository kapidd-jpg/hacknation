<?php

namespace App\Http\Controllers;

use App\Models\Jawaban;
use App\Models\Kelas;
use App\Models\Materi;
use App\Models\Nilai;
use App\Models\Paket;
use App\Models\Pendaftaran;
use App\Models\Pengerjaan;
use App\Models\ProgresModul;
use App\Models\Soal;
use App\Support\UserFoto;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    protected const META_KELAS = [
        'tps' => ['fokus' => 'UTBK Fokus', 'pct' => 71, 'jadwal' => 'Minggu, 18:30 WIB', 'pertemuan' => '24 Pertemuan', 'note' => 'Latihan Soal Tersedia'],
        'matematika' => ['fokus' => 'SNBT Fokus', 'pct' => 82, 'jadwal' => 'Rabu, 19:00 WIB', 'pertemuan' => '27 Pertemuan', 'note' => 'Live: Besok, 16:00 WIB'],
        'fisika' => ['fokus' => 'Saintek Fokus', 'pct' => 65, 'jadwal' => 'Kamis, 19:30 WIB', 'pertemuan' => '20 Pertemuan', 'note' => 'Live: Kamis, 19:30 WIB'],
        'inggris-literasi' => ['fokus' => 'Literasi & HOTS', 'pct' => 90, 'jadwal' => 'Jumat, 17:00 WIB', 'pertemuan' => '30 Pertemuan', 'note' => 'Latihan Soal Tersedia'],
        'python' => ['fokus' => 'Ekstra • Python', 'pct' => 45, 'jadwal' => 'Sabtu, 10:00 WIB', 'pertemuan' => '20 Pertemuan', 'note' => 'Tugas Coding Aktif (H-2)'],
        'kimia' => ['fokus' => 'Saintek Fokus', 'pct' => 58, 'jadwal' => 'Sabtu, 09:00 WIB', 'pertemuan' => '24 Pertemuan', 'note' => 'Live: Sabtu, 09:00 WIB'],
        'literasi' => ['fokus' => 'SNBT Fokus', 'pct' => 75, 'jadwal' => 'Kamis, 16:00 WIB', 'pertemuan' => '28 Pertemuan', 'note' => 'Latihan Soal Tersedia'],
        'biologi' => ['fokus' => 'Saintek Fokus', 'pct' => 50, 'jadwal' => 'Selasa, 15:30 WIB', 'pertemuan' => '26 Pertemuan', 'note' => 'Latihan Soal Tersedia'],
        'toefl' => ['fokus' => 'Bahasa', 'pct' => 60, 'jadwal' => 'Senin, 18:00 WIB', 'pertemuan' => '20 Pertemuan', 'note' => 'Latihan Soal Tersedia'],
        'web-design' => ['fokus' => 'Ekstra • Studio', 'pct' => 40, 'jadwal' => 'Sabtu, 13:00 WIB', 'pertemuan' => '16 Pertemuan', 'note' => 'Live: Sabtu, 13:00 WIB'],
        'penalaran-matematika' => ['fokus' => 'UTBK Fokus', 'pct' => 55, 'jadwal' => 'Selasa, 18:00 WIB', 'pertemuan' => '22 Pertemuan', 'note' => 'Latihan Soal Tersedia'],
        'pengetahuan-kuantitatif' => ['fokus' => 'UTBK Fokus', 'pct' => 52, 'jadwal' => 'Senin, 17:30 WIB', 'pertemuan' => '20 Pertemuan', 'note' => 'Latihan Soal Tersedia'],
        'pbm' => ['fokus' => 'UTBK Fokus', 'pct' => 48, 'jadwal' => 'Kamis, 17:00 WIB', 'pertemuan' => '18 Pertemuan', 'note' => 'Latihan Soal Tersedia'],
        'literasi-inggris' => ['fokus' => 'UTBK Fokus', 'pct' => 50, 'jadwal' => 'Jumat, 16:30 WIB', 'pertemuan' => '18 Pertemuan', 'note' => 'Latihan Soal Tersedia'],
        'matematika-wajib' => ['fokus' => 'Mapel SMA', 'pct' => 62, 'jadwal' => 'Rabu, 15:30 WIB', 'pertemuan' => '20 Pertemuan', 'note' => 'Live: Rabu, 15:30 WIB'],
        'ekonomi' => ['fokus' => 'Mapel SMA', 'pct' => 46, 'jadwal' => 'Senin, 16:00 WIB', 'pertemuan' => '20 Pertemuan', 'note' => 'Latihan Soal Tersedia'],
        'sosiologi' => ['fokus' => 'Mapel SMA', 'pct' => 44, 'jadwal' => 'Selasa, 13:00 WIB', 'pertemuan' => '18 Pertemuan', 'note' => 'Latihan Soal Tersedia'],
        'geografi' => ['fokus' => 'Mapel SMA', 'pct' => 42, 'jadwal' => 'Rabu, 13:30 WIB', 'pertemuan' => '18 Pertemuan', 'note' => 'Latihan Soal Tersedia'],
        'sejarah' => ['fokus' => 'Mapel SMA', 'pct' => 40, 'jadwal' => 'Kamis, 14:00 WIB', 'pertemuan' => '18 Pertemuan', 'note' => 'Latihan Soal Tersedia'],
        'ielts' => ['fokus' => 'Tes Internasional', 'pct' => 38, 'jadwal' => 'Sabtu, 08:00 WIB', 'pertemuan' => '20 Pertemuan', 'note' => 'Live: Sabtu, 08:00 WIB'],
        'toeic' => ['fokus' => 'Tes Internasional', 'pct' => 36, 'jadwal' => 'Minggu, 09:00 WIB', 'pertemuan' => '16 Pertemuan', 'note' => 'Latihan Soal Tersedia'],
        'jerman' => ['fokus' => 'Bahasa', 'pct' => 34, 'jadwal' => 'Selasa, 18:30 WIB', 'pertemuan' => '16 Pertemuan', 'note' => 'Live: Selasa, 18:30 WIB'],
        'korea' => ['fokus' => 'Bahasa', 'pct' => 42, 'jadwal' => 'Jumat, 19:00 WIB', 'pertemuan' => '16 Pertemuan', 'note' => 'Live: Jumat, 19:00 WIB'],
        'jepang' => ['fokus' => 'Bahasa', 'pct' => 36, 'jadwal' => 'Sabtu, 19:00 WIB', 'pertemuan' => '18 Pertemuan', 'note' => 'Live: Sabtu, 19:00 WIB'],
        'mandarin' => ['fokus' => 'Bahasa', 'pct' => 32, 'jadwal' => 'Minggu, 19:30 WIB', 'pertemuan' => '16 Pertemuan', 'note' => 'Live: Minggu, 19:30 WIB'],
        'public-speaking' => ['fokus' => 'Ekstra • Soft Skill', 'pct' => 30, 'jadwal' => 'Senin, 19:30 WIB', 'pertemuan' => '12 Pertemuan', 'note' => 'Live: Senin, 19:30 WIB'],
        'digital-marketing' => ['fokus' => 'Ekstra • Skill Digital', 'pct' => 28, 'jadwal' => 'Rabu, 19:30 WIB', 'pertemuan' => '14 Pertemuan', 'note' => 'Tugas Praktek Aktif'],
    ];

    public function index()
    {
        $user = Auth::user();
        if ($user->isStaff()) {
            return redirect()->route('guru.dashboard');
        }

        if (! $user->hasPaket()) {
            return redirect()->route('paket.index');
        }

        return view('dashboard.index');
    }

    public function katalog()
    {
        if (Auth::user()->isStaff()) {
            return redirect()->route('guru.dashboard');
        }

        $user = Auth::user();
        $kelas = Kelas::query()->where('aktif', true)->orderBy('cat')->orderBy('id')->get();

        $catPaket = [];
        foreach (Paket::query()->where('aktif', true)->get() as $paket) {
            foreach (($paket->kategori ?? []) as $cat) {
                $catPaket[$cat] = $paket->key;
            }
        }

        return view('dashboard.katalog', [
            'kelas' => $kelas,
            'katalog' => $kelas->map(fn ($k) => collect($k->getAttributes())
                ->only(['id', 'slug', 'cat', 'ico', 'name', 'meta', 'desc', 'modul', 'durasi', 'siswa', 'price', 'old', 'bg', 'color'])
                ->all()),
            'terdaftarIds' => $user->pendaftaran()->pluck('kelas_id')->map(fn ($v) => (string) $v)->all(),
            'paketKeys' => $user->paketKeys(),
            'aksesKategori' => $user->aksesKategori(),
            'hasAnyPaket' => $user->hasAnyPaket(),
            'catPaket' => $catPaket,
        ]);
    }

    public function katalogDaftar(Request $request)
    {
        $user = Auth::user();
        if ($user->isStaff()) {
            return $this->respondDaftar($request, false, 'Halaman katalog khusus untuk akun siswa.');
        }

        $request->validate([
            'kelas_id' => ['required', 'integer', 'exists:kelas,id'],
        ]);

        $kelas = Kelas::query()->where('aktif', true)->findOrFail($request->input('kelas_id'));

        if (! in_array($kelas->cat, $user->aksesKategori(), true)) {
            $needed = Paket::query()
                ->where('aktif', true)
                ->get()
                ->first(fn ($p) => in_array($kelas->cat, $p->kategori ?? [], true));

            return $this->respondDaftar(
                $request,
                false,
                'Kelas ' . $kelas->name . ' termasuk kategori ' . $kelas->cat . '.'
                    . ($needed ? ' Beli ' . $needed->nama . ' untuk mengaksesnya.' : ' Pilih paket yang sesuai untuk mengaksesnya.')
            );
        }

        try {
            $hasil = DB::transaction(function () use ($user, $kelas) {
                if (Pendaftaran::query()
                    ->where('user_id', $user->id)
                    ->where('kelas_id', $kelas->id)
                    ->exists()) {
                    return 'duplicate';
                }

                Pendaftaran::query()->create([
                    'user_id' => $user->id,
                    'kelas_id' => $kelas->id,
                ]);

                return 'ok';
            });
        } catch (QueryException $e) {
            return $this->respondDaftar($request, false, 'Kamu sudah terdaftar di kelas ini.');
        }

        if ($hasil === 'duplicate') {
            return $this->respondDaftar($request, false, 'Kamu sudah terdaftar di kelas ini.');
        }

        return $this->respondDaftar($request, true, 'Berhasil daftar kelas ' . $kelas->name . '!', true);
    }

protected function respondDaftar(Request $request, bool $ok, string $message, bool $redirectToKelas = false)
    {
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['ok' => $ok, 'message' => $message]);
        }

        if ($ok) {
            return redirect()->route('dashboard.kelas')->with('status', $message);
        }

        return back()->with('status', $message);
    }

    public function kelas()
    {
        if (Auth::user()->isStaff()) {
            return redirect()->route('guru.dashboard');
        }

        $user = Auth::user();
        $kelasAll = [];
        $completedIds = $user->completedModulIds();
        foreach ($user->kelasTerdaftar()->orderBy('pendaftaran.created_at', 'desc')->get() as $k) {
            $modulTotal = max($k->materi()->count(), 1);
            $modulDone = $completedIds->intersect($k->materi()->pluck('id'))->count();
            $pct = (int) round($modulDone / $modulTotal * 100);
            $meta = self::META_KELAS[$k->slug] ?? ['pct' => 60, 'note' => 'Latihan Soal Tersedia', 'pertemuan' => $k->durasi ?? '10 Pertemuan'];
            $kelasAll[] = [
                'slug' => $k->slug,
                'name' => $k->name,
                'tag' => $k->cat,
                'desc' => $k->meta ?? '',
                'ico' => substr($k->ico ?? 'KL', 0, 2),
                'bg' => $k->bg ?? 'rgba(94,234,212,0.4)',
                'color' => $k->color ?? '#0F766E',
                'pct' => $pct,
                'prog' => $modulDone . '/' . $modulTotal . ' Modul',
                'note' => $meta['note'],
                'cat' => $k->cat,
                'pertemuan' => $meta['pertemuan'],
            ];
        }

        return view('dashboard.kelas', ['kelasAll' => $kelasAll]);
    }

    public function materi()
    {
        if (Auth::user()->isStaff()) {
            return redirect()->route('guru.dashboard');
        }

        $courses = [];
        $allBabs = [];
        $allSets = [];
        $kelasSlugs = [];
        $completedIds = Auth::user()->completedModulIds();
        foreach (Auth::user()->kelasTerdaftar()->orderBy('pendaftaran.created_at', 'desc')->get() as $k) {
            $meta = self::META_KELAS[$k->slug] ?? ['fokus' => 'Fokus UTBK', 'pct' => 60, 'jadwal' => 'Minggu, 18:30 WIB', 'pertemuan' => '12 Pertemuan'];
            $materi = $k->materi()->orderBy('urutan')->get();
            $modulTotal = max($materi->count(), 1);
            $modulDone = $materi->whereIn('id', $completedIds)->count();
            $pct = (int) round($modulDone / $modulTotal * 100);
            $judulMateri = $materi->sortBy('urutan')->skip(1)->first();
            $courses[$k->slug] = [
                'name' => $k->name,
                'fokus' => $meta['fokus'],
                'judul' => $judulMateri ? $judulMateri->judul : 'Pembahasan Materi Inti ' . $k->name,
                'tutor' => $materi->first()?->tutor ?? 'Tim Tutor Master PTN',
                'pertemuan' => $meta['pertemuan'],
                'pct' => $pct,
                'modul_total' => $modulTotal,
                'bab_cur' => min(max($modulDone + 1, 1), max($modulTotal, 1)),
                'jadwal_live' => $meta['jadwal'],
            ];
            $kelasSlugs[] = $k->slug;

            $babs = [];
            $babIndex = [];
            $foundCurrent = false;
            $foundNext = false;
            foreach ($materi as $m) {
                $bab = $m->bab ?: 'Bab ' . $m->urutan;
                if (!isset($babIndex[$bab])) {
                    $babIndex[$bab] = count($babs);
                    $babs[] = ['no' => count($babs) + 1, 'judul' => $bab, 'open' => false, 'modul' => []];
                }
                if ($completedIds->contains($m->id)) {
                    $st = 'done';
                } elseif (! $foundCurrent) {
                    $st = 'current';
                    $foundCurrent = true;
                } elseif (! $foundNext) {
                    $st = 'next';
                    $foundNext = true;
                } else {
                    $st = 'locked';
                }
                $babs[$babIndex[$bab]]['modul'][] = [
                    'nama' => $m->judul,
                    'durasi' => $m->durasi ?: '15 Menit',
                    'status' => $st,
                    'materi_id' => $m->id,
                    'tipe' => $m->tipe ?: 'video',
                    'video_url' => $m->video_url,
                    'konten' => $m->konten,
                ];
            }
            foreach ($babs as $i => &$bab) {
                $bab['open'] = $i === 0;
                $bab['meta'] = count($bab['modul']) . ' Modul • ' . collect($bab['modul'])->pluck('durasi')->join(' • ');
            }
            unset($bab);
            $allBabs[$k->slug] = $babs;

            $sets = [];
            foreach (Soal::where('kelas_id', $k->id)->where('aktif', true)->distinct()->orderBy('set_label')->pluck('set_label') as $label) {
                $sets[] = [
                    'kelas_id' => $k->id,
                    'label' => $label,
                    'total' => Soal::where('kelas_id', $k->id)->where('set_label', $label)->where('aktif', true)->count(),
                    'best' => Pengerjaan::where('user_id', Auth::id())->where('kelas_id', $k->id)->where('set_label', $label)->max('skor'),
                ];
            }
            $allSets[$k->slug] = $sets;
        }

        $slug = request('kelas', $kelasSlugs[0] ?? '');
        if (!isset($courses[$slug])) {
            $slug = $kelasSlugs[0] ?? '';
        }

        return view('dashboard.materi', [
            'courses' => $courses,
            'slug' => $slug,
            'course' => $courses[$slug] ?? null,
            'babs' => $allBabs[$slug] ?? [],
            'latsolSets' => $allSets[$slug] ?? [],
        ]);
    }

    public function nilai()
    {
        if (Auth::user()->isStaff()) {
            return redirect()->route('guru.dashboard');
        }

$user = Auth::user();
        $rows = Pengerjaan::query()->where('user_id', $user->id)->with('kelas')->orderBy('created_at')->get();

        $batas = '2026-07-01';
        $semesters = [
            'ganjil' => ['label' => 'Semester Ganjil 2026/2027', 'from' => $batas, 'to' => null],
            'genap' => ['label' => 'Semester Genap 2025/2026', 'from' => '2025-01-01', 'to' => $batas],
        ];

        $nilaiData = [];
        foreach ($semesters as $key => $sem) {
            $list = $rows->filter(function (Pengerjaan $p) use ($sem) {
                $d = $p->created_at->toDateString();
                return $d >= $sem['from'] && ($sem['to'] === null || $d < $sem['to']);
            });

            $perKelas = [];
            foreach ($list->groupBy('kelas_id') as $kelasId => $items) {
                $kelas = $items->first()->kelas;
                $skor = $items->map(fn (Pengerjaan $p) => $p->skor);
                $avg = round($skor->avg());
                $perKelas[] = [
                    'subj' => $kelas?->name ?: 'Latihan',
                    'cat' => $kelas?->cat ?: '-',
                    'ico' => substr($kelas?->ico ?? 'LT', 0, 2),
                    'bg' => $kelas?->bg ?: 'rgba(94,234,212,0.4)',
                    'color' => $kelas?->color ?: '#0F766E',
                    'soal' => $items->max('total'),
                    'latihan' => $items->count(),
                    'avg' => $avg,
                    'akurasi' => round($items->map(fn (Pengerjaan $p) => $p->akurasi)->avg()),
                    'best' => $skor->max(),
                    'grade' => $avg >= 85 ? 'A' : ($avg >= 70 ? 'B' : 'C'),
                ];
            }
            usort($perKelas, fn ($a, $b) => $b['avg'] <=> $a['avg']);

            $avgs = array_map(fn ($r) => $r['avg'], $perKelas);
            $totalAvg = count($avgs) ? round(array_sum($avgs) / count($avgs)) : null;
            $bestRow = null;
            foreach ($perKelas as $r) {
                if ($bestRow === null || $r['best'] > $bestRow['best']) {
                    $bestRow = $r;
                }
            }

            $nilaiData[$key] = [
                'label' => $sem['label'],
                'rows' => $perKelas,
                'avg' => $totalAvg,
                'grade' => $totalAvg === null ? '-' : ($totalAvg >= 85 ? 'A' : ($totalAvg >= 70 ? 'B' : 'C')),
                'latihan' => $list->count(),
                'best' => $bestRow ? $bestRow['best'] : null,
                'bestSubj' => $bestRow ? $bestRow['subj'] : null,
            ];
        }

        return view('dashboard.nilai', ['nilaiData' => $nilaiData]);
    }

    public function latsol()
    {
        if (Auth::user()->isStaff()) {
            return redirect()->route('guru.dashboard');
        }

        $setsList = [];
        foreach (Auth::user()->kelasTerdaftar()->orderBy('pendaftaran.created_at', 'desc')->get() as $k) {
            $labels = Soal::where('kelas_id', $k->id)->where('aktif', true)->distinct()->orderBy('set_label')->pluck('set_label');
            foreach ($labels as $label) {
                $setsList[] = [
                    'kelas_id' => $k->id,
                    'kelas' => $k->name,
                    'slug' => $k->slug,
                    'ico' => substr($k->ico ?? 'LT', 0, 2),
                    'bg' => $k->bg ?: 'rgba(94,234,212,0.4)',
                    'color' => $k->color ?: '#0F766E',
                    'label' => $label,
                    'total' => Soal::where('kelas_id', $k->id)->where('set_label', $label)->where('aktif', true)->count(),
                    'best' => Pengerjaan::where('user_id', Auth::id())->where('kelas_id', $k->id)->where('set_label', $label)->max('skor'),
                    'attempts' => Pengerjaan::where('user_id', Auth::id())->where('kelas_id', $k->id)->where('set_label', $label)->count(),
                ];
            }
        }

        return view('dashboard.latsol', ['setsList' => $setsList]);
    }

    public function latsolMulai($kelas, $set)
    {
        if (Auth::user()->isStaff()) {
            return redirect()->route('guru.dashboard');
        }

        $kelas = Kelas::findOrFail($kelas);
        if (!Auth::user()->kelasTerdaftar()->whereKey($kelas->id)->exists()) {
            return redirect()->route('dashboard.latsol')->with('status', 'Kelas tidak terdaftar.');
        }

        $soals = Soal::where('kelas_id', $kelas->id)->where('set_label', $set)->where('aktif', true)->orderBy('urutan')->get();
        if ($soals->isEmpty()) {
            return redirect()->route('dashboard.latsol')->with('status', 'Paket latihan tidak ditemukan.');
        }

        return view('dashboard.latsol-kerja', ['kelas' => $kelas, 'set' => $set, 'soals' => $soals]);
    }

    public function latsolKirim(Request $request)
    {
        $user = Auth::user();
        if ($user->isStaff()) {
            return redirect()->route('guru.dashboard');
        }

        $data = $request->validate([
            'kelas_id' => ['required', 'integer', 'exists:kelas,id'],
            'set' => ['required', 'string', 'max:255'],
        ]);

        if (!$user->kelasTerdaftar()->whereKey($data['kelas_id'])->exists()) {
            return back()->with('status', 'Kelas tidak terdaftar.');
        }

        $soals = Soal::where('kelas_id', $data['kelas_id'])->where('set_label', $data['set'])->where('aktif', true)->orderBy('urutan')->get();
        if ($soals->isEmpty()) {
            return back()->with('status', 'Paket latihan tidak ditemukan.');
        }

        $jawabanInput = (array) $request->input('jawaban', []);
        $benar = 0;
        $total = $soals->count();
        $jawabanRows = [];
        foreach ($soals as $soal) {
            $pilihan = isset($jawabanInput[$soal->id]) ? (int) $jawabanInput[$soal->id] : null;
            $isBenar = $pilihan !== null && $pilihan === (int) $soal->kunci;
            if ($isBenar) {
                $benar++;
            }
            $jawabanRows[] = ['soal_id' => $soal->id, 'pilihan' => $pilihan, 'benar' => $isBenar];
        }

        $akurasi = $total ? (int) round($benar / $total * 100) : 0;
        $pengerjaan = Pengerjaan::create([
            'user_id' => $user->id,
            'kelas_id' => $data['kelas_id'],
            'set_label' => $data['set'],
            'tipe' => 'latsol',
            'skor' => $akurasi,
            'akurasi' => $akurasi,
            'benar' => $benar,
            'total' => $total,
        ]);
        foreach ($jawabanRows as $jr) {
            $jr['user_id'] = $user->id;
            $jr['pengerjaan_id'] = $pengerjaan->id;
            Jawaban::create($jr);
        }

        Nilai::create([
            'user_id' => $user->id,
            'kelas_id' => $data['kelas_id'],
            'skor' => $akurasi,
            'akurasi' => $akurasi,
            'tanggal' => now()->toDateString(),
        ]);

        return redirect()->route('dashboard.latsol.hasil', $pengerjaan->id);
    }

    public function latsolHasil(Pengerjaan $pengerjaan)
    {
        $user = Auth::user();
        if ($user->isStaff()) {
            return redirect()->route('guru.dashboard');
        }
        if ((int) $pengerjaan->user_id !== (int) $user->id) {
            abort(403);
        }

        $pengerjaan->load(['kelas', 'jawaban.soal']);

        return view('dashboard.latsol-hasil', ['p' => $pengerjaan]);
    }

    public function progresModul(Request $request)
    {
        $user = Auth::user();
        if ($user->isStaff()) {
            return response()->json(['ok' => false, 'message' => 'Hanya untuk akun siswa.'], 403);
        }

        $data = $request->validate([
            'materi_id' => ['required', 'integer', 'exists:materi,id'],
        ]);

        $materi = Materi::query()->findOrFail($data['materi_id']);
        if (! $user->kelasTerdaftar()->whereKey($materi->kelas_id)->exists()) {
            return response()->json(['ok' => false, 'message' => 'Kelas tidak terdaftar.'], 403);
        }

        $existing = ProgresModul::query()
            ->where('user_id', $user->id)
            ->where('materi_id', $materi->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $completed = false;
        } else {
            ProgresModul::query()->create([
                'user_id' => $user->id,
                'materi_id' => $materi->id,
            ]);
            $completed = true;
        }

        return response()->json([
            'ok' => true,
            'completed' => $completed,
            'pct' => $user->progresPct($materi->kelas),
        ]);
    }

    public function laporan()
    {
        if (Auth::user()->isStaff()) {
            return redirect()->route('guru.dashboard');
        }

        $user = Auth::user();

        $rows = Nilai::query()
            ->where('user_id', $user->id)
            ->orderBy('tanggal')
            ->get();

        $bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        $months = $rows->map(fn (Nilai $n) => [
            'label' => $bulan[(int) $n->tanggal->format('n') - 1] ?? $n->tanggal->format('M'),
            'val' => $n->skor,
            'akurasi' => $n->akurasi,
        ])->values()->all();

        $materi = [];
        foreach ($user->kelasTerdaftar()->get() as $k) {
            $judul = $k->materi()->orderBy('urutan')->skip(1)->first();
            $materi[] = [
                'name' => $judul?->judul ?? $k->name,
                'pct' => $user->progresPct($k),
            ];
        }
        usort($materi, fn ($a, $b) => $a['pct'] <=> $b['pct']);
        $materi = array_slice($materi, 0, 5);

        $terkini = $rows->last();

        return view('dashboard.laporan', [
            'laporan' => [
                'months' => $months,
                'materi' => array_values($materi),
                'skor' => $terkini?->skor,
                'akurasi' => $terkini?->akurasi,
                'delta' => $rows->count() >= 2 ? $terkini->skor - $rows->first()->skor : null,
            ],
        ]);
    }

    const FOTO_WHITELIST_HOSTS = ['www.figma.com'];

    public function foto()
    {
        $foto = Auth::user()?->foto ?? null;

        if (blank($foto)) {
            abort(404);
        }

        if (str_starts_with($foto, 'assets/')) {
            return redirect(asset($foto));
        }

        if (str_starts_with($foto, 'http://') || str_starts_with($foto, 'https://')) {
            $host = strtolower((string) parse_url($foto, PHP_URL_HOST));
            $dipilih = in_array($host, self::FOTO_WHITELIST_HOSTS, true)
                || str_ends_with($host, '.figma.com');

            if (! $dipilih) {
                abort(422, 'Sumber foto tidak diizinkan.');
            }

            return redirect($foto);
        }

        if (! preg_match('/^data:image\/(png|jpeg|webp|gif);base64,(.+)$/s', $foto, $m)) {
            abort(422, 'Format foto tidak valid.');
        }

        $bytes = base64_decode($m[2], true);
        if ($bytes === false || $bytes === '') {
            abort(404);
        }

        $mimeTypes = [
            'png' => 'image/png',
            'jpeg' => 'image/jpeg',
            'webp' => 'image/webp',
            'gif' => 'image/gif',
        ];

        return response($bytes, 200, [
            'Content-Type' => $mimeTypes[$m[1]] ?? 'image/jpeg',
            'Cache-Control' => 'private, no-store, must-revalidate',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    public function pengaturan()
    {
        if (Auth::user()->isStaff()) {
            return redirect()->route('guru.dashboard');
        }

        return view('dashboard.pengaturan');
    }

    public function pengaturanUpdate(Request $request)
    {
        $user = Auth::user();
        if ($user->isStaff()) {
            return response()->json(['ok' => false, 'message' => 'Pengaturan profil hanya untuk akun siswa.'], 403);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id, 'not_regex:/[\r\n]/'],
            'sekolah' => ['nullable', 'string', 'max:255'],
            'kelas_jurusan' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'foto' => ['nullable', 'string', 'max:3000000', function ($attribute, $value, $fail) {
                if (blank($value) || str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
                    return;
                }
                if (! preg_match('/^data:image\/(png|jpeg|webp|gif);base64,/', $value)) {
                    $fail('Format foto tidak didukung. Gunakan PNG, JPG, WEBP, atau GIF.');
                }
            }],
        ]);

        if (! blank($data['foto'] ?? null) && str_starts_with($data['foto'], 'data:')) {
            $data['foto'] = UserFoto::compress($data['foto']);
        }

        $user->update($data);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['ok' => true, 'message' => 'Profil berhasil disimpan.']);
        }

        return back()->with('status', 'Profil berhasil disimpan.');
    }

    public function pengaturanKeamanan(Request $request)
    {
        $user = Auth::user();
        if ($user->isStaff()) {
            return response()->json(['ok' => false, 'message' => 'Pengaturan keamanan hanya untuk akun siswa.'], 403);
        }

        $data = $request->validate([
            'password_lama' => ['nullable', 'string'],
            'password_baru' => ['required_with:password_lama', 'nullable', 'string', 'min:8'],
            'password_baru_confirmation' => ['required_with:password_lama', 'same:password_baru'],
            'two_factor' => ['nullable', 'boolean'],
        ]);

        if (! blank($data['password_lama'] ?? null)) {
            if (! Hash::check($data['password_lama'], $user->password)) {
                return response()->json(['ok' => false, 'message' => 'Password lama salah.'], 422);
            }

            $user->password = $data['password_baru'];
        }

        if (array_key_exists('two_factor', $data)) {
            $user->two_factor_enabled = $data['two_factor'];
        }

        if ($user->isDirty()) {
            $user->save();
        }

        return response()->json(['ok' => true, 'message' => 'Pengaturan keamanan berhasil disimpan.']);
    }
}