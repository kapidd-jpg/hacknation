<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Nilai;
use App\Models\Paket;
use App\Models\Pendaftaran;
use App\Support\UserFoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
    ];

    public function index()
    {
        if (Auth::user()->isStaff()) {
            return redirect()->route('guru.dashboard');
        }

        return view('dashboard.index');
    }

    public function katalog()
    {
        if (Auth::user()->isStaff()) {
            return redirect()->route('guru.dashboard');
        }

        $kelas = Kelas::query()->where('aktif', true)->orderBy('cat')->orderBy('id')->get();

        return view('dashboard.katalog', [
            'kelas' => $kelas,
            'katalog' => $kelas->map(fn ($k) => collect($k->getAttributes())
                ->only(['id', 'slug', 'cat', 'ico', 'name', 'meta', 'desc', 'modul', 'durasi', 'siswa', 'price', 'old', 'bg', 'color'])
                ->all()),
            'terdaftarIds' => Auth::user()->pendaftaran()->pluck('kelas_id')->map(fn ($v) => (string) $v)->all(),
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

        $daftar = Pendaftaran::query()->where('user_id', $user->id)->get();
        if ($daftar->contains(fn ($p) => (int) $p->kelas_id === (int) $kelas->id)) {
            return $this->respondDaftar($request, false, 'Kamu sudah terdaftar di kelas ini.');
        }

        $kuota = Paket::query()->where('key', $user->paket)->value('kuota');
        if ($kuota !== null && $daftar->count() >= $kuota) {
            return $this->respondDaftar($request, false, 'Kuota kelas paket kamu sudah penuh. Upgrade paket untuk menambah kelas.');
        }

        Pendaftaran::query()->create([
            'user_id' => $user->id,
            'kelas_id' => $kelas->id,
        ]);

        return $this->respondDaftar($request, true, 'Berhasil daftar kelas ' . $kelas->name . '!', true);
    }

    public function paketUpgrade(Request $request)
    {
        $user = Auth::user();
        if ($user->isStaff()) {
            return $this->respondDaftar($request, false, 'Upgrade paket hanya untuk akun siswa.');
        }

        $data = $request->validate([
            'paket' => ['required', 'string', 'in:starter,utbk-pro,golden'],
        ]);

        $paket = Paket::query()->where('key', $data['paket'])->where('aktif', true)->first();
        if (! $paket) {
            return $this->respondDaftar($request, false, 'Paket tidak tersedia.');
        }

        $user->paket = $paket->key;
        $user->save();

        return $this->respondDaftar($request, true, 'Paket kamu diupgrade ke ' . $paket->nama . '!');
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

        $kelasAll = [];
        foreach (Auth::user()->kelasTerdaftar()->orderBy('pendaftaran.created_at', 'desc')->get() as $k) {
            $meta = self::META_KELAS[$k->slug] ?? ['pct' => 60, 'note' => 'Latihan Soal Tersedia', 'pertemuan' => $k->durasi ?? '10 Pertemuan'];
            $kelasAll[] = [
                'slug' => $k->slug,
                'name' => $k->name,
                'tag' => $k->cat,
                'desc' => $k->meta ?? '',
                'ico' => substr($k->ico ?? 'KL', 0, 2),
                'bg' => $k->bg ?? 'rgba(126,252,154,0.35)',
                'color' => $k->color ?? '#007433',
                'pct' => $meta['pct'],
                'prog' => round(($meta['pct'] / 100) * max($k->modul, 1)) . '/' . $k->modul . ' Modul',
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
        foreach (Auth::user()->kelasTerdaftar()->orderBy('pendaftaran.created_at', 'desc')->get() as $k) {
            $meta = self::META_KELAS[$k->slug] ?? ['fokus' => 'Fokus UTBK', 'pct' => 60, 'jadwal' => 'Minggu, 18:30 WIB', 'pertemuan' => '12 Pertemuan'];
            $materi = $k->materi()->orderBy('urutan')->get();
            $judulMateri = $materi->sortBy('urutan')->skip(1)->first();
            $courses[$k->slug] = [
                'name' => $k->name,
                'fokus' => $meta['fokus'],
                'judul' => $judulMateri ? $judulMateri->judul : 'Pembahasan Materi Inti ' . $k->name,
                'tutor' => $materi->first()?->tutor ?? 'Tim Tutor Master PTN',
                'pertemuan' => $meta['pertemuan'],
                'pct' => $meta['pct'],
                'modul_total' => $k->modul,
                'bab_cur' => 2,
                'jadwal_live' => $meta['jadwal'],
            ];
        }

        return view('dashboard.materi', ['courses' => $courses]);
    }

    public function nilai()
    {
        if (Auth::user()->isStaff()) {
            return redirect()->route('guru.dashboard');
        }

        return view('dashboard.nilai');
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
            $meta = self::META_KELAS[$k->slug] ?? ['pct' => 60];
            $judul = $k->materi()->orderBy('urutan')->skip(1)->first();
            $materi[] = [
                'name' => $judul?->judul ?? $k->name,
                'pct' => $meta['pct'],
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
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
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