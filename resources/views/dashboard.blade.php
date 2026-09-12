@extends('layouts.app')

@section('title', 'Dashboard Siswa — StudyServer')

@php
    $navItems = [
        ['label' => 'Dashboard', 'active' => true],
        ['label' => 'Kelas Saya', 'active' => false],
        ['label' => 'Katalog', 'active' => false],
        ['label' => 'Nilai', 'active' => false],
        ['label' => 'Laporan', 'active' => false],
        ['label' => 'Pengaturan', 'active' => false],
    ];

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

@section('content')
<div class="min-h-screen bg-navy-50">

    {{-- ============ SIDEBAR ============ --}}
    <aside class="fixed inset-y-0 left-0 w-72 bg-navy-800 shadow-xl flex flex-col justify-between z-40">
        <div>
            <div class="flex items-center gap-3 h-20 px-6">
                <span class="flex items-center justify-center size-8 rounded-md bg-white/10 shrink-0">
                    <svg class="size-5 text-brand-greenlight" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.25278V19.25M12 6.25278C10.8321 5.47686 9.24649 5 7.5 5C5.75351 5 4.16789 5.47686 3 6.25278V19.25C4.16789 18.4741 5.75351 18 7.5 18C9.24649 18 10.8321 18.4741 12 19.25M12 6.25278C13.1679 5.47686 14.7535 5 16.5 5C18.2465 5 19.8321 5.47686 21 6.25278V19.25C19.8321 18.4741 18.2465 18 16.5 18C14.7535 18 13.1679 18.4741 12 19.25"/></svg>
                </span>
                <div>
                    <p class="text-white font-bold text-lg leading-tight">StudyServer</p>
                    <p class="text-navy-400 text-[11px] font-bold tracking-widest uppercase">Bimbel Adaptif</p>
                </div>
            </div>

            <nav class="flex flex-col gap-1 px-4 mt-4">
                @foreach ($navItems as $item)
                    <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl transition
                        {{ $item['active'] ? 'bg-navy-600 text-white shadow-lg' : 'text-navy-300 hover:bg-white/5 font-semibold text-sm' }}">
                        <span class="size-4 rounded bg-white/20 shrink-0"></span>
                        <span class="{{ $item['active'] ? 'text-base' : 'text-sm font-semibold' }}">{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </nav>
        </div>

        <div class="flex flex-col gap-3 p-4">
            <div class="bg-black/25 rounded-xl p-3.5 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <span class="flex items-center justify-center size-8 rounded-lg bg-brand-green/30 shrink-0">
                        <svg class="size-4 text-brand-greenlight" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </span>
                    <div>
                        <p class="text-white text-xs font-semibold">Paket UTBK Pro</p>
                        <p class="text-navy-400 text-[11px] font-bold">Aktif s/d Mei 2025</p>
                    </div>
                </div>
            </div>
            <a href="{{ route('login') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-navy-300 text-xs font-semibold hover:bg-white/5 transition">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Keluar
            </a>
        </div>
    </aside>

    {{-- ============ MAIN AREA ============ --}}
    <div class="pl-72">

        {{-- Topbar --}}
        <header class="sticky top-0 z-30 h-20 bg-white/90 backdrop-blur shadow-[0px_1px_8px_0px_rgba(15,27,76,0.04)] flex items-center justify-between px-8">
            <div class="relative max-w-md w-full">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 size-4 text-ink-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" placeholder="Cari materi, kelas, tugas, atau tutor..."
                       class="w-full bg-navy-50 rounded-full pl-11 pr-16 py-2.5 text-xs text-navy-900 placeholder:text-ink-muted focus:outline-none focus:ring-2 focus:ring-navy-100">
                <kbd class="absolute right-3 top-1/2 -translate-y-1/2 bg-navy-100 text-ink-soft text-[11px] font-bold px-1.5 py-0.5 rounded shadow-sm">Ctrl + K</kbd>
            </div>

            <div class="flex items-center gap-4">
                <span class="hidden sm:flex items-center gap-2 bg-navy-50 rounded-full px-3 py-1.5">
                    <svg class="size-3 text-ink-soft" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span class="text-[11px] font-bold text-ink-soft">Semester Genap 2024/2025</span>
                </span>
                <button class="relative flex items-center justify-center size-10 rounded-full hover:bg-navy-50 transition">
                    <svg class="size-[18px] text-ink-soft" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2a2 2 0 01-.6 1.4L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    <span class="absolute top-2 right-2 size-2 rounded-full bg-red-600 ring-2 ring-white"></span>
                </button>
                <span class="hidden sm:block h-6 w-px bg-navy-100"></span>
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <img src="https://www.figma.com/api/mcp/asset/4b9001eb-320b-418b-b43a-9ddbb0503794.png" alt="Foto profil"
                             class="size-8 rounded-full object-cover ring-2 ring-navy-800/20">
                        <span class="absolute -bottom-0.5 -right-0.5 size-2.5 rounded-full bg-brand-green ring-2 ring-white"></span>
                    </div>
                    <div class="hidden sm:block">
                        <p class="text-navy-900 text-sm font-semibold leading-tight">Brian Pratama</p>
                        <p class="text-ink-soft text-[11px] font-bold">Siswa SMA</p>
                    </div>
                    <svg class="size-2.5 text-ink-soft" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </div>
        </header>

        <main class="px-8 py-8 flex flex-col gap-8">

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
                    <a href="#" class="text-navy-950 text-xs font-bold flex items-center gap-1">
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

        </main>
    </div>
</div>
@endsection
