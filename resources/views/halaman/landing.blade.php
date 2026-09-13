@extends('layout.app')

@section('title', 'PintarKuy — Belajar lebih terarah, berkembang lebih cepat')

@section('content')

{{-- ============ HEADER ============ --}}
<header class="bg-navy-800 sticky top-0 z-50 shadow-[0px_4px_20px_-2px_rgba(15,27,76,0.15)]">
    <div class="w-full flex items-center justify-between h-20 px-6 md:px-12">
        <a href="{{ url('/') }}" class="flex items-center gap-3" title="PintarKuy">
            <img src="{{ asset('assets/images/logopintar.png') }}" alt="PintarKuy" class="h-9 w-auto shrink-0">
            <span class="text-white font-bold text-xl tracking-tight">PintarKuy</span>
        </a>
        <nav class="hidden md:flex items-center gap-8">
            <a href="{{ url('/') }}" class="text-white font-bold border-b-2 border-brand-greenlight pb-1.5">Beranda</a>
            <a href="{{ route('about') }}" class="text-navy-400 text-sm font-semibold hover:text-white transition">Tentang</a>
            <a href="{{ route('classes') }}" class="text-navy-400 text-sm font-semibold hover:text-white transition">Kelas</a>
            <a href="{{ route('contact') }}" class="text-navy-400 text-sm font-semibold hover:text-white transition">Kontak</a>
        </nav>
        @php
            $dashHomeUrl = Auth::check()
                ? (Auth::user()->isGuru() ? route('guru.dashboard') : route('dashboard'))
                : route('login');
            $dashKatalogUrl = Auth::check()
                ? (Auth::user()->isGuru() ? route('guru.dashboard') : route('dashboard.katalog'))
                : route('register');
            $userFoto = Auth::check() ? (Auth::user()->foto ?? 'https://www.figma.com/api/mcp/asset/4b9001eb-320b-418b-b43a-9ddbb0503794.png') : '';
        @endphp
        <div class="flex items-center gap-4" id="landingAuth">
            @auth
                <a href="{{ $dashHomeUrl }}" class="flex items-center gap-2 bg-white/10 ring-1 ring-white/20 pl-1.5 pr-4 py-1.5 rounded-full hover:bg-white/20 transition" title="Buka Dashboard">
                    <img src="{{ $userFoto }}" alt="" class="size-7 rounded-full object-cover">
                    <span class="text-white text-sm font-bold">{{ explode(' ', Auth::user()->name)[0] }}</span>
                </a>
            @else
                <a href="{{ route('login') }}" class="text-white text-sm font-semibold px-3 py-2 hover:text-navy-300 transition">Masuk</a>
                <a href="{{ route('register') }}" class="bg-white text-navy-950 text-sm font-semibold px-6 py-2.5 rounded-full shadow hover:bg-navy-50 transition">Daftar</a>
            @endauth
        </div>
    </div>
</header>

{{-- ============ HERO ============ --}}
<section class="relative bg-gradient-to-b from-navy-50 via-white to-navy-50 overflow-hidden pt-14 pb-24 px-6 md:px-12">
    <div class="absolute -top-32 -left-32 size-96 rounded-full bg-brand-greenlight/20 blur-3xl"></div>
    <div class="absolute top-20 right-0 size-[480px] rounded-full bg-navy-100/30 blur-3xl"></div>

    <div class="relative w-full grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
        <div class="lg:col-span-7 flex flex-col items-start gap-6">
            <span class="reveal inline-flex items-center gap-2 bg-navy-50 border border-black/10 rounded-full px-4 py-1.5 shadow-sm" style="animation-delay:0.05s">
                <span class="relative flex size-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-green opacity-75"></span>
                    <span class="relative inline-flex rounded-full size-2.5 bg-brand-green"></span>
                </span>
                <span class="text-[12px] font-semibold text-navy-950 tracking-wide">Pendaftaran Gelombang UTBK-SNBT 2026 Dibuka</span>
            </span>

            <h1 class="reveal text-4xl md:text-5xl font-extrabold text-navy-900 leading-tight tracking-tight" style="animation-delay:0.15s">
                Belajar lebih terarah.<br>
                <span class="bg-gradient-to-r from-navy-800 via-navy-950 to-brand-green bg-clip-text text-transparent">Berkembang lebih cepat.</span>
            </h1>

            <p class="reveal text-ink-soft text-base leading-relaxed max-w-xl" style="animation-delay:0.25s">
                Platform bimbingan belajar interaktif dengan tutor lulusan PTN terbaik, ribuan latihan
                soal adaptif, dan simulasi tryout berstandar resmi untuk membantumu lolos kampus impian.
            </p>

            <div class="reveal flex flex-wrap items-center gap-4 pt-2" style="animation-delay:0.35s">
                <a href="{{ route('register') }}" data-auth-cta class="inline-flex items-center gap-2 bg-navy-800 text-white font-semibold text-sm px-8 py-3.5 rounded-full shadow-lg shadow-navy-800/20 hover:bg-navy-950 transition">
                    Mulai Belajar
                    <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
                <a href="#kelas" class="inline-flex items-center gap-2 bg-white border border-black/10 text-navy-950 font-semibold text-sm px-7 py-3.5 rounded-full shadow-sm hover:bg-navy-50 transition">
                    Jelajahi Kelas
                </a>
            </div>

<div class="reveal flex items-center gap-3 pt-2" style="animation-delay:0.45s">
                <div class="flex -space-x-2">
                    @for ($i = 0; $i < 5; $i++)
                        <span class="size-6 rounded-full bg-navy-100 border-2 border-white"></span>
                    @endfor
                </div>
                <p class="text-xs text-ink-soft">
                    <span class="font-bold text-navy-900">4.9/5</span> dari
                    <span class="text-navy-950">10.000+ ulasan</span> siswa SMA & Alumni se-Indonesia
                </p>
            </div>
        </div>

        <div class="reveal lg:col-span-5 relative flex items-center justify-center min-h-[360px]" style="animation-delay:0.3s">
            <div class="absolute inset-4 rounded-3xl bg-gradient-to-br from-brand-greenlight/30 to-navy-100/40 blur-2xl"></div>

            <div class="relative bg-white p-2.5 rounded-3xl shadow-2xl w-full max-w-md">
                <img src="https://www.figma.com/api/mcp/asset/4cbcc0bf-e804-4e9a-8e8b-fcf8b74e5af0.png"
                     alt="Siswa PintarKuy belajar online dengan laptop"
                     class="w-full rounded-2xl aspect-[454/339] object-cover">
            </div>

            {{-- Floating card: progress ring --}}
            <div class="absolute -top-6 -right-6 max-w-[210px] bg-white/95 backdrop-blur border border-white/60 rounded-2xl shadow-xl px-4 py-3.5 flex items-center gap-3.5">
                <div class="relative flex items-center justify-center size-12 shrink-0">
                    <svg class="size-12 -rotate-90" viewBox="0 0 36 36">
                        <circle cx="18" cy="18" r="16" fill="none" stroke="#dce9ff" stroke-width="3"/>
                        <circle cx="18" cy="18" r="16" fill="none" stroke="#006d30" stroke-width="3" stroke-dasharray="100" stroke-dashoffset="8" stroke-linecap="round"/>
                    </svg>
                    <span class="absolute text-[11px] font-bold text-navy-900">92%</span>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-navy-900 leading-tight">Target UTBK</p>
                    <p class="text-xs font-semibold text-brand-green">Tercapai 92%</p>
                </div>
            </div>

            {{-- Floating card: live tutoring --}}
            <div class="absolute -bottom-5 -left-6 max-w-[260px] bg-white/95 backdrop-blur border border-white/60 rounded-2xl shadow-xl px-4 py-3.5 flex items-center gap-3">
                <span class="flex items-center justify-center size-9 rounded-xl bg-brand-greenlight shrink-0">
                    <svg class="size-4 text-navy-950" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.55-2.28A1 1 0 0121 8.6v6.8a1 1 0 01-1.45.9L15 14M4 7h9a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V9a2 2 0 012-2z"/></svg>
                </span>
                <div>
                    <p class="text-[11px] font-bold text-navy-950 tracking-wide uppercase flex items-center gap-1.5">
                        <span class="size-2 rounded-full bg-brand-green"></span> Live Tutoring
                    </p>
                    <p class="text-xs font-medium text-ink-soft">TPS Matematika (15m lagi)</p>
                </div>
            </div>

            {{-- Floating card: score --}}
            <div class="absolute right-0 top-1/2 -translate-y-1/2 bg-navy-800 rounded-xl shadow-lg px-3.5 py-2.5 flex items-center gap-2.5">
                <svg class="size-[18px] text-brand-greenlight" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                <div>
                    <p class="text-[11px] font-bold text-navy-100 tracking-wide uppercase">Skor rata-rata</p>
                    <p class="text-white font-extrabold text-lg leading-none">712.5 <span class="text-brand-greenlight text-[11px] font-normal">Top 1.5%</span></p>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats strip --}}
    <div class="relative w-full -mb-8 mt-12">
        <div class="reveal bg-white rounded-2xl shadow-[0px_4px_6px_-1px_rgba(0,0,0,0.1)] p-8 grid grid-cols-1 sm:grid-cols-3 divide-y sm:divide-y-0 sm:divide-x divide-black/10">
            <div class="flex items-center gap-4 px-4 py-2">
                <span class="flex items-center justify-center size-12 rounded-2xl bg-navy-100 shrink-0">
                    <svg class="size-6 text-navy-800" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4"/></svg>
                </span>
                <div>
                    <p class="text-2xl font-extrabold text-navy-950">1,200+ Siswa</p>
                    <p class="text-xs font-medium text-ink-soft">Aktif belajar & simulasi harian</p>
                </div>
            </div>
            <div class="flex items-center gap-4 px-4 py-2">
                <span class="flex items-center justify-center size-12 rounded-2xl bg-brand-greenlight/40 shrink-0">
                    <svg class="size-6 text-brand-greentext" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.42A12.083 12.083 0 0112 21a12.08 12.08 0 01-6.16-10.42L12 14z"/></svg>
                </span>
                <div>
                    <p class="text-2xl font-extrabold text-navy-950">150+ Pengajar</p>
                    <p class="text-xs font-medium text-ink-soft">Master tutor lulusan UI, ITB, UGM</p>
                </div>
            </div>
            <div class="flex items-center gap-4 px-4 py-2">
                <span class="flex items-center justify-center size-12 rounded-2xl bg-navy-100 shrink-0">
                    <svg class="size-6 text-navy-800" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.25278V19.25M12 6.25278C10.8321 5.47686 9.24649 5 7.5 5C5.75351 5 4.16789 5.47686 3 6.25278V19.25C4.16789 18.4741 5.75351 18 7.5 18C9.24649 18 10.8321 18.4741 12 19.25M12 6.25278C13.1679 5.47686 14.7535 5 16.5 5C18.2465 5 19.8321 5.47686 21 6.25278V19.25C19.8321 18.4741 18.2465 18 16.5 18C14.7535 18 13.1679 18.4741 12 19.25"/></svg>
                </span>
                <div>
                    <p class="text-2xl font-extrabold text-navy-950">300+ Kelas</p>
                    <p class="text-xs font-medium text-ink-soft">Modul interaktif & bank soal HOTS</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============ TRUSTED BY STRIP ============ --}}
<section class="bg-white pt-20 pb-10 px-6 md:px-12">
    <div class="w-full flex flex-col items-center gap-8">
        <p class="reveal text-ink-muted text-xs font-bold tracking-widest uppercase text-center">Dipercaya universitas & institusi pendidikan di seluruh Indonesia</p>
        <div class="reveal w-full grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-6 items-center" style="animation-delay:0.1s">
            @foreach ([
                ['U-27', 'Univ. Negeri 27', 'bg-navy-100'],
                ['S', 'SMA Juara', 'bg-amber-100'],
                ['K', 'Kampus Merdeka', 'bg-brand-greenlight/40'],
                ['P', 'Pesantren Teknologi', 'bg-purple-100'],
                ['I', 'Institut Inovasi', 'bg-navy-100'],
                ['C', 'Cendekia Bangsa', 'bg-amber-100'],
            ] as [$mark, $name, $bg])
            <div class="flex items-center justify-center gap-2.5 opacity-70 hover:opacity-100 transition">
                <span class="flex items-center justify-center size-9 rounded-xl {{ $bg }} font-black text-navy-950 text-sm">{{ $mark }}</span>
                <span class="text-navy-400 font-bold text-sm tracking-tight">{{ $name }}</span>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============ FEATURE GRID ============ --}}
<section id="kelas" class="bg-navy-50 pt-24 pb-16 px-6 md:px-12">
    <div class="w-full flex flex-col items-center gap-16">
        <div class="reveal max-w-2xl flex flex-col items-center text-center gap-4">
            <span class="bg-navy-100 text-navy-950 text-[11px] font-bold tracking-widest uppercase px-3.5 py-1 rounded-full">Metodologi Belajar Modern</span>
            <h2 class="text-3xl md:text-4xl font-extrabold text-navy-900 tracking-tight">Kenapa pilih PintarKuy?</h2>
            <p class="text-ink-soft">Kami merancang ekosistem belajar komprehensif yang mengombinasikan keunggulan pedagogi tatap muka dengan fleksibilitas teknologi adaptif.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 w-full">
            {{-- Card 1 --}}
            <div class="reveal bg-white rounded-2xl p-8 shadow-[0px_4px_20px_-2px_rgba(15,27,76,0.06)] flex flex-col justify-between group">
                <div class="flex flex-col gap-6">
                    <div class="flex items-center justify-between">
                        <span class="flex items-center justify-center size-14 rounded-2xl bg-navy-800 shadow-lg">
                            <svg class="size-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.25278V19.25M12 6.25278C10.8321 5.47686 9.24649 5 7.5 5C5.75351 5 4.16789 5.47686 3 6.25278V19.25C4.16789 18.4741 5.75351 18 7.5 18C9.24649 18 10.8321 18.4741 12 19.25M12 6.25278C13.1679 5.47686 14.7535 5 16.5 5C18.2465 5 19.8321 5.47686 21 6.25278V19.25C19.8321 18.4741 18.2465 18 16.5 18C14.7535 18 13.1679 18.4741 12 19.25"/></svg>
                        </span>
                        <span class="bg-navy-100 text-navy-950 text-[11px] font-bold px-3 py-1 rounded-full">Kurikulum 2026</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-xl text-navy-900 mb-2.5">Kelas Berkualitas</h3>
                        <p class="text-sm text-ink-soft leading-relaxed">Kurikulum terstruktur sesuai standar BPPP SNBT terbaru, diajarkan langsung oleh tutor master berprestasi dengan penjelasan konsep mendalam dan trik cepat 20 detik.</p>
                    </div>
                </div>
                <div class="border-t border-navy-50 mt-6 pt-6 flex items-center justify-between">
                    <a href="{{ route('classes') }}" class="text-xs font-semibold text-navy-950 flex items-center gap-1 hover:text-brand-green transition">
                        Lihat silabus materi
                        <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>

            {{-- Card 2 --}}
            <div class="reveal bg-white rounded-2xl p-8 shadow-[0px_4px_20px_-2px_rgba(15,27,76,0.06)] flex flex-col justify-between group" style="animation-delay:0.15s">
                <div class="flex flex-col gap-6">
                    <div class="flex items-center justify-between">
                        <span class="flex items-center justify-center size-14 rounded-2xl bg-brand-green shadow-lg">
                            <svg class="size-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </span>
                        <span class="bg-brand-greenlight/40 text-brand-greentext text-[11px] font-bold px-3 py-1 rounded-full">24/7 Akses</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-xl text-navy-900 mb-2.5">Fleksibel</h3>
                        <p class="text-sm text-ink-soft leading-relaxed">Belajar kapan saja dan di mana saja. Akses rekaman kelas resolusi tinggi, modul PDF rangkuman rumus sakti, dan konsultasi PR 24/7 tanpa batas jadwal di tablet, desktop, maupun ponsel.</p>
                    </div>
                </div>
                <div class="border-t border-navy-50 mt-6 pt-6 flex items-center justify-between">
                    <a href="#uji-kemampuan" class="text-xs font-semibold text-navy-950 flex items-center gap-1 hover:text-brand-green transition">Coba simulasi multi-device
                        <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>

            {{-- Card 3 --}}
            <div class="reveal bg-white rounded-2xl p-8 shadow-[0px_4px_20px_-2px_rgba(15,27,76,0.06)] flex flex-col justify-between group" style="animation-delay:0.3s">
                <div class="flex flex-col gap-6">
                    <div class="flex items-center justify-between">
                        <span class="flex items-center justify-center size-14 rounded-2xl bg-amber-500 shadow-lg">
                            <svg class="size-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </span>
                        <span class="bg-amber-100 text-amber-900 text-[11px] font-bold px-3 py-1 rounded-full">Lolos 89.4%</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-xl text-navy-900 mb-2.5">Terpercaya</h3>
                        <p class="text-sm text-ink-soft leading-relaxed">Terbukti meloloskan ribuan siswa ke perguruan tinggi negeri impian seperti FK UI, STEI ITB, dan FEB UGM dengan akurasi prediksi skor sistem IRT hingga 98%.</p>
                    </div>
                </div>
                <div class="border-t border-navy-50 mt-6 pt-6 flex items-center justify-between">
                    <a href="{{ route('about') }}" class="text-xs font-semibold text-navy-950 flex items-center gap-1 hover:text-brand-green transition">Lihat rekap kelulusan alumni
                        <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============ HOW IT WORKS ============ --}}
<section class="relative bg-navy-950 overflow-hidden py-24 px-6 md:px-12">
    <div class="absolute -top-32 right-0 size-[420px] rounded-full bg-brand-green/20 blur-3xl"></div>
    <div class="absolute bottom-0 -left-24 size-80 rounded-full bg-navy-800 blur-3xl"></div>

    <div class="relative w-full flex flex-col items-center gap-16">
        <div class="reveal max-w-2xl flex flex-col items-center text-center gap-4">
            <span class="bg-white/10 text-brand-greenlight text-[11px] font-bold tracking-widest uppercase px-3.5 py-1 rounded-full backdrop-blur">Mulai Belajar dalam 3 Langkah</span>
            <h2 class="text-3xl md:text-4xl font-extrabold text-white tracking-tight">Gimana cara mulai di PintarKuy?</h2>
            <p class="text-navy-300">Tidak butuh pengalaman atau persiapan khusus. Cukup daftar dan biarkan sistem adaptif kami menyusun rencana belajarmu.</p>
        </div>

        <div class="w-full grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach ([
                ['1', 'Daftar & Isi Profil', 'Buat akun gratis dan tentukan jurusan impianmu. Sistem akan menyusun peta kemampuan awal lewat asesmen singkat 10 menit.', 'M02 12 M14 2 H6 a2 2 0 0 0-2 2 v16 a2 2 0 0 0 2 2 h12 a2 2 0 0 0 2-2 V8 z M14 2 v6 h6'],
                ['2', 'Ikuti Rencana Adaptif', 'Setiap hari dapatkan materi, latihan soal, dan live class yang disesuaikan dengan level serta target skor personalmu.', 'M3 3v1.5M21 3v1.5M20.5 8H3.5M21 5.5h-18a0 0 0 0 0 0v3a0 0 0 0 0 0 0h18a0 0 0 0 0 0 0v-3zM5.5 14l1.5 1.5L9.5 13M5.5 18l1.5 1.5L9.5 17M13 13.5h5M13 17.5h5'],
                ['3', 'Lolos Kampus Impian', 'Pantau perkembangan lewat analitik real-time, ikuti tryout nasional, dan masuk jajaran dengan skor di atas passing grade.', 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
            ] as [$step, $title, $desc, $icon])
            <div class="reveal relative bg-white/5 backdrop-blur border border-white/10 rounded-2xl p-7 flex flex-col gap-5 hover:bg-white/10 hover:-translate-y-1 transition duration-300" style="animation-delay:{{ $loop->iteration * 0.12 }}s">
                <div class="flex items-center justify-between">
                    <span class="flex items-center justify-center size-12 rounded-2xl bg-gradient-to-br from-brand-green to-brand-greentext shadow-lg">
                        <svg class="size-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/></svg>
                    </span>
                    <span class="text-white/20 font-black text-5xl leading-none">{{ $step }}</span>
                </div>
                <div>
                    <h3 class="text-white font-bold text-lg mb-2">{{ $title }}</h3>
                    <p class="text-navy-300 text-sm leading-relaxed">{{ $desc }}</p>
                </div>
                <div class="h-1 w-10 rounded-full bg-brand-greenlight/60"></div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============ QUIZ TEASER ============ --}}
<section id="uji-kemampuan" class="bg-navy-50 py-16 px-6 md:px-12 scroll-mt-20">
    <div class="w-full bg-white border border-black/10 rounded-3xl shadow-xl p-8 md:p-12 flex flex-col lg:flex-row gap-10 items-center">
        <div class="reveal flex-1 flex flex-col gap-5">
            <span class="inline-flex bg-brand-greenlight text-[#00210a] text-[11px] font-bold px-3 py-1 rounded-full w-fit">Simulasi IRT Real-Time</span>
            <h2 class="text-2xl font-extrabold text-navy-950 leading-snug">Uji Kemampuan Sekarang: Soal HOTS TPS Penalaran Umum</h2>
            <p class="text-sm text-ink-soft leading-relaxed">Rasakan langsung pengalaman simulasi tryout berbobot SNBT dengan analitik real-time. Dapatkan feedback instan mengenai kelemahan dan rekomendasi materi yang perlu dipelajari ulang.</p>
            <div class="flex gap-6 items-center pt-1">
                <span class="flex items-center gap-2 text-xs font-semibold text-navy-900">
                    <svg class="size-4 text-navy-800" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Estimasi: 2 Menit
                </span>
                <span class="flex items-center gap-2 text-xs font-semibold text-navy-900">
                    <svg class="size-4 text-navy-800" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    Bobot IRT: Tinggi
                </span>
            </div>
        </div>

        <div class="reveal flex-1 w-full bg-navy-50 border border-black/10 rounded-2xl p-6" style="animation-delay:0.15s">
            @php
                $quiz = [
                    [
                        'tag' => 'Penalaran Logika',
                        'text' => 'Jika semua mahasiswa berprestasi mendapatkan beasiswa riset, dan beberapa penerima beasiswa riset lolos magang internasional di Silicon Valley, manakah simpulan yang PASTI BENAR?',
                        'correct' => 'B',
                        'options' => ['A' => 'Semua mahasiswa berprestasi pasti magang di Silicon Valley.', 'B' => 'Sebagian penerima beasiswa riset adalah mahasiswa berprestasi.', 'C' => 'Mahasiswa yang tidak berprestasi tidak bisa magang di luar negeri.'],
                    ],
                    [
                        'tag' => 'Penalaran Kuantitatif',
                        'text' => 'Perhatikan pola bilangan berikut: 2, 6, 12, 20, 30, ... Bilangan selanjutnya adalah ...',
                        'correct' => 'C',
                        'options' => ['A' => '36', 'B' => '40', 'C' => '42', 'D' => '44'],
                    ],
                    [
                        'tag' => 'Penalaran Kausal',
                        'text' => 'Jika hari hujan, Andi selalu membawa payung. Hari ini Andi tidak membawa payung. Manakah simpulan yang PASTI BENAR?',
                        'correct' => 'B',
                        'options' => ['A' => 'Hari ini hujan.', 'B' => 'Hari ini tidak hujan.', 'C' => 'Andi lupa membawa payung.'],
                    ],
                    [
                        'tag' => 'Penalaran Silogisme',
                        'text' => 'Sebagian siswa kelas XII mengikuti bimbingan belajar. Semua siswa yang mengikuti bimbingan belajar lulus ujian. Manakah simpulan yang PASTI BENAR?',
                        'correct' => 'C',
                        'options' => ['A' => 'Semua siswa kelas XII lulus ujian.', 'B' => 'Semua siswa yang lulus ujian mengikuti bimbingan belajar.', 'C' => 'Sebagian siswa kelas XII lulus ujian.'],
                    ],
                    [
                        'tag' => 'Analogi',
                        'text' => 'Akar adalah bagian dari Pohon, sama seperti Fondasi adalah bagian dari ...',
                        'correct' => 'A',
                        'options' => ['A' => 'Bangunan', 'B' => 'Ranting', 'C' => 'Kunci'],
                    ],
                ];
            @endphp

            <div id="quizQuestions" class="flex flex-col">
                @foreach ($quiz as $i => $q)
                <div class="quiz-question {{ $i > 0 ? 'hidden' : '' }}" data-correct="{{ $q['correct'] }}">
                    <div class="flex items-center justify-between border-b border-black/10 pb-3">
                        <span class="text-[11px] font-bold text-navy-800 tracking-widest uppercase">Soal {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }} / {{ str_pad(count($quiz), 2, '0', STR_PAD_LEFT) }}</span>
                        <span class="bg-navy-100 text-navy-950 text-[11px] font-semibold px-2.5 py-0.5 rounded-full">{{ $q['tag'] }}</span>
                    </div>
                    <p class="text-sm font-medium text-navy-900 leading-relaxed py-4">{{ $q['text'] }}</p>
                    <div class="flex flex-col gap-2.5">
                        @foreach ($q['options'] as $value => $option)
                        <label class="quiz-option bg-white border border-black/10 rounded-xl px-4 py-3.5 flex items-center justify-between cursor-pointer hover:border-navy-300 transition">
                            <span class="text-xs text-navy-900">{{ $value }}. {{ $option }}</span>
                            <input type="radio" name="quiz-q{{ $i }}" value="{{ $value }}" class="size-5 accent-navy-800">
                        </label>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>

            <div class="flex flex-wrap items-center gap-3 pt-4">
                <button type="button" id="quizCheck" class="inline-flex items-center gap-2 bg-navy-800 text-white text-xs font-bold px-6 py-3 rounded-full shadow-lg shadow-navy-800/20 hover:bg-navy-950 transition disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Periksa Jawaban
                </button>
                <button type="button" id="quizNext" class="hidden inline-flex items-center gap-2 bg-navy-800 text-white text-xs font-bold px-6 py-3 rounded-full shadow-lg shadow-navy-800/20 hover:bg-navy-950 transition">
                    Soal Berikutnya
                    <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </button>
                <a href="{{ route('register') }}" data-auth-cta class="inline-flex items-center gap-2 text-xs font-bold text-navy-950 hover:text-brand-green transition">
                    Daftar Gratis &amp; Uji Penuh — 2 Menit
                    <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
            <p id="quizFeedback" class="hidden min-h-4 text-xs font-semibold leading-relaxed pt-3"></p>

            <div id="quizResult" class="hidden mt-4 bg-white border border-black/10 rounded-2xl p-5 flex flex-col gap-3">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-navy-800 tracking-widest uppercase">Hasil Akhir</span>
                    <span class="bg-brand-greenlight text-[#00210a] text-[11px] font-bold px-2.5 py-0.5 rounded-full">5 Soal Selesai</span>
                </div>
                <div class="flex items-baseline gap-2">
                    <span id="quizScore" class="text-4xl font-black text-navy-950">0</span>
                    <span class="text-sm font-semibold text-navy-900">/ 100 poin</span>
                </div>
                <p id="quizScoreMessage" class="text-xs font-medium text-ink-soft leading-relaxed"></p>
                <a href="{{ route('register') }}" data-auth-cta class="text-center text-sm font-bold bg-navy-800 text-white rounded-full py-3 hover:bg-navy-950 transition">Daftar Gratis &amp; Lanjut Uji Penuh</a>
            </div>
        </div>
    </div>
</section>

{{-- ============ PROGRAMS / PRICING ============ --}}
<section id="program" class="bg-navy-50 py-24 px-6 md:px-12">
    <div class="w-full flex flex-col items-center gap-14">
        <div class="reveal max-w-2xl flex flex-col items-center text-center gap-4">
            <span class="bg-brand-greenlight text-[#00210a] text-[11px] font-bold tracking-widest uppercase px-3.5 py-1 rounded-full">Program & Paket</span>
            <h2 class="text-3xl md:text-4xl font-extrabold text-navy-900 tracking-tight">Pilih Program Sesuai Target</h2>
            <p class="text-ink-soft">Semua paket sudah termasuk akses penuh ke bank soal HOTS, live class, dan analitik IRT. Tanpa kontrak, berhenti kapan saja.</p>
        </div>

        <div class="w-full grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch">
            @php
                $plans = [
                    [
                        'key' => 'starter',
                        'name' => 'Starter',
                        'old' => 'Rp 299.000',
                        'price' => 'Rp 199.000',
                        'per' => 'bulan',
                        'tag' => null,
                        'dark' => false,
                        'feats' => ['3 sesi live class per minggu', 'Bank soal terbatas (500 soal)', '2x simulasi IRT', 'Forum diskusi komunitas'],
                    ],
                    [
                        'key' => 'utbk-pro',
                        'name' => 'UTBK Pro',
                        'old' => 'Rp 799.000',
                        'price' => 'Rp 599.000',
                        'per' => 'bulan',
                        'tag' => 'Paling Laris',
                        'dark' => true,
                        'feats' => ['Semua fitur Starter', '6.000+ soal HOTS + pembahasan', 'Tryout nasional mingguan', 'Live class tanpa batas', 'Konsultasi private tutor', 'Analitik prediksi skor IRT'],
                    ],
                    [
                        'key' => 'golden',
                        'name' => 'Golden Campus',
                        'old' => 'Rp 1.499.000',
                        'price' => 'Rp 1.199.000',
                        'per' => 'bulan',
                        'tag' => null,
                        'dark' => false,
                        'feats' => ['Semua fitur UTBK Pro', 'Mentor 1-on-1 (4x/bulan)', 'Paket tryout khusus 3 PTN', 'Revisi berkas & beasiswa', 'Grup khusus golden (max 20 siswa)'],
                    ],
                ];
            @endphp
            @foreach ($plans as $plan)
                <div class="reveal relative rounded-3xl p-8 shadow-card flex flex-col justify-between gap-8 {{ $plan['dark'] ? 'bg-navy-800 md:-translate-y-4 ring-4 ring-brand-green/30' : 'bg-white' }}" style="animation-delay:{{ $loop->iteration * 0.15 }}s">
                    @if ($plan['tag'])
                        <span class="absolute -top-3.5 left-1/2 -translate-x-1/2 bg-brand-green text-white text-[11px] font-bold tracking-wide px-4 py-1.5 rounded-full shadow-lg">{{ $plan['tag'] }}</span>
                    @endif
                    <div class="flex flex-col gap-4">
                        <div class="flex items-center justify-between">
                            <h3 class="font-bold text-xl {{ $plan['dark'] ? 'text-white' : 'text-navy-900' }}">{{ $plan['name'] }}</h3>
                            <span class="flex items-center justify-center size-10 rounded-xl {{ $plan['dark'] ? 'bg-white/10' : 'bg-navy-50' }}">
                                <svg class="size-5 {{ $plan['dark'] ? 'text-brand-greenlight' : 'text-navy-800' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <p class="text-xs font-semibold line-through {{ $plan['dark'] ? 'text-white/50' : 'text-ink-muted' }}">{{ $plan['old'] }}</p>
                            <div class="flex items-baseline gap-1.5">
                                <span class="text-4xl font-black tracking-tight {{ $plan['dark'] ? 'text-white' : 'text-navy-950' }}">{{ $plan['price'] }}</span>
                                <span class="text-xs font-semibold {{ $plan['dark'] ? 'text-white/70' : 'text-ink-muted' }}">/{{ $plan['per'] }}</span>
                            </div>
                        </div>
                        <div class="h-px w-full {{ $plan['dark'] ? 'bg-white/10' : 'bg-navy-100' }}"></div>
                        <ul class="flex flex-col gap-3">
                            @foreach ($plan['feats'] as $feat)
                                <li class="flex items-start gap-2.5 text-sm {{ $plan['dark'] ? 'text-white/85' : 'text-ink-soft' }}">
                                    <svg class="size-4 mt-0.5 shrink-0 {{ $plan['dark'] ? 'text-brand-greenlight' : 'text-brand-green' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    {{ $feat }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <a href="{{ route('login') }}" data-paket="{{ $plan['key'] }}" class="plans-cta text-center text-sm font-bold rounded-full py-3.5 transition
                        {{ $plan['dark'] ? 'bg-brand-greenlight text-navy-950 hover:bg-brand-greenlight/90 shadow-lg hover:shadow-[0_0_22px_rgba(126,252,154,0.75),0_0_50px_rgba(126,252,154,0.35)] hover:-translate-y-0.5' : 'bg-navy-800 text-white hover:bg-navy-950' }}">
                        Mulai Sekarang
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============ TESTIMONIALS ============ --}}
<section class="bg-white py-24 px-6 md:px-12">
    <div class="w-full flex flex-col items-center gap-14">
        <div class="reveal max-w-2xl flex flex-col items-center text-center gap-4">
            <span class="bg-navy-100 text-navy-950 text-[11px] font-bold tracking-widest uppercase px-3.5 py-1 rounded-full">Cerita Mereka</span>
            <h2 class="text-3xl md:text-4xl font-extrabold text-navy-900 tracking-tight">Lolos PTN Bersama PintarKuy</h2>
            <p class="text-ink-soft">Ribuan alumni sudah membuktikan. Ini sebagian dari cerita mereka — kamu berikutnya.</p>
        </div>

        <div class="w-full grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach ([
                ['Nav', 'Nabila Zahra', 'FK UI 2026', 'Skor gadja dari 480 ke 705 dalam 6 bulan. Live class-nya paling nge-zoom jelasin konsep — beda banget sama sekadar nonton video. Emak sampe nangis pas pengumuman.', 'bg-navy-900'],
                ['R', 'Rangga Prasetyo', 'STEI ITB 2026', 'Fitur analitik IRT-nya gila sih. Tiap minggu aku tau persis lemah di mana dan langsung direkomendasiin materi yang harus diulang.', 'bg-brand-green'],
                ['S', 'Salsabila Putri', 'FEB UGM 2026', 'Tryout mingguannya bikin mental juara. Jaringan internet lemot di kampung juga tetap lancar karena video-nya compact. Recommended 100%.', 'bg-navy-800'],
            ] as [$initial, $name, $role, $quote, $bg])
            <div class="reveal bg-navy-50 rounded-2xl p-7 flex flex-col gap-5 hover:-translate-y-1 transition duration-300" style="animation-delay:{{ $loop->iteration * 0.12 }}s">
                <div class="flex items-center justify-between">
                    <div class="flex gap-1">
                        @for ($i = 0; $i < 5; $i++)
                            <svg class="size-4 text-amber-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        @endfor
                    </div>
                    <span class="text-ink-muted text-[11px] font-bold">Alumni 2026</span>
                </div>
                <p class="text-navy-900 text-sm leading-relaxed">"{{ $quote }}"</p>
                <div class="flex items-center gap-3 pt-1">
                    <span class="flex items-center justify-center size-10 rounded-full {{ $bg }} text-white font-bold text-sm shrink-0">{{ $initial }}</span>
                    <div>
                        <p class="text-navy-950 font-bold text-sm">{{ $name }}</p>
                        <p class="text-ink-soft text-xs font-semibold">{{ $role }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="reveal flex items-center gap-3 pt-2">
            <div class="flex -space-x-2">
                @for ($i = 0; $i < 6; $i++)
                    <span class="size-7 rounded-full border-2 border-white {{ $i % 2 ? 'bg-brand-green' : 'bg-navy-800' }}"></span>
                @endfor
            </div>
            <p class="text-xs text-ink-soft"><span class="font-bold text-navy-900">10.000+</span> siswa telah bergabung, dan masih bertambah tiap hari</p>
        </div>
    </div>
</section>

{{-- ============ CTA BANNER ============ --}}
<section class="bg-navy-50 py-24 px-6 md:px-12">
    <div class="reveal relative w-full bg-navy-800 rounded-3xl shadow-2xl overflow-hidden p-10 md:p-16 flex flex-col md:flex-row items-center justify-between gap-8">
        <div class="absolute -bottom-24 -right-24 size-80 rounded-full bg-brand-green/30 blur-3xl"></div>
        <div class="absolute -top-20 -left-20 size-72 rounded-full bg-navy-100/20 blur-3xl"></div>

        <div class="relative max-w-xl flex flex-col gap-3">
            <span class="bg-white/10 text-brand-greenlight text-[11px] font-bold tracking-widest uppercase px-3.5 py-1 rounded-full w-fit">Promo Periode Semester Baru</span>
            <h2 class="text-3xl md:text-4xl font-extrabold text-white tracking-tight">Siap Menggapai Kampus Impianmu?</h2>
            <p class="text-navy-400 leading-relaxed">Bergabunglah bersama ribuan pejuang PTN lainnya hari ini. Mulai langsung dari paket yang sesuai targetmu dan rasakan bedanya belajar dengan sistem adaptif.</p>
        </div>
        <a href="{{ route('register') }}" data-auth-cta class="relative bg-white text-navy-950 font-bold text-sm text-center px-8 py-4 rounded-full shadow-lg shrink-0 hover:bg-navy-50 hover:shadow-[0_0_24px_rgba(255,255,255,0.7),0_0_60px_rgba(126,252,154,0.4)] hover:-translate-y-0.5 transition">
            Daftar Sekarang
        </a>
    </div>
</section>

{{-- ============ FAQ ============ --}}
<section class="bg-navy-50 py-24 px-6 md:px-12">
    <div class="w-full max-w-3xl mx-auto flex flex-col items-center gap-12">
        <div class="reveal flex flex-col items-center text-center gap-4">
            <span class="bg-navy-100 text-navy-950 text-[11px] font-bold tracking-widest uppercase px-3.5 py-1 rounded-full">Bantuan</span>
            <h2 class="text-3xl md:text-4xl font-extrabold text-navy-900 tracking-tight">Pertanyaan yang Sering Ditanya</h2>
        </div>

        <div class="w-full flex flex-col gap-3">
            @foreach ([
                ['Apakah ada paket gratis?', 'Tidak ada paket gratis. Semua paket berbayar — bahkan untuk mencoba kamu cukup mulai dari paket Starter dengan tarif terjangkau Rp 199.000/bulan. Kebijakan ini menjaga keadilan dan mencegah penyalahgunaan lewat pendaftaran akun ganda.', true],
                ['Apakah pintarkuy cocok buat siswa SMA biasa?', 'Tentu. Sistem adaptif kami menyesuaikan level kesulitan soal dengan kemampuan awalmu. Tidak masalah mau rantau kelas 10, 11, atau 12 — rencana belajar dibuat khusus untukmu.', false],
                ['Bagaimana cara konsultasi dengan tutor?', 'Melalui fitur Tanya Tutor di aplikasi, kamu bisa mengirim foto soal kapan saja. Balasan rata-rata datang di bawah 30 menit di jam aktif, dan untuk paket Golden tersedia sesi konsultasi 1-on-1 via video call.', false],
                ['Soal dan materi memakai kurikulum yang mana?', 'Semua materi disusun mengikuti standar BPPP SNBT terbaru plus kurikulum merdeka untuk pendampingan sekolah. Bank soal diperbarui setiap bulan menyusul perubahan pola ujian.', false],
                ['Bisakah berhenti kapan saja?', 'Bisa. Tidak ada ikatan kontrak. Kamu hanya membayar untuk periode yang berjalan, dan bisa berhenti kapan pun dari halaman pengaturan akun.', false],
            ] as [$q, $a, $open])
            <details class="reveal group bg-white rounded-2xl shadow-card overflow-hidden" {{ $open ? 'open' : '' }} style="animation-delay:{{ $loop->iteration * 0.08 }}s">
                <summary class="flex items-center justify-between gap-4 p-6 cursor-pointer list-none select-none">
                    <span class="text-navy-950 font-bold text-base leading-snug">{{ $q }}</span>
                    <span class="flex items-center justify-center size-7 rounded-full bg-navy-50 shrink-0 transition rotate-0 group-open:rotate-45 group-open:bg-brand-greenlight">
                        <svg class="size-3.5 text-navy-950" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    </span>
                </summary>
                <div class="px-6 pb-6 -mt-1">
                    <div class="h-px w-full bg-navy-100 mb-4"></div>
                    <p class="text-ink-soft text-sm leading-relaxed">{{ $a }}</p>
                </div>
            </details>
            @endforeach
        </div>
    </div>
</section>

{{-- ============ FOOTER ============ --}}
<footer class="bg-navy-800">
    <div class="w-full px-6 md:px-12 pt-16 pb-12 flex flex-col gap-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
            <div class="flex flex-col gap-4">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('assets/images/logopintar.png') }}" alt="PintarKuy" class="h-9 w-auto">
                    <span class="text-white font-bold text-xl">PintarKuy</span>
                </div>
                <p class="text-navy-400 text-xs leading-relaxed">Platform bimbingan belajar digital terdepan untuk persiapan UTBK-SNBT dan ujian sekolah dengan teknologi adaptif berstandar nasional.</p>
            </div>
            <div class="flex flex-col gap-3">
                <h4 class="text-white text-sm font-semibold tracking-widest uppercase">Navigasi</h4>
                <a href="{{ url('/') }}" class="text-navy-400 text-sm hover:text-white transition">Beranda</a>
                <a href="{{ route('about') }}" class="text-navy-400 text-sm hover:text-white transition">Tentang Kami</a>
                <a href="{{ route('classes') }}" class="text-navy-400 text-sm hover:text-white transition">Program Kelas</a>
                <a href="{{ route('contact') }}" class="text-navy-400 text-sm hover:text-white transition">Hubungi Kami</a>
            </div>
            <div class="flex flex-col gap-3">
                <h4 class="text-white text-sm font-semibold tracking-widest uppercase">Program Unggulan</h4>
                <a href="#uji-kemampuan" class="text-navy-400 text-sm hover:text-white transition">Simulasi UTBK/SNBT</a>
                <a href="{{ route('classes') }}" class="text-navy-400 text-sm hover:text-white transition">Bimbel Intensif SMA</a>
                <a href="{{ route('classes') }}" class="text-navy-400 text-sm hover:text-white transition">Bank Soal &amp; Pembahasan</a>
                <a href="{{ route('classes') }}" class="text-navy-400 text-sm hover:text-white transition">Live Tutoring Interaktif</a>
            </div>
            <div class="flex flex-col gap-3">
                <h4 class="text-white text-sm font-semibold tracking-widest uppercase">Hubungi Kami</h4>
                <p class="text-navy-400 text-xs leading-relaxed">Jakarta Selatan, DKI Jakarta<br>support@PintarKuy.id<br>+62 (021) 8899-2345</p>
                <div class="flex gap-3 pt-1">
                    @for ($i = 0; $i < 3; $i++)
                        <span class="flex items-center justify-center size-9 rounded-full bg-navy-950"></span>
                    @endfor
                </div>
            </div>
        </div>
        <div class="border-t border-white/10 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-navy-400 text-xs">© {{ date('Y') }} PT PintarKuy Edukasi Indonesia. Hak Cipta Dilindungi.</p>
            <div class="flex gap-6">
                <a href="#" class="text-navy-400 text-xs hover:text-white transition">Kebijakan Privasi</a>
                <a href="#" class="text-navy-400 text-xs hover:text-white transition">Syarat & Ketentuan</a>
            </div>
        </div>
    </div>
</footer>

@endsection

@push('scripts')
    <script>
        window.pintarKuyDashUrl = @json($dashKatalogUrl);
        window.pintarKuyDashHomeUrl = @json($dashHomeUrl);
    </script>
    @vite(['resources/js/halaman/landing.js'])
@endpush
