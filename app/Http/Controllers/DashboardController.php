<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Materi;
use App\Models\Nilai;
use App\Models\Paket;
use App\Models\Pendaftaran;
use App\Models\Pengerjaan;
use App\Models\ProgresModul;
use App\Models\Soal;
use App\Services\LatsolService;
use App\Support\UserNotif;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    private static function fokusLabel(string $cat): string
    {
        return match ($cat) {
            'SAINTEK' => 'Saintek Fokus',
            'SOSHUM' => 'Soshum Fokus',
            'LITERASI' => 'Literasi & HOTS',
            'EKSTRA' => 'Ekstra Kulikuler',
            'BAHASA' => 'Bahasa',
            default => 'Fokus ' . ($cat ?: 'UTBK'),
        };
    }

    public function index()
    {
        $user = Auth::user();

        if (! $user->hasPaket()) {
            return redirect()->route('paket.index');
        }

        $kelasDaftar = $user->kelasTerdaftar()
            ->orderBy('pendaftaran.created_at', 'desc')
            ->with('materi')
            ->get();
        $kelasCount = $kelasDaftar->count();

        $nilai = Nilai::query()->where('user_id', $user->id)->get();
        $avgNilai = $nilai->count() ? round($nilai->avg('skor')) : null;
        $latihanCount = Pengerjaan::query()->where('user_id', $user->id)->count();

        $completedIds = $user->completedModulIds();
        $classes = [];
        $totalModul = 0;
        $doneModul = 0;
        foreach ($kelasDaftar as $k) {
            $materiAll = $k->materi;
            $materiIds = $materiAll->pluck('id');
            $materiTotal = $materiAll->count();
            $done = $completedIds->intersect($materiIds)->count();
            $total = max($materiTotal, 1);
            $classes[] = [
                'name' => $k->name,
                'tag' => $k->cat,
                'desc' => $k->meta ?: ($k->cat . ' • Bimbel Adaptif'),
                'pct' => (int) round($done / $total * 100),
                'progressText' => $done . '/' . $total . ' Modul',
                'icon' => substr($k->ico ?: 'KL', 0, 2),
                'bg' => $k->bg ?: 'rgba(94,234,212,0.4)',
                'color' => $k->color ?: '#0F766E',
            ];
            $totalModul += $materiTotal;
            $doneModul += $done;
        }
        $pctProgres = $totalModul ? (int) round($doneModul / $totalModul * 100) : 0;

        return view('dashboard.index', [
            'kelasCount' => $kelasCount,
            'avgNilai' => $avgNilai,
            'latihanCount' => $latihanCount,
            'nilaiCount' => $nilai->count(),
            'pctProgres' => $pctProgres,
            'doneModul' => $doneModul,
            'totalModul' => $totalModul,
            'classes' => $classes,
        ]);
    }

    public function katalog()
    {
        $user = Auth::user();
        $kelas = Kelas::query()->where('aktif', true)->orderBy('cat')->orderBy('id')->get();

        $catPaket = [];
        foreach (Paket::query()->where('aktif', true)->get() as $paket) {
            foreach (($paket->kategori ?? []) as $cat) {
                $catPaket[$cat] = $paket->key;
            }
        }

        $catPrice = Paket::pricesByKategori();

        return view('dashboard.katalog', [
            'kelas' => $kelas,
            'katalog' => $kelas->map(fn ($k) => collect($k->getAttributes())
                ->only(['id', 'slug', 'cat', 'ico', 'name', 'meta', 'desc', 'modul', 'durasi', 'siswa', 'bg', 'color'])
                ->put('price', Paket::formatHarga($catPrice[$k->cat]['harga'] ?? $k->price))
                ->put('old', Paket::formatHarga($catPrice[$k->cat]['harga_lama'] ?? $k->old))
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
        $user = Auth::user();
        $kelasAll = [];
        $completedIds = $user->completedModulIds();
        foreach ($user->kelasTerdaftar()->orderBy('pendaftaran.created_at', 'desc')->get() as $k) {
            $materiRaw = $k->materi()->count();
            $modulTotal = max($materiRaw, 1);
            $modulDone = $completedIds->intersect($k->materi()->pluck('id'))->count();
            $pct = (int) round($modulDone / $modulTotal * 100);
            $pertemuan = $k->durasi ?: ($modulTotal . ' Modul');
            $note = $materiRaw > 0 ? 'Latihan Soal Tersedia' : 'Belum ada materi';
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
                'note' => $note,
                'cat' => $k->cat,
                'pertemuan' => $pertemuan,
            ];
        }

        return view('dashboard.kelas', ['kelasAll' => $kelasAll]);
    }

    public function materi()
    {
        $courses = [];
        $allBabs = [];
        $allSets = [];
        $kelasSlugs = [];
        $completedIds = Auth::user()->completedModulIds();

        $userId = Auth::id();
        $kelasIds = Auth::user()->kelasTerdaftar()->pluck('kelas.id');
        $soalAgg = Soal::query()->whereIn('kelas_id', $kelasIds)->where('aktif', true)
            ->selectRaw('kelas_id, set_label, count(*) as total')
            ->groupBy('kelas_id', 'set_label')
            ->orderBy('set_label')
            ->get();
        $bestMap = Pengerjaan::query()->where('user_id', $userId)->whereIn('kelas_id', $kelasIds)
            ->selectRaw('kelas_id, set_label, max(skor) as best')
            ->groupBy('kelas_id', 'set_label')
            ->get();
        $bestByKey = $bestMap->mapWithKeys(fn ($b) => [$b->kelas_id . '|' . $b->set_label => $b->best]);

        foreach (Auth::user()->kelasTerdaftar()->orderBy('pendaftaran.created_at', 'desc')->get() as $k) {
            $materi = $k->materi()->orderBy('urutan')->get();
            $modulTotal = max($materi->count(), 1);
            $modulDone = $materi->whereIn('id', $completedIds)->count();
            $pct = (int) round($modulDone / $modulTotal * 100);
            $judulMateri = $materi->sortBy('urutan')->skip(1)->first();
            $pertemuan = $k->durasi ?: ($modulTotal . ' Modul');
            $courses[$k->slug] = [
                'name' => $k->name,
                'fokus' => self::fokusLabel($k->cat),
                'judul' => $judulMateri ? $judulMateri->judul : 'Pembahasan Materi Inti ' . $k->name,
                'tutor' => $materi->first()?->tutor ?? 'Tim Tutor Master PTN',
                'pertemuan' => $pertemuan,
                'pct' => $pct,
                'modul_total' => $modulTotal,
                'bab_cur' => min(max($modulDone + 1, 1), max($modulTotal, 1)),
                'jadwal_live' => '',
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
            foreach ($soalAgg->where('kelas_id', $k->id) as $row) {
                $sets[] = [
                    'kelas_id' => $k->id,
                    'label' => $row->set_label,
                    'total' => (int) $row->total,
                    'best' => $bestByKey[$k->id . '|' . $row->set_label] ?? null,
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

    public function nilai(LatsolService $service)
    {
        $user = Auth::user();
        $rows = Pengerjaan::query()->where('user_id', $user->id)->with('kelas')->orderBy('created_at')->get();

        $awalTahunAjaran = now()->month >= 7 ? now()->year : now()->year - 1;
        $semesters = [
            'ganjil' => ['label' => 'Semester Ganjil ' . $awalTahunAjaran . '/' . ($awalTahunAjaran + 1), 'from' => $awalTahunAjaran . '-07-01', 'to' => ($awalTahunAjaran + 1) . '-07-01'],
            'genap' => ['label' => 'Semester Genap ' . $awalTahunAjaran . '/' . ($awalTahunAjaran + 1), 'from' => ($awalTahunAjaran + 1) . '-01-01', 'to' => ($awalTahunAjaran + 1) . '-07-01'],
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

return view('dashboard.nilai', [
            'nilaiData' => $nilaiData,
            'setsData' => $this->setsDataTerkini($user, $service),
        ]);
    }

    /**
     * Agregasi per set latihan (kelas + set_label): nilai terkini + histori
     * attempt (tren). Nilai utama = attempt PALING BARU, tanpa batas mengulang.
     */
    protected function setsDataTerkini($user, LatsolService $service): array
    {
        $sets = [];

        foreach ($user->kelasTerdaftar()->orderBy('pendaftaran.created_at', 'desc')->get() as $k) {
            $labels = Soal::query()
                ->where('kelas_id', $k->id)
                ->where('aktif', true)
                ->distinct()
                ->orderBy('set_label')
                ->pluck('set_label');

            foreach ($labels as $label) {
                $terkini = $service->getNilaiTerkini($user->id, $k->id, $label);
                $histori = $service->getHistoriAttempt($user->id, $k->id, $label);

                $sets[] = [
                    'kelas_id' => $k->id,
                    'subj' => $k->name,
                    'cat' => $k->cat,
                    'ico' => substr($k->ico ?: 'LT', 0, 2),
                    'bg' => $k->bg ?: 'rgba(94,234,212,0.4)',
                    'color' => $k->color ?: '#0F766E',
                    'label' => $label,
                    'total_soal' => $terkini?->total ?? (int) Soal::query()->where('kelas_id', $k->id)->where('set_label', $label)->where('aktif', true)->count(),
                    'jumlah_attempt' => $histori->count(),
                    'terkini' => $terkini ? [
                        'skor' => $terkini->skor,
                        'akurasi' => $terkini->akurasi,
                        'benar' => $terkini->benar,
                        'salah' => $terkini->salah,
                        'kosong' => $terkini->kosong,
                        'total' => $terkini->total,
                        'waktu' => $terkini->created_at->format('d M Y, H:i'),
                    ] : null,
                    'histori' => $histori->map(fn ($p) => [
                        'skor' => $p->skor,
                        'waktu' => $p->created_at->format('d M, H:i'),
                    ])->all(),
                ];
            }
        }

        return $sets;
    }

    public function latsol()
    {
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

    public function latsolKirim(Request $request, LatsolService $service)
    {
        $user = Auth::user();

        $data = $request->validate([
            'kelas_id' => ['required', 'integer', 'exists:kelas,id'],
            'set' => ['required', 'string', 'max:255'],
            'waktu_mulai' => ['nullable', 'string', 'max:60'],
        ]);

        if (!$user->kelasTerdaftar()->whereKey($data['kelas_id'])->exists()) {
            return back()->with('status', 'Kelas tidak terdaftar.');
        }

        $waktuMulai = null;
        if (!blank($data['waktu_mulai'] ?? null)) {
            try {
                $waktuMulai = Carbon::parse($data['waktu_mulai']);
            } catch (\Throwable $e) {
                $waktuMulai = null;
            }
        }

        $hasil = $service->buatAttempt(
            $user,
            (int) $data['kelas_id'],
            $data['set'],
            (array) $request->input('jawaban', []),
            $waktuMulai
        );

        if ($hasil === null) {
            return back()->with('status', 'Paket latihan tidak ditemukan.');
        }

        $attempt = $hasil['attempt'];
        $skor = (int) ($hasil['breakdown']['skor'] ?? 0);
        UserNotif::push(
            $user,
            'Hasil latihan ' . ($attempt->set_label ?: 'soal') . ' siap dilihat',
            'Skor kamu: ' . $skor . '. ' . (($hasil['tuntas'] ?? false) ? 'Selamat, kamu tuntas!' : 'Cek pembahasan dan coba lagi.'),
            'latsol',
            ['url' => route('dashboard.latsol.hasil', $attempt->id), 'icon' => 'latsol']
        );

        return redirect()->route('dashboard.latsol.hasil', $hasil['attempt']->id);
    }

    public function latsolHasil(Pengerjaan $pengerjaan, LatsolService $service)
    {
        $user = Auth::user();
        if ((int) $pengerjaan->user_id !== (int) $user->id) {
            abort(403);
        }

        $pengerjaan->load(['kelas', 'jawaban.soal']);

        $percobaan = Pengerjaan::where('user_id', $user->id)
            ->where('kelas_id', $pengerjaan->kelas_id)
            ->where('set_label', $pengerjaan->set_label)
            ->where('tipe', 'latsol')
            ->where('id', '<=', $pengerjaan->id)
            ->count();
        $terbaik = Pengerjaan::where('user_id', $user->id)
            ->where('kelas_id', $pengerjaan->kelas_id)
            ->where('set_label', $pengerjaan->set_label)
            ->where('tipe', 'latsol')
            ->max('akurasi');

        return view('dashboard.latsol-hasil', [
            'p' => $pengerjaan,
            'tuntas' => $pengerjaan->skor >= $service->passingThreshold(),
            'passing' => $service->passingThreshold(),
            'percobaan' => $percobaan,
            'terbaik' => $terbaik,
        ]);
    }

    public function progresModul(Request $request, LatsolService $service)
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

        $gate = $service->gateProgres($user, $materi);

        if (! $gate['lulus']) {
            return response()->json([
                'ok' => false,
                'completed' => false,
                'belum_tuntas' => true,
                'message' => $gate['skor'] === null
                    ? 'Modul belum tuntas. Kerjakan latihan soal materi ini dulu (skor minimal ' . $service->passingThreshold() . ').'
                    : 'Modul belum tuntas. Skor latihan terakhirmu ' . $gate['skor'] . ', minimal ' . $service->passingThreshold() . ' untuk lanjut ke bab berikutnya.',
            ], 422);
        }

        try {
            $completed = DB::transaction(function () use ($user, $materi) {
                $existing = ProgresModul::query()
                    ->where('user_id', $user->id)
                    ->where('materi_id', $materi->id)
                    ->lockForUpdate()
                    ->first();

                if ($existing) {
                    $existing->delete();

                    return false;
                }

                ProgresModul::query()->create([
                    'user_id' => $user->id,
                    'materi_id' => $materi->id,
                ]);

                return true;
            });
        } catch (QueryException $e) {
            if (filled($e->getCode()) && (int) $e->getCode() !== 23000) {
                throw $e;
            }

            $completed = true;
        }

        return response()->json([
            'ok' => true,
            'completed' => $completed,
            'pct' => $user->progresPct($materi->kelas),
        ]);
    }

    public function laporan(LatsolService $service)
    {
        $user = Auth::user();

        $rows = Nilai::query()
            ->where('user_id', $user->id)
            ->orderBy('tanggal')
            ->get();

        $bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        $months = $rows->groupBy(fn (Nilai $n) => $n->tanggal->format('Y-m'))
            ->map(fn ($group) => [
                'label' => $bulan[(int) $group->first()->tanggal->format('n') - 1] ?? $group->first()->tanggal->format('M'),
                'val' => round($group->avg('skor')),
                'akurasi' => round($group->avg('akurasi')),
            ])->values()->all();

        $perMateri = $service->getRataRataPerMateri($user->id);
        usort($perMateri, fn ($a, $b) => $a['skor'] <=> $b['skor']);
        $materi = array_map(fn ($m) => [
            'name' => $m['materi'],
            'skor' => $m['skor'],
        ], array_slice($perMateri, 0, 5));

        $valAkhir = $months[count($months) - 1]['val'] ?? null;
        $akurasiAkhir = $months[count($months) - 1]['akurasi'] ?? null;

        $totalLatihan = Nilai::query()->where('user_id', $user->id)->count();
        $totalSoalDikerjakan = (int) Pengerjaan::query()->where('user_id', $user->id)->sum('total');

        return view('dashboard.laporan', [
            'laporan' => [
                'months' => $months,
                'materi' => array_values($materi),
                'skor' => $valAkhir,
                'akurasi' => $akurasiAkhir,
                'delta' => count($months) >= 2 ? $valAkhir - $months[0]['val'] : null,
                'total_latihan' => $totalLatihan,
                'total_soal' => $totalSoalDikerjakan,
            ],
        ]);
    }

    public function pengaturan()
    {
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
            'bio' => ['nullable', 'string', 'max:1000'],
            'email' => ['nullable', 'string', 'email', function ($attribute, $value, $fail) use ($user) {
                if ($value !== $user->email) {
                    $fail('Email tidak dapat diubah.');
                }
            }],
        ]);

        $data['email'] = $user->email;
        $data['sekolah'] = $user->sekolah;
        $data['kelas_jurusan'] = $user->kelas_jurusan;

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

            if (Hash::check($data['password_baru'], $user->password)) {
                return response()->json(['ok' => false, 'message' => 'Password baru tidak boleh sama dengan password lama.'], 422);
            }

            $user->password = $data['password_baru'];
        }

        if (array_key_exists('two_factor', $data)) {
            $user->two_factor_enabled = (bool) ($data['two_factor'] ?? false);
        }

        if ($user->isDirty()) {
            $user->save();
        }

        return response()->json(['ok' => true, 'message' => 'Pengaturan keamanan berhasil disimpan.']);
    }
}