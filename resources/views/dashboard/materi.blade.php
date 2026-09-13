@extends('layout.dashboard')

@section('title', 'Detail Materi — PintarKuy')

@php
    $coursesDefault = [
        'matematika' => [
            'name' => 'Matematika Saintek', 'fokus' => 'SNBT Fokus',
            'judul' => 'Kalkulus Integral & Penerapan Luas Bidang',
            'tutor' => 'Dr. Hendra Saputra, M.Sc.',
            'pertemuan' => '27 Pertemuan', 'pct' => 68, 'modul_total' => 26, 'bab_cur' => 2,
            'jadwal_live' => 'Rabu, 19:00 WIB',
        ],
        'fisika' => [
            'name' => 'Fisika', 'fokus' => 'Saintek Fokus',
            'judul' => 'Mekanika: Kinematika & Dinamika Partikel',
            'tutor' => 'Dr. Rina Kumala, M.Si.',
            'pertemuan' => '20 Pertemuan', 'pct' => 65, 'modul_total' => 20, 'bab_cur' => 2,
            'jadwal_live' => 'Kamis, 19:30 WIB',
        ],
        'bahasa' => [
            'name' => 'Bahasa Inggris', 'fokus' => 'Literasi & HOTS',
            'judul' => 'Reading Comprehension & Grammar HOTS',
            'tutor' => 'Ms. Amelia Dwi, M.A.',
            'pertemuan' => '30 Pertemuan', 'pct' => 90, 'modul_total' => 30, 'bab_cur' => 3,
            'jadwal_live' => 'Jumat, 17:00 WIB',
        ],
        'pemrograman' => [
            'name' => 'Pemrograman', 'fokus' => 'Ekstra • Python',
            'judul' => 'Python Dasar: Logika & Sains Data',
            'tutor' => 'Bapak Joko Prasetyo, M.Kom.',
            'pertemuan' => '20 Pertemuan', 'pct' => 45, 'modul_total' => 20, 'bab_cur' => 2,
            'jadwal_live' => 'Sabtu, 10:00 WIB',
        ],
        'kimia' => [
            'name' => 'Kimia', 'fokus' => 'Saintek Fokus',
            'judul' => 'Stoikiometri & Larutan Reaksi',
            'tutor' => 'Ibu Sari Dewanti, M.Sc.',
            'pertemuan' => '24 Pertemuan', 'pct' => 58, 'modul_total' => 24, 'bab_cur' => 2,
            'jadwal_live' => 'Sabtu, 09:00 WIB',
        ],
        'tps' => [
            'name' => 'TPS Penalaran', 'fokus' => 'UTBK Fokus',
            'judul' => 'Logika Dasar & Penalaran Kuantitatif',
            'tutor' => 'Dr. Andi Firmansyah, M.Ed.',
            'pertemuan' => '24 Pertemuan', 'pct' => 71, 'modul_total' => 24, 'bab_cur' => 2,
            'jadwal_live' => 'Minggu, 18:30 WIB',
        ],
    ];

    $courses = $courses ?? $coursesDefault;
    if (empty($courses)) {
        $courses = $coursesDefault;
    }

    $firstSlug = array_key_first($courses);
    $slug = request('kelas', $firstSlug ?: 'matematika');
    if (!array_key_exists($slug, $courses)) {
        $slug = $firstSlug ?: 'matematika';
    }
    $c = $courses[$slug];
    $modul_done = (int) round($c['pct'] / 100 * $c['modul_total']);

    $curName = '';
    $curDur = '';
    $nextName = '';
    $nextDur = '';

    if ($slug === 'matematika') {
        $babs = [
            [
                'no' => 1, 'judul' => 'Konsep Dasar Integral', 'meta' => '4 Modul • 120 Menit', 'open' => false,
                'modul' => [
                    ['nama' => 'Pengenalan Integral Tak Tentu', 'durasi' => '12:40', 'status' => 'done'],
                    ['nama' => 'Teorema Fundamental Kalkulus', 'durasi' => '15:10', 'status' => 'done'],
                    ['nama' => 'Sifat Linearitas Integral', 'durasi' => '13:05', 'status' => 'done'],
                    ['nama' => 'Latihan Soal Bab 1', 'durasi' => '20:00', 'status' => 'done'],
                ],
            ],
            [
                'no' => 2, 'judul' => 'Teknik Substitusi', 'meta' => '5 Modul • 150 Menit', 'open' => true,
                'modul' => [
                    ['nama' => 'Substitusi Aljabar Dasar', 'durasi' => '14:22', 'status' => 'done'],
                    ['nama' => 'Teknik Substitusi Aljabar & Trigonometri', 'durasi' => '16:48', 'status' => 'current'],
                    ['nama' => 'Substitusi Trigonometri Lanjut', 'durasi' => '17:05', 'status' => 'next'],
                    ['nama' => 'Latihan Soal Bab 2', 'durasi' => '22:00', 'status' => 'locked'],
                    ['nama' => 'Tryout Mini Bab 2', 'durasi' => '30:00', 'status' => 'locked'],
                ],
            ],
            [
                'no' => 3, 'judul' => 'Integral Parsial', 'meta' => '4 Modul • 130 Menit', 'open' => false,
                'modul' => [
                    ['nama' => 'Konsep Dasar Integral Parsial', 'durasi' => '14:30', 'status' => 'locked'],
                    ['nama' => 'Integral Parsial Eksponensial', 'durasi' => '16:12', 'status' => 'locked'],
                    ['nama' => 'Integral Parsial Logaritma', 'durasi' => '15:40', 'status' => 'locked'],
                    ['nama' => 'Latihan Soal Bab 3', 'durasi' => '22:00', 'status' => 'locked'],
                ],
            ],
            [
                'no' => 4, 'judul' => 'Integral Tentu & Luas Bidang', 'meta' => '5 Modul • 160 Menit', 'open' => false,
                'modul' => [
                    ['nama' => 'Definisi Integral Tentu', 'durasi' => '15:00', 'status' => 'locked'],
                    ['nama' => 'Menghitung Luas Bidang Datar', 'durasi' => '18:25', 'status' => 'locked'],
                    ['nama' => 'Luas Antara Dua Kurva', 'durasi' => '19:10', 'status' => 'locked'],
                    ['nama' => 'Latihan Soal Bab 4', 'durasi' => '25:00', 'status' => 'locked'],
                    ['nama' => 'Tryout Mini Bab 4', 'durasi' => '30:00', 'status' => 'locked'],
                ],
            ],
            [
                'no' => 5, 'judul' => 'Penerapan Aplikasi', 'meta' => '4 Modul • 120 Menit', 'open' => false,
                'modul' => [
                    ['nama' => 'Volume Benda Putar', 'durasi' => '16:30', 'status' => 'locked'],
                    ['nama' => 'Integral dalam Fisika & Ekonomi', 'durasi' => '15:50', 'status' => 'locked'],
                    ['nama' => 'Review Bab 1-5', 'durasi' => '20:00', 'status' => 'locked'],
                    ['nama' => 'Latihan Soal Bab 5', 'durasi' => '24:00', 'status' => 'locked'],
                ],
            ],
        ];
        foreach ($babs as $bab) {
            foreach ($bab['modul'] as $m) {
                if ($m['status'] === 'current') { $curName = $m['nama']; $curDur = $m['durasi']; }
                if ($m['status'] === 'next') { $nextName = $m['nama']; $nextDur = $m['durasi']; }
            }
        }
    } else {
        $babTemplates = [
            ['judul' => 'Konsep & Teori Dasar', 'meta' => '4 Modul • 120 Menit'],
            ['judul' => 'Teknik & Metode Pengerjaan', 'meta' => '4 Modul • 130 Menit'],
            ['judul' => 'Soal Latihan Berjenjang', 'meta' => '4 Modul • 120 Menit'],
            ['judul' => 'Soal HOTS & Pembahasan', 'meta' => '4 Modul • 130 Menit'],
            ['judul' => 'Tryout & Evaluasi', 'meta' => '4 Modul • 120 Menit'],
        ];
        $babs = [];
        $pointer = 0;
        foreach ($babTemplates as $bi => $bt) {
            $mods = [];
            for ($m = 1; $m <= 4; $m++) {
                $durasi = (12 + (($pointer * 7) % 10)) . ':' . str_pad((string) (($pointer * 5) % 60), 2, '0', STR_PAD_LEFT);
                $status = $pointer < $modul_done ? 'done' : ($pointer === $modul_done ? 'current' : ($pointer === $modul_done + 1 ? 'next' : 'locked'));
                $nama = $bt['judul'] . ' — Sub Materi ' . $m;
                if ($status === 'current') { $curName = $nama; $curDur = $durasi; }
                if ($status === 'next') { $nextName = $nama; $nextDur = $durasi; }
                $mods[] = ['nama' => $nama, 'durasi' => $durasi, 'status' => $status];
                $pointer++;
            }
            $babs[] = ['no' => $bi + 1, 'judul' => $bt['judul'], 'meta' => $bt['meta'], 'open' => ($bi + 1) === $c['bab_cur'], 'modul' => $mods];
        }
    }

    $currentCourse = [
        'tag' => $c['name'],
        'fokus' => $c['fokus'],
        'judul' => $c['judul'],
        'tutor' => $c['tutor'],
        'pertemuan' => $c['pertemuan'],
        'sisa_waktu' => 'Masih ada 45 Menit',
        'pct' => $c['pct'],
        'modul_done' => $modul_done,
        'modul_total' => $c['modul_total'],
        'jadwal_live' => $c['jadwal_live'],
        'akses_kelas' => '1 Sep 2026 — 1 Mar 2027',
    ];
@endphp

@section('pageContent')
    <div class="flex flex-col gap-6">

        {{-- ============ HEAD ============ --}}
        <div class="dash-head dash-reveal">
            <div>
                <a href="{{ route('dashboard.kelas') }}" class="materi-back">← Kembali ke Kelas Saya</a>
                <h1 class="materi-title">Detail Materi</h1>
                <p class="dash-head-sub">{{ $c['name'] }} • Silabus lengkap beserta progres modul yang sedang kamu ikuti.</p>
            </div>
            <div class="dash-head-actions">
                <a href="{{ route('dashboard.katalog') }}" class="dash-btn dash-btn--ghost">Jelajahi Katalog</a>
                <a href="{{ route('dashboard.nilai') }}" class="dash-btn dash-btn--ghost">Lihat Nilai</a>
            </div>
        </div>

        {{-- ============ HERO KURSUS ============ --}}
        <div class="materi-hero dash-reveal">
            <div class="materi-hero-main">
                <div class="materi-hero-tags">
                    <span class="dash-pill dash-pill--green">{{ $currentCourse['tag'] }}</span>
                    <span class="dash-pill">{{ $currentCourse['fokus'] }}</span>
                    <span class="dash-pill">{{ $currentCourse['pertemuan'] }}</span>
                </div>
                <h2>{{ $currentCourse['judul'] }}</h2>
                <p class="materi-hero-sub">Tutor {{ $currentCourse['tutor'] }} (Master Tutor PTN) • Video Modul</p>

                <div class="materi-hero-progress">
                    <span class="materi-hero-label">{{ $currentCourse['pct'] }}% Selesai</span>
                    <div class="dash-progress materi-hero-bar"><span style="width:0%" data-w="{{ $currentCourse['pct'] }}"></span></div>
                    <span class="materi-hero-count">{{ $currentCourse['modul_done'] }}/{{ $currentCourse['modul_total'] }} Modul</span>
                </div>

                <a href="#materiNext" class="materi-cta dash-btn dash-btn--primary" id="lanjutkanCta">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5.14v13.72a1 1 0 001.5.86l11-6.86a1 1 0 000-1.72l-11-6.86A1 1 0 008 5.14z"/></svg>
                    Lanjutkan Video Pembelajaran
                </a>
                <span class="materi-hero-hint">{{ $currentCourse['sisa_waktu'] }} • Lanjut 1 Modul lagi</span>
            </div>
            <div class="materi-hero-side">
                <span class="materi-hero-icon">{{ $c['name'][0] }}</span>
                <p class="materi-hero-side-label">Modul Berikutnya</p>
                <p class="materi-hero-side-name">{{ $nextName }}</p>
                <p class="materi-hero-side-meta">Bab {{ $c['bab_cur'] }} • {{ $nextDur }} menit</p>
            </div>
        </div>

        {{-- ============ SILABUS + INFO ============ --}}
        <div class="materi-grid">
            <div class="dash-card dash-reveal">
                <p class="dash-card-title"><span class="dash-dot"></span>Silabus & Modul Pembelajaran</p>

                <div class="materi-silabus" id="materiSilabus">
                    @foreach ($babs as $bab)
                        <div class="bab-item {{ $bab['open'] ? 'is-open' : '' }}">
                            <button type="button" class="bab-head" aria-expanded="{{ $bab['open'] ? 'true' : 'false' }}">
                                <span class="bab-no">Bab {{ $bab['no'] }}</span>
                                <span class="bab-title">{{ $bab['judul'] }}</span>
                                <span class="bab-meta">{{ $bab['meta'] }}</span>
                                <svg class="bab-chev" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div class="bab-body">
                                <div class="bab-body-inner">
                                    @foreach ($bab['modul'] as $m)
                                        @php
                                            $badge = [
                                                'done' => 'Selesai',
                                                'current' => 'Sedang Dipelajari',
                                                'next' => 'Berikutnya',
                                                'locked' => 'Terkunci',
                                            ][$m['status']];
                                        @endphp
                                        <div class="modul-row {{ $m['status'] === 'current' ? 'is-current' : '' }} {{ $m['status'] === 'next' ? 'is-next' : '' }}">
                                            <span class="modul-ico modul-ico--{{ $m['status'] }}">
                                                @if ($m['status'] === 'locked')
                                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                                @elseif ($m['status'] === 'done')
                                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                                @else
                                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5.14v13.72a1 1 0 001.5.86l11-6.86a1 1 0 000-1.72l-11-6.86A1 1 0 008 5.14z"/></svg>
                                                @endif
                                            </span>
                                            <div class="modul-info">
                                                <span class="modul-name">{{ $m['nama'] }}</span>
                                                <span class="modul-dur">{{ $m['durasi'] }} menit</span>
                                            </div>
                                            <span class="materi-status materi-status--{{ $m['status'] }}">{{ $badge }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="materi-foot-note" id="materiNext">
                    Latihan soal & tryout mini akan terbuka otomatis setelah modul sebelumnya selesai. 🎯
                </div>
            </div>

            <aside class="flex flex-col gap-6">
                <div class="dash-card dash-reveal">
                    <p class="dash-card-title"><span class="dash-dot"></span>Info Kelas</p>
                    <ul class="materi-info">
                        <li>
                            <span class="materi-info-ico" style="color:var(--green);background:rgba(126,252,154,0.35)">👨‍🏫</span>
                            <div><span class="materi-info-label">Tutor</span><span class="materi-info-value">{{ $currentCourse['tutor'] }}</span></div>
                        </li>
                        <li>
                            <span class="materi-info-ico">📅</span>
                            <div><span class="materi-info-label">Kelas Live Mingguan</span><span class="materi-info-value">{{ $currentCourse['jadwal_live'] }}</span></div>
                        </li>
                        <li>
                            <span class="materi-info-ico">⏱️</span>
                            <div><span class="materi-info-label">Durasi Pertemuan</span><span class="materi-info-value">120 Menit / Live</span></div>
                        </li>
                        <li>
                            <span class="materi-info-ico">📜</span>
                            <div><span class="materi-info-label">Akses Kelas</span><span class="materi-info-value">{{ $currentCourse['akses_kelas'] }}</span></div>
                        </li>
                        <li>
                            <span class="materi-info-ico">🎓</span>
                            <div><span class="materi-info-label">Sertifikat</span><span class="materi-info-value">Lulus ≥ 75% modul</span></div>
                        </li>
                    </ul>
                </div>

                <div class="dash-card materi-promo dash-reveal">
                    <p class="materi-promo-icon">⚡</p>
                    <p class="materi-promo-title">Kamu tertinggal 3 modul?</p>
                    <p class="materi-promo-sub">Ikut sesi bimbingan individu 1-on-1 untuk mengejar target paling cepat.</p>
                    <a href="{{ route('dashboard.katalog') }}" class="dash-btn dash-btn--ghost materi-promo-btn">Lihat Program Bantuan</a>
                </div>
            </aside>
        </div>
    </div>
@endsection

@push('styles')
    @vite(['resources/css/dashboard/site.css', 'resources/css/dashboard/materi.css'])
@endpush

@push('scripts')
    @vite(['resources/js/dashboard/materi.js'])
@endpush