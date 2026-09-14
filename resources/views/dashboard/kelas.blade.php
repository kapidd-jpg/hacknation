@extends('layout.dashboard')

@section('title', 'Kelas Saya — PintarKuy')

@php
    $kelasAll = $kelasAll ?? [
        ['slug' => 'matematika', 'name' => 'Matematika', 'tag' => 'WAJIB', 'desc' => 'Kelas 12 SMA • Persiapan UTBK', 'ico' => 'Mt', 'bg' => 'rgba(94,234,212,0.35)', 'color' => '#0F766E', 'pct' => 82, 'prog' => '18/22 Modul', 'note' => 'Live: Besok, 16:00 WIB', 'cat' => 'WAJIB', 'pertemuan' => '32 Pertemuan'],
        ['slug' => 'fisika', 'name' => 'Fisika', 'tag' => 'SAINTEK', 'desc' => 'Mekanika & Termodinamika', 'ico' => 'Fi', 'bg' => 'rgba(223,228,251,1)', 'color' => '#1E3ABA', 'pct' => 65, 'prog' => '13/20 Modul', 'note' => 'Live: Kamis, 19:30 WIB', 'cat' => 'SAINTEK', 'pertemuan' => '20 Pertemuan'],
        ['slug' => 'bahasa', 'name' => 'Bahasa Inggris', 'tag' => 'LITERASI', 'desc' => 'Reading Comprehension & HOTS', 'ico' => 'En', 'bg' => 'rgba(223,228,251,1)', 'color' => '#1E3ABA', 'pct' => 90, 'prog' => '27/30 Modul', 'note' => 'Latihan Soal Tersedia', 'cat' => 'LITERASI', 'pertemuan' => '30 Pertemuan'],
        ['slug' => 'pemrograman', 'name' => 'Pemrograman', 'tag' => 'EKSTRA', 'desc' => 'Dasar Logika & Python untuk Sains', 'ico' => 'Py', 'bg' => 'rgba(254,243,199,1)', 'color' => '#92400e', 'pct' => 45, 'prog' => '9/20 Modul', 'note' => 'Tugas Coding Aktif (H-2)', 'cat' => 'EKSTRA', 'pertemuan' => '20 Pertemuan'],
        ['slug' => 'kimia', 'name' => 'Kimia', 'tag' => 'SAINTEK', 'desc' => 'Stoikiometri & Larutan', 'ico' => 'Ki', 'bg' => 'rgba(94,234,212,0.35)', 'color' => '#0F766E', 'pct' => 58, 'prog' => '14/24 Modul', 'note' => 'Live: Sabtu, 09:00 WIB', 'cat' => 'SAINTEK', 'pertemuan' => '24 Pertemuan'],
        ['slug' => 'tps', 'name' => 'TPS Penalaran', 'tag' => 'UTBK', 'desc' => 'Logika & Penalaran Kuantitatif', 'ico' => 'TP', 'bg' => 'rgba(223,228,251,1)', 'color' => '#1E3ABA', 'pct' => 71, 'prog' => '17/24 Modul', 'note' => 'Latihan Soal Tersedia', 'cat' => 'UTBK', 'pertemuan' => '24 Pertemuan'],
    ];
@endphp

@section('pageContent')
    <div class="flex flex-col gap-6 dash-reveal">
        <div class="dash-head">
            <div>
                <h1>Kelas Saya</h1>
                <p class="dash-head-sub">Semua kelas yang kamu ikuti semester ini beserta progres belajarmu.</p>
            </div>
            <div class="dash-head-actions">
                <a href="{{ route('dashboard.katalog') }}" class="dash-btn dash-btn--ghost">Cari Kelas Lain</a>
                <a href="{{ route('dashboard.katalog') }}" class="dash-btn dash-btn--primary">+ Daftar Kelas Baru</a>
            </div>
        </div>

        <div id="kelasGrid" class="dash-grid dash-grid--2">
            @foreach ($kelasAll as $k)
                <div class="kelas-item">
                    <div class="kelas-item-top">
                        <span class="kelas-icon" style="background:{{ $k['bg'] }};color:{{ $k['color'] }}">{{ $k['ico'] }}</span>
                        <div class="flex-1 min-w-0">
                            <h3>{{ $k['name'] }}</h3>
                            <p class="kelas-meta">{{ $k['desc'] }}</p>
                            <div class="kelas-tags">
                                <span class="dash-pill">{{ $k['cat'] }}</span>
                                <span class="dash-pill">{{ $k['pertemuan'] }}</span>
                            </div>
                        </div>
                    </div>

                    <p class="kelas-desc">{{ $k['pertemuan'] }} • Tutor Master PTN • Rekaman kelas tersedia 24/7.</p>

                    <div>
                        <div class="kelas-progress-head">
                            <span>{{ $k['pct'] }}% Selesai</span>
                            <span>{{ $k['prog'] }}</span>
                        </div>
                        <div class="dash-progress"><span style="width:0%" data-w="{{ $k['pct'] }}"></span></div>
                    </div>

                    <div class="kelas-note">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $k['note'] }}
                    </div>

                    <div class="kelas-item-foot">
                        <a href="{{ route('dashboard.materi', ['kelas' => $k['slug']]) }}" class="dash-btn dash-btn--primary">Lanjutkan Belajar</a>
                        <a href="{{ route('dashboard.materi', ['kelas' => $k['slug']]) }}" class="dash-btn dash-btn--ghost">Lihat Materi</a>
                    </div>
                </div>
            @endforeach
        </div>

        <a href="{{ route('dashboard.katalog') }}" class="kelas-empty-step dash-reveal">Butuh kelas baru? Jelajahi katalog dan daftar sekarang →</a>
    </div>
@endsection

@push('styles')
    @vite(['resources/css/dashboard/site.css', 'resources/css/dashboard/kelas.css'])
@endpush

@push('scripts')
    <script>window.pintarKuyMateriUrl = window.pintarKuyMateriUrl || @json(route('dashboard.materi'));</script>
    @vite(['resources/js/dashboard/kelas.js'])
@endpush