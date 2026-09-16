@extends('layout.dashboard')

@section('title', 'Detail Materi - PintarKuy')

@php
    $emptyKelas = empty($course);

    $curName = '';
    $curDur = '';
    $nextName = '';
    $nextDur = '';
    foreach ($babs as $bab) {
        foreach ($bab['modul'] as $m) {
            if ($m['status'] === 'current') { $curName = $m['nama']; $curDur = $m['durasi']; }
            if ($m['status'] === 'next') { $nextName = $m['nama']; $nextDur = $m['durasi']; }
        }
    }

    $currentCourse = $emptyKelas ? null : [
        'tag' => $course['name'],
        'fokus' => $course['fokus'],
        'judul' => $course['judul'],
        'tutor' => $course['tutor'],
        'pertemuan' => $course['pertemuan'],
        'pct' => $course['pct'],
        'modul_done' => (int) round($course['pct'] / 100 * max($course['modul_total'], 1)),
        'modul_total' => $course['modul_total'],
        'jadwal_live' => $course['jadwal_live'],
    ];

    $tipeLabel = [
        'video' => 'Video Pembelajaran',
        'teks' => 'Ringkasan Teks',
        'video_teks' => 'Video + Ringkasan',
    ];
@endphp

@section('pageContent')
    <div class="flex flex-col gap-6">

        {{-- ============ HEAD ============ --}}
        <div class="dash-head dash-reveal">
            <div>
                <a href="{{ route('dashboard.kelas') }}" class="materi-back">← Kembali ke Kelas Saya</a>
                <h1 class="materi-title">Detail Materi</h1>
                <p class="dash-head-sub">{{ $course['name'] ?? 'Kelas' }} • Silabus lengkap beserta progres modul yang sedang kamu ikuti.</p>
            </div>
            <div class="dash-head-actions">
                <a href="{{ route('dashboard.latsol') }}" class="dash-btn dash-btn--ghost">Latihan Soal</a>
                <a href="{{ route('dashboard.nilai') }}" class="dash-btn dash-btn--ghost">Lihat Nilai</a>
            </div>
        </div>

        @if ($emptyKelas)
            <div class="dash-card dash-reveal" style="padding:48px;text-align:center;">
                <p style="font-size:40px;margin-bottom:8px;">📚</p>
                <p class="materi-title" style="font-size:22px;margin-bottom:6px;">Kamu belum terdaftar di kelas manapun</p>
                <p class="dash-head-sub" style="margin-bottom:20px;">Ikuti katalog program untuk mulai belajar dan mengerjakan latihan.</p>
                <a href="{{ route('dashboard.katalog') }}" class="dash-btn dash-btn--primary">Jelajahi Katalog</a>
            </div>
        @else
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

                    @if ($curName)
                        <button type="button" class="materi-cta dash-btn dash-btn--primary" id="lanjutkanCta">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5.14v13.72a1 1 0 001.5.86l11-6.86a1 1 0 000-1.72l-11-6.86A1 1 0 008 5.14z"/></svg>
                            Lanjutkan Materi
                        </button>
                        <span class="materi-hero-hint">Lanjut: {{ $curName }}</span>
                    @else
                        <span class="materi-hero-hint">Semua modul telah ditandai selesai. Kerjakan latihan soalnya! 🎯</span>
                    @endif
                </div>
                <div class="materi-hero-side">
                    <span class="materi-hero-icon">{{ mb_substr($course['name'], 0, 1) }}</span>
                    <p class="materi-hero-side-label">Modul Berikutnya</p>
                    <p class="materi-hero-side-name">{{ $nextName ?: 'Kerjakan Latihan' }}</p>
                    <p class="materi-hero-side-meta">{{ $nextDur ?: 'Lihat paket latihan di bawah' }}</p>
                </div>
            </div>

            {{-- ============ SILABUS + INFO ============ --}}
            <div class="materi-grid">
                <div class="dash-card dash-reveal">
                    <p class="dash-card-title"><span class="dash-dot"></span>Silabus & Modul Pembelajaran</p>

                    <div class="materi-silabus" id="materiSilabus">
                        @forelse ($babs as $bab)
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
                                            <button type="button"
                                                class="modul-row {{ $m['status'] === 'current' ? 'is-current' : '' }} {{ $m['status'] === 'next' ? 'is-next' : '' }} {{ $m['status'] === 'locked' ? 'is-locked' : 'is-clickable' }}"
                                                data-nama="{{ $m['nama'] }}"
                                                data-durasi="{{ $m['durasi'] }}"
                                                data-tipe="{{ $m['tipe'] }}"
                                                data-video="{{ $m['video_url'] }}"
                                                data-konten="{{ $m['konten'] }}"
                                                data-materi="{{ $m['materi_id'] }}"
                                                data-status="{{ $m['status'] }}"
                                                {{ $m['status'] === 'locked' ? 'disabled' : '' }}>
                                                <span class="modul-ico modul-ico--{{ $m['status'] }}">
                                                    @if ($m['status'] === 'locked')
                                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                                    @elseif ($m['status'] === 'done')
                                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                                    @elseif ($m['tipe'] === 'teks')
                                                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h10"/></svg>
                                                    @else
                                                        <svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5.14v13.72a1 1 0 001.5.86l11-6.86a1 1 0 000-1.72l-11-6.86A1 1 0 008 5.14z"/></svg>
                                                    @endif
                                                </span>
                                                <span class="modul-info">
                                                    <span class="modul-name">{{ $m['nama'] }}</span>
                                                    <span class="modul-dur">{{ $tipeLabel[$m['tipe']] ?? $m['tipe'] }} • {{ $m['durasi'] }}</span>
                                                </span>
                                                <span class="materi-status materi-status--{{ $m['status'] }}">{{ $badge }}</span>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="guru-empty" style="padding:24px;text-align:center;">Belum ada materi untuk kelas ini.</p>
                        @endforelse
                    </div>

                    <div class="materi-foot-note" id="materiNext">
                        Latihan soal & tryout mini tersedia di bawah setelah kamu pelajari materinya. 🎯
                    </div>
                </div>

                <aside class="flex flex-col gap-6">
                    <div class="dash-card dash-reveal">
                        <p class="dash-card-title"><span class="dash-dot"></span>Info Kelas</p>
                        <ul class="materi-info">
                            <li>
                                <span class="materi-info-ico" style="color:var(--green);background:rgba(94,234,212,0.35)">👨‍🏫</span>
                                <div><span class="materi-info-label">Tutor</span><span class="materi-info-value">{{ $currentCourse['tutor'] }}</span></div>
                            </li>
                            <li>
                                <span class="materi-info-ico">📅</span>
                                <div><span class="materi-info-label">Kelas Live Mingguan</span><span class="materi-info-value">{{ $currentCourse['jadwal_live'] ?: 'Rekaman tersedia 24/7' }}</span></div>
                            </li>
                            <li>
                                <span class="materi-info-ico">📜</span>
                                <div><span class="materi-info-label">Akses Kelas</span><span class="materi-info-value">Selama paket aktif</span></div>
                            </li>
                            <li>
                                <span class="materi-info-ico">🎓</span>
                                <div><span class="materi-info-label">Sertifikat</span><span class="materi-info-value">Lulus ≥ 75% modul</span></div>
                            </li>
                        </ul>
                    </div>

                    @if ($latsolSets)
                        <div class="dash-card dash-reveal">
                            <p class="dash-card-title"><span class="dash-dot"></span>Latihan Soal Mapel Ini</p>
                            <div class="flex flex-col gap-3">
                                @foreach ($latsolSets as $set)
                                    <div class="materi-latsol-set">
                                        <div class="materi-latsol-set-info">
                                            <p class="materi-latsol-set-label">{{ $set['label'] }}</p>
                                            <p class="materi-latsol-set-meta">{{ $set['total'] }} soal • Terbaik: {{ $set['best'] ?? '-' }}</p>
                                        </div>
                                        <a href="{{ route('dashboard.latsol.mulai', [$set['kelas_id'], $set['label']]) }}" class="dash-btn dash-btn--primary dash-btn--sm">Mulai</a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </aside>
            </div>
        @endif
    </div>

    {{-- ============ MODAL MATERI ============ --}}
    <div class="materi-modal-backdrop" id="materiModal" aria-hidden="true">
        <div class="materi-modal" role="dialog" aria-modal="true" aria-labelledby="materiModalTitle">
            <div class="materi-modal-head">
                <div>
                    <p class="materi-modal-kicker" id="materiModalKicker">Video Pembelajaran</p>
                    <h3 id="materiModalTitle">-</h3>
                </div>
                <button type="button" class="materi-modal-close" id="materiModalClose" aria-label="Tutup">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="materi-modal-body">
                <div class="materi-modal-video" id="materiModalVideo"></div>
                <div class="materi-modal-durasi" id="materiModalDurasi"></div>
                <div class="materi-modal-konten" id="materiModalKonten"></div>
                <div class="materi-modal-actions">
                    <button type="button" class="dash-btn dash-btn--ghost" id="materiModalDone">Tandai Selesai ✓</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    @vite(['resources/css/dashboard/site.css', 'resources/css/dashboard/materi.css'])
@endpush

@push('scripts')
    @vite(['resources/js/dashboard/materi.js'])
@endpush