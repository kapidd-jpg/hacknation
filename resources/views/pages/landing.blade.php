@extends('layouts.app')

@section('title', 'StudyServer — Belajar lebih terarah, berkembang lebih cepat')

@section('content')

{{-- ============ HEADER ============ --}}
<header class="bg-navy-800 sticky top-0 z-50 shadow-[0px_4px_20px_-2px_rgba(15,27,76,0.15)]">
    <div class="max-w-[1280px] mx-auto flex items-center justify-between h-20 px-6 md:px-12">
        <a href="{{ url('/') }}" class="flex items-center gap-3">
            <span class="flex items-center justify-center size-8 rounded-md bg-white/10">
                <svg class="size-5 text-brand-greenlight" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.25278V19.25M12 6.25278C10.8321 5.47686 9.24649 5 7.5 5C5.75351 5 4.16789 5.47686 3 6.25278V19.25C4.16789 18.4741 5.75351 18 7.5 18C9.24649 18 10.8321 18.4741 12 19.25M12 6.25278C13.1679 5.47686 14.7535 5 16.5 5C18.2465 5 19.8321 5.47686 21 6.25278V19.25C19.8321 18.4741 18.2465 18 16.5 18C14.7535 18 13.1679 18.4741 12 19.25"/></svg>
            </span>
            <span class="text-white font-bold text-xl tracking-tight">StudyServer</span>
        </a>
        <nav class="hidden md:flex items-center gap-8">
            <a href="{{ url('/') }}" class="text-white font-bold border-b-2 border-brand-greenlight pb-1.5">Beranda</a>
            <a href="#" class="text-navy-400 text-sm font-semibold hover:text-white transition">Tentang</a>
            <a href="#" class="text-navy-400 text-sm font-semibold hover:text-white transition">Kelas</a>
            <a href="#" class="text-navy-400 text-sm font-semibold hover:text-white transition">Kontak</a>
        </nav>
        <div class="flex items-center gap-4">
            <a href="{{ route('login') }}" class="text-white text-sm font-semibold px-3 py-2 hover:text-navy-300 transition">Masuk</a>
            <a href="#" class="bg-white text-navy-950 text-sm font-semibold px-6 py-2.5 rounded-full shadow hover:bg-navy-50 transition">Daftar</a>
        </div>
    </div>
</header>

{{-- ============ HERO ============ --}}
<section class="relative bg-gradient-to-b from-navy-50 via-white to-navy-50 overflow-hidden pt-14 pb-24 px-6 md:px-12">
    <div class="absolute -top-32 -left-32 size-96 rounded-full bg-brand-greenlight/20 blur-3xl"></div>
    <div class="absolute top-20 right-0 size-[480px] rounded-full bg-navy-100/30 blur-3xl"></div>

    <div class="relative max-w-[1280px] mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
        <div class="lg:col-span-7 flex flex-col items-start gap-6">
            <span class="inline-flex items-center gap-2 bg-navy-50 border border-black/10 rounded-full px-4 py-1.5 shadow-sm">
                <span class="relative flex size-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-green opacity-75"></span>
                    <span class="relative inline-flex rounded-full size-2.5 bg-brand-green"></span>
                </span>
                <span class="text-[12px] font-semibold text-navy-950 tracking-wide">Pendaftaran Gelombang UTBK-SNBT 2025 Dibuka</span>
            </span>

            <h1 class="text-4xl md:text-5xl font-extrabold text-navy-900 leading-tight tracking-tight">
                Belajar lebih terarah.<br>
                <span class="bg-gradient-to-r from-navy-800 via-navy-950 to-brand-green bg-clip-text text-transparent">Berkembang lebih cepat.</span>
            </h1>

            <p class="text-ink-soft text-base leading-relaxed max-w-xl">
                Platform bimbingan belajar interaktif dengan tutor lulusan PTN terbaik, ribuan latihan
                soal adaptif, dan simulasi tryout berstandar resmi untuk membantumu lolos kampus impian.
            </p>

            <div class="flex flex-wrap items-center gap-4 pt-2">
                <a href="{{ route('register') }}" class="inline-flex items-center gap-2 bg-navy-800 text-white font-semibold text-sm px-8 py-3.5 rounded-full shadow-lg shadow-navy-800/20 hover:bg-navy-950 transition">
                    Mulai Belajar
                    <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
                <a href="#kelas" class="inline-flex items-center gap-2 bg-white border border-black/10 text-navy-950 font-semibold text-sm px-7 py-3.5 rounded-full shadow-sm hover:bg-navy-50 transition">
                    Jelajahi Kelas
                </a>
            </div>

            <div class="flex items-center gap-3 pt-2">
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

        <div class="lg:col-span-5 relative flex items-center justify-center min-h-[360px]">
            <div class="absolute inset-4 rounded-3xl bg-gradient-to-br from-brand-greenlight/30 to-navy-100/40 blur-2xl"></div>

            <div class="relative bg-white p-2.5 rounded-3xl shadow-2xl w-full max-w-md">
                <img src="https://www.figma.com/api/mcp/asset/4cbcc0bf-e804-4e9a-8e8b-fcf8b74e5af0.png"
                     alt="Siswa StudyServer belajar online dengan laptop"
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
    <div class="relative max-w-[1152px] mx-auto -mb-8 mt-12">
        <div class="bg-white rounded-2xl shadow-[0px_4px_6px_-1px_rgba(0,0,0,0.1)] p-8 grid grid-cols-1 sm:grid-cols-3 divide-y sm:divide-y-0 sm:divide-x divide-black/10">
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

{{-- ============ FEATURE GRID ============ --}}
<section id="kelas" class="bg-navy-50 pt-24 pb-16 px-6 md:px-12">
    <div class="max-w-[1280px] mx-auto flex flex-col items-center gap-16">
        <div class="max-w-2xl flex flex-col items-center text-center gap-4">
            <span class="bg-navy-100 text-navy-950 text-[11px] font-bold tracking-widest uppercase px-3.5 py-1 rounded-full">Metodologi Belajar Modern</span>
            <h2 class="text-3xl md:text-4xl font-extrabold text-navy-900 tracking-tight">Kenapa pilih StudyServer?</h2>
            <p class="text-ink-soft">Kami merancang ekosistem belajar komprehensif yang mengombinasikan keunggulan pedagogi tatap muka dengan fleksibilitas teknologi adaptif.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 w-full">
            {{-- Card 1 --}}
            <div class="bg-white rounded-2xl p-8 shadow-[0px_4px_20px_-2px_rgba(15,27,76,0.06)] flex flex-col justify-between">
                <div class="flex flex-col gap-6">
                    <div class="flex items-center justify-between">
                        <span class="flex items-center justify-center size-14 rounded-2xl bg-navy-800 shadow-lg">
                            <svg class="size-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.25278V19.25M12 6.25278C10.8321 5.47686 9.24649 5 7.5 5C5.75351 5 4.16789 5.47686 3 6.25278V19.25C4.16789 18.4741 5.75351 18 7.5 18C9.24649 18 10.8321 18.4741 12 19.25M12 6.25278C13.1679 5.47686 14.7535 5 16.5 5C18.2465 5 19.8321 5.47686 21 6.25278V19.25C19.8321 18.4741 18.2465 18 16.5 18C14.7535 18 13.1679 18.4741 12 19.25"/></svg>
                        </span>
                        <span class="bg-navy-100 text-navy-950 text-[11px] font-bold px-3 py-1 rounded-full">Kurikulum 2025</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-xl text-navy-900 mb-2.5">Kelas Berkualitas</h3>
                        <p class="text-sm text-ink-soft leading-relaxed">Kurikulum terstruktur sesuai standar BPPP SNBT terbaru, diajarkan langsung oleh tutor master berprestasi dengan penjelasan konsep mendalam dan trik cepat 20 detik.</p>
                    </div>
                </div>
                <div class="border-t border-navy-50 mt-6 pt-6 flex items-center justify-between">
                    <span class="text-xs font-semibold text-navy-950 flex items-center gap-1">Lihat silabus materi
                        <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </span>
                </div>
            </div>

            {{-- Card 2 --}}
            <div class="bg-white rounded-2xl p-8 shadow-[0px_4px_20px_-2px_rgba(15,27,76,0.06)] flex flex-col justify-between">
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
                    <span class="text-xs font-semibold text-navy-950 flex items-center gap-1">Coba simulasi multi-device
                        <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </span>
                </div>
            </div>

            {{-- Card 3 --}}
            <div class="bg-white rounded-2xl p-8 shadow-[0px_4px_20px_-2px_rgba(15,27,76,0.06)] flex flex-col justify-between">
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
                    <span class="text-xs font-semibold text-navy-950 flex items-center gap-1">Lihat rekap kelulusan alumni
                        <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============ QUIZ TEASER ============ --}}
<section class="bg-navy-50 py-16 px-6 md:px-12">
    <div class="max-w-[1280px] mx-auto bg-white border border-black/10 rounded-3xl shadow-xl p-8 md:p-12 flex flex-col lg:flex-row gap-10 items-center">
        <div class="flex-1 flex flex-col gap-5">
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

        <div class="flex-1 w-full bg-navy-50 border border-black/10 rounded-2xl p-6">
            <div class="flex items-center justify-between border-b border-black/10 pb-3">
                <span class="text-[11px] font-bold text-navy-800 tracking-widest uppercase">Soal 01 / 05</span>
                <span class="bg-navy-100 text-navy-950 text-[11px] font-semibold px-2.5 py-0.5 rounded-full">Penalaran Logika</span>
            </div>
            <p class="text-sm font-medium text-navy-900 leading-relaxed py-4">
                Jika semua mahasiswa berprestasi mendapatkan beasiswa riset, dan beberapa penerima beasiswa riset lolos magang internasional di Silicon Valley, manakah simpulan yang PASTI BENAR?
            </p>
            <div class="flex flex-col gap-2.5">
                @foreach ([
                    'A. Semua mahasiswa berprestasi pasti magang di Silicon Valley.',
                    'B. Sebagian penerima beasiswa riset adalah mahasiswa berprestasi.',
                    'C. Mahasiswa yang tidak berprestasi tidak bisa magang di luar negeri.',
                ] as $option)
                <label class="bg-white border border-black/10 rounded-xl px-4 py-3.5 flex items-center justify-between cursor-pointer hover:border-navy-300 transition">
                    <span class="text-xs text-navy-900">{{ $option }}</span>
                    <input type="radio" name="quiz-demo" class="size-5 accent-navy-800">
                </label>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ============ CTA BANNER ============ --}}
<section class="bg-navy-50 py-24 px-6 md:px-12">
    <div class="relative max-w-[1280px] mx-auto bg-navy-800 rounded-3xl shadow-2xl overflow-hidden p-10 md:p-16 flex flex-col md:flex-row items-center justify-between gap-8">
        <div class="absolute -bottom-24 -right-24 size-80 rounded-full bg-brand-green/30 blur-3xl"></div>
        <div class="absolute -top-20 -left-20 size-72 rounded-full bg-navy-100/20 blur-3xl"></div>

        <div class="relative max-w-xl flex flex-col gap-3">
            <span class="bg-white/10 text-brand-greenlight text-[11px] font-bold tracking-widest uppercase px-3.5 py-1 rounded-full w-fit">Promo Periode Semester Baru</span>
            <h2 class="text-3xl md:text-4xl font-extrabold text-white tracking-tight">Siap Menggapai Kampus Impianmu?</h2>
            <p class="text-navy-400 leading-relaxed">Bergabunglah bersama ribuan pejuang PTN lainnya hari ini. Coba gratis fitur simulasi dan akses bank soal terlengkap selama 7 hari tanpa syarat.</p>
        </div>
        <a href="{{ route('register') }}" class="relative bg-white text-navy-950 font-bold text-sm text-center px-8 py-4 rounded-full shadow-lg shrink-0 hover:bg-navy-50 transition">
            Daftar Sekarang — Gratis Trial 7 Hari
        </a>
    </div>
</section>

{{-- ============ FOOTER ============ --}}
<footer class="bg-navy-800">
    <div class="max-w-[1280px] mx-auto px-6 md:px-12 pt-16 pb-12 flex flex-col gap-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
            <div class="flex flex-col gap-4">
                <div class="flex items-center gap-3">
                    <span class="flex items-center justify-center size-8 rounded-md bg-white/10">
                        <svg class="size-5 text-brand-greenlight" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.25278V19.25M12 6.25278C10.8321 5.47686 9.24649 5 7.5 5C5.75351 5 4.16789 5.47686 3 6.25278V19.25C4.16789 18.4741 5.75351 18 7.5 18C9.24649 18 10.8321 18.4741 12 19.25M12 6.25278C13.1679 5.47686 14.7535 5 16.5 5C18.2465 5 19.8321 5.47686 21 6.25278V19.25C19.8321 18.4741 18.2465 18 16.5 18C14.7535 18 13.1679 18.4741 12 19.25"/></svg>
                    </span>
                    <span class="text-white font-bold text-xl">StudyServer</span>
                </div>
                <p class="text-navy-400 text-xs leading-relaxed">Platform bimbingan belajar digital terdepan untuk persiapan UTBK-SNBT dan ujian sekolah dengan teknologi adaptif berstandar nasional.</p>
            </div>
            <div class="flex flex-col gap-3">
                <h4 class="text-white text-sm font-semibold tracking-widest uppercase">Navigasi</h4>
                <a href="#" class="text-navy-400 text-sm hover:text-white transition">Beranda</a>
                <a href="#" class="text-navy-400 text-sm hover:text-white transition">Tentang Kami</a>
                <a href="#" class="text-navy-400 text-sm hover:text-white transition">Program Kelas</a>
                <a href="#" class="text-navy-400 text-sm hover:text-white transition">Hubungi Kami</a>
            </div>
            <div class="flex flex-col gap-3">
                <h4 class="text-white text-sm font-semibold tracking-widest uppercase">Program Unggulan</h4>
                <a href="#" class="text-navy-400 text-sm hover:text-white transition">Simulasi UTBK/SNBT</a>
                <a href="#" class="text-navy-400 text-sm hover:text-white transition">Bimbel Intensif SMA</a>
                <a href="#" class="text-navy-400 text-sm hover:text-white transition">Bank Soal & Pembahasan</a>
                <a href="#" class="text-navy-400 text-sm hover:text-white transition">Live Tutoring Interaktif</a>
            </div>
            <div class="flex flex-col gap-3">
                <h4 class="text-white text-sm font-semibold tracking-widest uppercase">Hubungi Kami</h4>
                <p class="text-navy-400 text-xs leading-relaxed">Jakarta Selatan, DKI Jakarta<br>support@studyserver.id<br>+62 (021) 8899-2345</p>
                <div class="flex gap-3 pt-1">
                    @for ($i = 0; $i < 3; $i++)
                        <span class="flex items-center justify-center size-9 rounded-full bg-navy-950"></span>
                    @endfor
                </div>
            </div>
        </div>
        <div class="border-t border-white/10 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-navy-400 text-xs">© {{ date('Y') }} PT StudyServer Edukasi Indonesia. Hak Cipta Dilindungi.</p>
            <div class="flex gap-6">
                <a href="#" class="text-navy-400 text-xs hover:text-white transition">Kebijakan Privasi</a>
                <a href="#" class="text-navy-400 text-xs hover:text-white transition">Syarat & Ketentuan</a>
            </div>
        </div>
    </div>
</footer>

@endsection
