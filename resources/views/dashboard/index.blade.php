@extends('layouts.dashboard')

@section('title', 'Dashboard Siswa — PintarKuy')

@php
    $stats = [
        ['label' => 'Kelas Aktif', 'value' => '6 Kelas', 'note' => '2 kelas live minggu ini', 'bg' => 'bg-brand-greenlight/40'],
        ['label' => 'Rata-rata Nilai', 'value' => '88.5', 'badge' => '+4.2%', 'note' => 'Kategori Sangat Baik (A)', 'bg' => 'bg-purple-100'],
        ['label' => 'Tugas Selesai', 'value' => '24', 'suffix' => '/ 28', 'note' => '85.7% tingkat penyelesaian', 'bg' => 'bg-navy-100'],
        ['label' => 'Kehadiran Presensi', 'value' => '96%', 'note' => '16 dari 17 sesi dihadiri', 'bg' => 'bg-navy-100'],
    ];

    $classes = [
        ['name' => 'Matematika', 'tag' => 'WAJIB', 'desc' => 'Kelas 12 SMA • Persiapan UTBK', 'pct' => 82, 'progressText' => '18/22 Modul', 'note' => 'Live: Besok, 16:00 WIB', 'color' => 'brand-green', 'iconBg' => 'bg-brand-greenlight/40'],
        ['name' => 'Fisika', 'tag' => 'SAINTEK', 'desc' => 'Mekanika & Termodinamika', 'pct' => 65, 'progressText' => '13/20 Modul', 'note' => 'Live: Kamis, 19:30 WIB', 'color' => 'purple-700', 'iconBg' => 'bg-purple-100'],
        ['name' => 'Bahasa Inggris', 'tag' => 'LITERASI', 'desc' => 'Reading Comprehension & HOTS', 'pct' => 90, 'progressText' => '27/30 Modul', 'note' => 'Latihan Soal Tersedia', 'color' => 'navy-950', 'iconBg' => 'bg-navy-100'],
        ['name' => 'Pemrograman', 'tag' => 'EKSTRA', 'desc' => 'Dasar Logika & Python untuk Sains', 'pct' => 45, 'progressText' => '9/20 Modul', 'note' => 'Tugas Coding Aktif (H-2)', 'color' => 'navy-800', 'iconBg' => 'bg-navy-100'],
    ];
@endphp

@section('pageContent')
    {{-- Greeting banner --}}
    <div class="relative bg-white rounded-2xl shadow-[0px_4px_24px_-2px_rgba(15,27,76,0.06)] p-8 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6 overflow-hidden">
        <div class="absolute -right-16 -top-16 size-64 rounded-full bg-navy-50 blur-2xl"></div>
        <div class="relative flex flex-col gap-2 max-w-xl">
            <div class="flex items-center gap-2">
                <span class="flex items-center gap-1.5 bg-navy-100 text-ink-soft text-[11px] font-bold px-3 py-1 rounded-full">
                    <span class="size-1.5 rounded-full bg-brand-green"></span> Akun Siswa Aktif
                </span>
                <span class="text-ink-muted text-[11px] font-bold">ID: SS-2025-0842</span>
            </div>
            <h1 class="text-navy-950 text-2xl font-extrabold flex items-center gap-2">Selamat datang kembali, Brian! 👋</h1>
            <p class="text-ink-soft text-sm">
                Lanjutkan progres belajarmu hari ini. Kamu memiliki <span class="text-navy-950">2 modul</span> yang siap diselesaikan dan
                <span class="text-red-700">1 tugas</span> mendekati tenggat waktu pengumpulan.
            </p>
        </div>
        <div class="relative flex gap-3">
            <div class="bg-navy-100 rounded-full shadow-sm px-4 py-2.5 flex items-center gap-2.5">
                <span class="flex items-center justify-center size-6 rounded-full bg-navy-800">
                    <svg class="size-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
                <div>
                    <p class="text-ink-muted text-[10px] font-semibold tracking-wide uppercase leading-none">Target Utama</p>
                    <p class="text-navy-950 text-xs font-bold leading-tight">UTBK SNBT 2025</p>
                </div>
            </div>
            <div class="bg-navy-100 rounded-full shadow-sm px-4 py-2.5 flex items-center gap-2.5">
                <span class="flex items-center justify-center size-6 rounded-full bg-brand-greenlight">
                    <svg class="size-3 text-brand-greentext" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/></svg>
                </span>
                <div>
                    <p class="text-ink-muted text-[10px] font-semibold tracking-wide uppercase leading-none">Streak Belajar</p>
                    <p class="text-brand-greentext text-xs font-bold leading-tight">12 Hari Berturut-turut</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Stat cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach ($stats as $stat)
            <div class="bg-white rounded-xl shadow-[0px_4px_20px_-2px_rgba(15,27,76,0.05)] p-5 flex flex-col justify-between">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-ink-soft text-xs font-medium">{{ $stat['label'] }}</p>
                        <p class="text-navy-800 text-2xl font-bold mt-1">
                            {{ $stat['value'] }}
                            @isset($stat['suffix'])<span class="text-ink-muted text-lg font-normal">{{ $stat['suffix'] }}</span>@endisset
                            @isset($stat['badge'])<span class="ml-1 align-middle bg-brand-greenlight/40 text-brand-green text-[11px] font-semibold px-1.5 py-0.5 rounded">{{ $stat['badge'] }}</span>@endisset
                        </p>
                    </div>
                    <span class="flex items-center justify-center size-11 rounded-xl {{ $stat['bg'] }} shrink-0"></span>
                </div>
                <div class="bg-navy-50 rounded-lg px-2.5 py-2 mt-4">
                    <p class="text-ink-soft text-[11px] font-bold">{{ $stat['note'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Continue learning + weekly target --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        {{-- Lanjutkan Belajar --}}
        <div class="lg:col-span-8 bg-white rounded-2xl shadow-[0px_4px_20px_-2px_rgba(15,27,76,0.05)] p-7 flex flex-col justify-between">
            <div class="flex flex-col gap-3">
                <div class="flex items-center justify-between pb-4">
                    <div class="flex items-center gap-2.5">
                        <span class="size-2.5 rounded-full bg-brand-green"></span>
                        <h2 class="text-navy-950 text-lg font-bold">Lanjutkan Belajar</h2>
                    </div>
                    <span class="bg-navy-50 text-ink-soft text-[11px] font-bold px-3 py-1 rounded-full">Terakhir Diakses 2 jam yang lalu</span>
                </div>

                <div class="bg-navy-50 rounded-xl p-4 flex gap-5 items-center">
                    <div class="relative w-36 h-28 rounded-lg bg-navy-800 overflow-hidden shrink-0">
                        <div class="absolute inset-0 bg-gradient-to-t from-navy-800/80 to-transparent"></div>
                        <span class="absolute left-2 bottom-2 bg-white/90 text-navy-950 text-[11px] font-bold px-2 py-0.5 rounded">MODUL 3.4</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="bg-brand-greenlight/40 text-brand-green text-[11px] font-bold px-2.5 py-0.5 rounded-full">Matematika Saintek</span>
                            <span class="text-ink-muted text-[11px] font-bold">• SNBT Fokus</span>
                        </div>
                        <h3 class="text-navy-950 font-bold text-lg mt-1.5 truncate">Kalkulus Integral & Penerapan Luas Bidang</h3>
                        <p class="text-ink-soft text-xs mt-1 truncate">Bab 4: Teknik Substitusi Aljabar & Trigonometri • Video Modul</p>
                        <p class="text-ink-soft text-[11px] font-bold mt-2">Tutor: <span class="text-navy-900 font-normal">Dr. Hendra Saputra, M.Sc.</span> (Master Tutor UI)</p>
                    </div>
                </div>

                <div class="flex flex-col gap-2 pt-2">
                    <div class="flex items-center justify-between text-[11px] font-bold">
                        <span class="text-brand-green">68% Selesai</span>
                        <span class="text-ink-soft">Tersisa 18 Menit dari 45 Menit</span>
                    </div>
                    <div class="bg-navy-100 h-2.5 rounded-full overflow-hidden">
                        <div class="bg-brand-green h-full rounded-full" style="width:68%"></div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between pt-6">
                <button class="bg-navy-800 text-white text-sm font-semibold px-6 py-3 rounded-full shadow-sm hover:bg-navy-950 transition flex items-center gap-2">
                    Lanjutkan Belajar
                    <svg class="size-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </button>
                <button class="bg-navy-100 text-navy-950 text-sm font-semibold px-5 py-3 rounded-full hover:bg-navy-100/70 transition flex items-center gap-2">
                    <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/></svg>
                    Lihat Silabus Lengkap
                </button>
            </div>
        </div>

        {{-- Weekly target ring --}}
        <div class="lg:col-span-4 bg-white rounded-2xl shadow-[0px_4px_20px_-2px_rgba(15,27,76,0.05)] p-7 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3">
                    <h2 class="text-navy-950 text-lg font-bold leading-tight">Target<br>Mingguan</h2>
                    <span class="bg-navy-50 text-ink-soft text-[11px] font-bold px-2.5 py-1 rounded-full text-center leading-tight">Mg ke-3<br>Feb</span>
                </div>

                <div class="relative flex items-center justify-center py-4">
                    <svg class="size-36 -rotate-90" viewBox="0 0 120 120">
                        <circle cx="60" cy="60" r="52" fill="none" stroke="#eff4ff" stroke-width="12"/>
                        <circle cx="60" cy="60" r="52" fill="none" stroke="#006d30" stroke-width="12"
                                stroke-dasharray="326.7" stroke-dashoffset="{{ round(326.7 * (1 - 0.74)) }}" stroke-linecap="round"/>
                    </svg>
                    <div class="absolute flex flex-col items-center">
                        <span class="text-navy-950 text-2xl font-black">74%</span>
                        <span class="text-ink-soft text-[11px] font-medium">Tercapai</span>
                    </div>
                </div>

                <div class="flex flex-col gap-2.5 pt-2">
                    <div class="bg-navy-50 rounded-lg px-3 py-2 flex items-center justify-between">
                        <span class="text-ink-soft text-[11px] font-bold">Target Jam Belajar</span>
                        <span class="text-navy-950 text-[11px] font-bold">18.5 <span class="text-ink-muted font-normal">/ 25 Jam</span></span>
                    </div>
                    <div class="bg-navy-50 rounded-lg px-3 py-2 flex items-center justify-between">
                        <span class="text-ink-soft text-[11px] font-bold">Modul Tuntas</span>
                        <span class="text-navy-950 text-[11px] font-bold">14 <span class="text-ink-muted font-normal">/ 19 Modul</span></span>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-center pt-4">
                <span class="bg-brand-greenlight/40 text-brand-greentext text-xs font-bold px-4 py-1.5 rounded-full">Status: On Track 🎯</span>
            </div>
        </div>
    </div>

    {{-- Kelas Saya --}}
    <div class="flex flex-col gap-4">
        <div class="flex items-end justify-between">
            <div>
                <h2 class="text-navy-950 text-xl font-bold">Kelas Saya</h2>
                <p class="text-ink-soft text-xs mt-0.5">Akses cepat ke materi dan ruang belajar aktif kamu semester ini</p>
            </div>
            <a href="{{ route('dashboard.kelas') }}" class="text-navy-950 text-xs font-bold flex items-center gap-1">
                Lihat Semua (6)
                <svg class="size-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach ($classes as $class)
                <div class="bg-white rounded-xl shadow-[0px_4px_20px_-2px_rgba(15,27,76,0.05)] p-5 flex flex-col justify-between">
                    <div class="flex flex-col gap-0.5">
                        <div class="flex items-start justify-between">
                            <span class="flex items-center justify-center size-10 rounded-lg {{ $class['iconBg'] }}"></span>
                            <span class="bg-navy-50 text-ink-muted text-[11px] font-semibold px-2 py-0.5 rounded">{{ $class['tag'] }}</span>
                        </div>
                        <h3 class="text-navy-950 font-bold text-lg mt-2.5">{{ $class['name'] }}</h3>
                        <p class="text-ink-soft text-[11px] font-bold">{{ $class['desc'] }}</p>

                        <div class="flex flex-col gap-1.5 pt-3.5">
                            <div class="flex items-center justify-between">
                                <span class="text-{{ $class['color'] }} text-[11px] font-semibold">{{ $class['pct'] }}%</span>
                                <span class="text-ink-muted text-[11px] font-bold">{{ $class['progressText'] }}</span>
                            </div>
                            <div class="bg-navy-100 h-1.5 rounded-full overflow-hidden">
                                <div class="bg-{{ $class['color'] }} h-full rounded-full" style="width:{{ $class['pct'] }}%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-navy-50 rounded-lg px-3 py-2 mt-5">
                        <p class="text-navy-900 text-[11px] font-medium">{{ $class['note'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection