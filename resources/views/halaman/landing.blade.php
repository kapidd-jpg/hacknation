@extends('layout.app')

@section('title', 'PintarKuy - Belajar lebih terarah, berkembang lebih cepat')

@section('content')

@php
    $routeName = request()->route() ? request()->route()->getName() : '';
    $pkLandingStartUrl = Auth::check()
        ? (Auth::user()->isGuru() ? route('guru.dashboard') : route('dashboard'))
        : route('register');
    $userFoto = Auth::check() ? \App\Support\UserFoto::src(Auth::user()->foto) : '';
    $userNama = Auth::user()?->name ?? '';
@endphp

{{-- ============ FIXED TOP HEADER ============ --}}
<header class="fixed top-0 left-0 right-0 w-full z-50 bg-paper border-b border-ink">
    <div class="h-16 max-w-7xl mx-auto px-4 md:px-6 lg:px-margin flex items-center justify-center gap-4 xl:gap-8">
        <div class="flex items-center gap-2">
            <a href="{{ url('/') }}" class="flex items-center gap-2" title="PintarKuy">
                <img src="{{ asset('assets/images/logopintar.png') }}" alt="PintarKuy" class="h-8 w-auto object-contain">
                <span class="lb-headline-sm text-ink tracking-tight uppercase leading-none">PintarKuy</span>
            </a>
        </div>
        <div class="h-5 w-px bg-ink/20 hidden md:block"></div>
        <nav class="hidden md:flex items-center gap-3 xl:gap-4">
            @foreach ([
                ['Beranda', 'home', url('/')],
                ['Tentang', 'about', route('about')],
                ['Kelas', 'classes', route('classes')],
                ['Kontak', 'contact', route('contact')],
            ] as [$label, $name, $href])
                <a href="{{ $href }}" class="transition-colors py-1 px-2.5 rounded {{ $routeName === $name ? 'bg-ink text-paper' : 'text-ink-soft hover:text-ink' }}">
                    <span class="lb-label-md">{{ $label }}</span>
                </a>
            @endforeach
        </nav>
        <div class="h-5 w-px bg-ink/20 hidden md:block"></div>
        <div class="flex items-center gap-3 xl:gap-4">
            @auth
                <a href="{{ $pkLandingStartUrl }}" class="w-8 h-8 rounded-full bg-ink flex items-center justify-center overflow-hidden" title="Buka Dashboard">
                    @if (Auth::user()->foto)
                        <img src="{{ $userFoto }}" alt="" class="w-full h-full object-cover rounded-full">
                    @else
                        <span class="lb-code text-[11px] text-gold font-bold">{{ \App\Support\UserFoto::initials($userNama) }}</span>
                    @endif
                </a>
            @else
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center bg-gold text-ink lb-label-md font-semibold px-4 py-1.5 rounded border border-ink shadow-[2px_2px_0px_0px_#101A2E] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[1px_1px_0px_0px_#101A2E] transition-all">
                    Daftar
                </a>
                <div class="w-8 h-8 rounded-full bg-ink flex items-center justify-center">
                    <svg class="size-[18px] text-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
            @endauth
        </div>
    </div>
</header>

<main class="w-full pt-16 bg-paper min-h-screen">

{{-- ============ Top Document Registry Metadata Bar ============ --}}
<section class="w-full bg-paper border-b border-ink/15">
    <div class="max-w-7xl mx-auto px-4 md:px-margin py-1 flex flex-wrap items-center justify-between gap-y-2 text-ink-soft lb-code text-code-figure">
        <div class="flex items-center gap-4">
            <span class="inline-flex items-center gap-1.5 text-ink">
                <span class="w-2 h-2 rounded-none bg-teal"></span>
                INSTRUMEN RESMI BIMBEL: PK-2024/SNBT
            </span>
            <span class="hidden md:inline text-outline">|</span>
            <span class="hidden md:inline">KATEGORI: UTBK-SNBT / KEDINASAN / UJIAN MANDIRI</span>
        </div>
        <div class="flex items-center gap-6 lb-label uppercase tracking-wider">
            <span class="text-ink font-semibold">LEMBAR AKTIF: SESI GENAP</span>
            <span class="bg-whitewarm px-2 py-0.5 border border-ink/20 text-ink">KODE BUKU: REG-A04</span>
        </div>
    </div>
</section>

{{-- ============ Hero Section ============ --}}
<section class="w-full bg-paper py-10 lg:py-16">
    <div class="max-w-7xl mx-auto px-4 md:px-margin">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-12 items-center">

            {{-- Left Editorial Prompt (7 Cols) --}}
            <div class="lg:col-span-7 flex flex-col items-start">
                <div class="inline-flex items-center gap-2 px-2.5 py-1 bg-whitewarm border border-ink/15 border-l-2 border-l-teal rounded-sm mb-4">
                    <span class="lb-code text-ink font-bold uppercase tracking-wide">LEMBAR DIAGNOSTIK TERAKREDITASI</span>
                    <span class="w-1.5 h-1.5 bg-gold"></span>
                    <span class="lb-label text-ink-soft">TAHUN AJARAN 2024/2025</span>
                </div>

                <h1 class="lb-headline-xl text-ink font-bold tracking-tight mb-4 leading-[1.15]">
                    Belajar lebih terarah. Berkembang lebih cepat.
                </h1>

                <p class="lb-body-lg text-ink-soft mb-6 max-w-2xl">
                    Pendekatan bimbingan belajar terstruktur berbasis evaluasi diagnostik dan lembar kerja terukur.
                </p>

                <div class="flex flex-wrap items-center gap-4 w-full sm:w-auto">
                    <a href="{{ $pkLandingStartUrl }}" class="inline-flex items-center justify-center px-6 py-3.5 bg-ink text-paper lb-label-md font-semibold rounded-sm border border-ink shadow-[2px_2px_0px_0px_#101A2E] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[1px_1px_0px_0px_#101A2E] transition-all">
                        Mulai Belajar
                        <svg class="ml-2 size-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                    <a href="{{ route('classes') }}" class="inline-flex items-center justify-center px-6 py-3.5 bg-transparent text-ink lb-label-md font-semibold rounded-sm border-[1.5px] border-ink hover:bg-ink/5 transition-colors">
                        Jelajahi Kelas
                        <svg class="ml-2 size-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253V19m0-12.747C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v12.747C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-12.747C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v12.747c-1.168-.776-2.754-1.253-4.5-1.253s-3.332.477-4.5 1.253"/></svg>
                    </a>
                </div>

                <div class="w-full mt-6 pt-2 border-t border-ink/15 flex items-center justify-between text-ink-soft lb-code">
                    <div class="flex items-center gap-2">
                        <svg class="size-4 text-teal" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        <span>STANDAR MUTU RAPOR TERPADU (SMRT-04)</span>
                    </div>
                    <span class="hidden sm:inline">SERIFIKAT EVALUASI: NASIONAL</span>
                </div>
            </div>

            {{-- Right Visual Ledger Frame (5 Cols) --}}
            <div class="lg:col-span-5 relative mt-4 lg:mt-0">
                <div class="absolute -top-3 -right-3 w-full h-full border border-ink/30 bg-whitewarm rounded-sm pointer-events-none"></div>

                <div class="relative z-10 bg-whitewarm p-2 rounded-sm border border-ink shadow-[2px_2px_0px_0px_#101A2E]">
                    <div class="flex items-center justify-between px-2 py-1.5 mb-2 bg-paper border border-ink/15 lb-code text-[11px] text-ink">
                        <span class="font-bold">DOKUMEN RISET BELAJAR #894-A</span>
                        <span class="text-ink-soft">LOKASI: PERPUSTAKAAN PUSAT</span>
                    </div>

                    <div class="relative w-full aspect-[4/3] overflow-hidden rounded-sm border border-ink/20 bg-ink">
                        <img src="{{ asset('assets/images/hero-landing.jpg') }}"
                             alt="Natural window light, realistic Indonesian study desk with notebooks, exam prep books, pens, laptop, candid student study setting, subtle navy and warm gold tone lighting, clean authentic educational aesthetic"
                             class="w-full h-full object-cover mix-blend-luminosity opacity-90 filter contrast-125">
                        <div class="absolute inset-0 bg-gradient-to-tr from-ink/60 via-transparent to-gold/30 mix-blend-color pointer-events-none"></div>
                        <div class="absolute inset-0 bg-ink/15 pointer-events-none"></div>
                    </div>

                    <div class="absolute -bottom-6 -left-4 z-20 bg-whitewarm p-3.5 rounded-sm border border-ink shadow-[2px_2px_0px_0px_#101A2E] max-w-[240px]">
                        <div class="w-full border-t border-b border-ink py-1.5 my-0.5">
                            <div class="flex items-baseline justify-between gap-3">
                                <span class="lb-score text-[44px] leading-none text-gold font-bold tabular-nums">86</span>
                                <span class="lb-code text-[13px] text-teal font-bold bg-teal/10 px-1.5 py-0.5 border-l-2 border-teal">TERVERIFIKASI</span>
                            </div>
                            <div class="lb-body-sm text-ink font-medium mt-1">Rata-rata nilai siswa</div>
                        </div>
                        <div class="flex items-center justify-between mt-1 text-[10px] lb-code text-ink-soft">
                            <span>SKALA 0-100</span>
                            <span>N = 3.480 UJIAN</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ============ Horizontal Ledger-Style Stat Row ============ --}}
<section class="w-full bg-whitewarm border-y border-ink/20">
    <div class="max-w-7xl mx-auto px-4 md:px-margin">
        <div class="grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-ink/20">
            <div class="py-6 px-4 flex flex-col justify-center">
                <div class="lb-code text-ink-soft uppercase tracking-wider mb-1">KOMPONEN DATA .01</div>
                <div class="lb-headline-xl text-ink font-bold tabular-nums tracking-tight">1.200+</div>
                <div class="mt-1 lb-body-md text-ink font-medium flex items-center gap-2">
                    <span class="w-2 h-0.5 bg-gold"></span>
                    Siswa Aktif Terdaftar
                </div>
                <div class="mt-2 lb-code lb-body-sm text-ink-soft">Penyebaran: 28 Provinsi &amp; Konsorsium Mandiri</div>
            </div>

            <div class="py-6 px-4 flex flex-col justify-center">
                <div class="lb-code text-ink-soft uppercase tracking-wider mb-1">KOMPONEN DATA .02</div>
                <div class="lb-headline-xl text-ink font-bold tabular-nums tracking-tight">150+</div>
                <div class="mt-1 lb-body-md text-ink font-medium flex items-center gap-2">
                    <span class="w-2 h-0.5 bg-teal"></span>
                    Pengajar &amp; Konsultan Ahli
                </div>
                <div class="mt-2 lb-code lb-body-sm text-ink-soft">Alumni UI, ITB, UGM, ITS &amp; Tenaga Asesor</div>
            </div>

            <div class="py-6 px-4 flex flex-col justify-center">
                <div class="lb-code text-ink-soft uppercase tracking-wider mb-1">KOMPONEN DATA .03</div>
                <div class="lb-headline-xl text-ink font-bold tabular-nums tracking-tight">300+</div>
                <div class="mt-1 lb-body-md text-ink font-medium flex items-center gap-2">
                    <span class="w-2 h-0.5 bg-gold"></span>
                    Kelas &amp; Modul Terstruktur
                </div>
                <div class="mt-2 lb-code lb-body-sm text-ink-soft">Silabus diperbarui berkala sesuai kisi-kisi resmi</div>
            </div>
        </div>
    </div>
</section>

{{-- ============ Section: "Kenapa pilih PintarKuy?" ============ --}}
<section class="w-full bg-paper py-10">
    <div class="max-w-7xl mx-auto px-4 md:px-margin">

        <div class="flex flex-col md:flex-row md:items-end justify-between mb-6 pb-2 border-b border-ink/20 gap-4">
            <div>
                <div class="lb-code text-teal font-bold uppercase tracking-wider mb-1">SISTEM BIMBINGAN TERPADU</div>
                <h2 class="lb-headline-lg text-ink font-bold tracking-tight">Kenapa pilih PintarKuy?</h2>
            </div>
            <div class="lb-code text-ink-soft">DOKUMEN KONTROL: STANDARISASI METODE BELAJAR</div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <div class="bg-whitewarm p-6 rounded-sm border border-ink/10 flex flex-col justify-between hover:border-ink/40 transition-colors">
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <div class="w-10 h-10 border-[1.5px] border-ink flex items-center justify-center rounded-sm bg-paper">
                            <svg class="size-5 text-ink" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        </div>
                        <span class="lb-code text-[11px] text-ink-soft border border-ink/15 px-2 py-0.5 rounded-sm">MODUL.01</span>
                    </div>
                    <h3 class="lb-headline-sm text-ink font-bold mb-3">Kelas Berkualitas</h3>
                    <p class="lb-body-md text-ink-soft leading-relaxed mb-6">
                        Deskripsi kurikulum berbasis standar SNBT dan ujian mandiri terkini dengan bank soal tervalidasi.
                    </p>
                </div>
                <div class="pt-4 border-t border-ink/10 flex items-center justify-between">
                    <div class="inline-flex items-center gap-1.5 px-2 py-1 bg-paper border border-ink/10 border-l-2 border-l-teal rounded-sm">
                        <span class="lb-label text-ink font-semibold">Tervalidasi BSNP</span>
                    </div>
                    <span class="lb-code text-[11px] text-ink-soft">K-2024</span>
                </div>
            </div>

            <div class="bg-whitewarm p-6 rounded-sm border border-ink/10 flex flex-col justify-between hover:border-ink/40 transition-colors">
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <div class="w-10 h-10 border-[1.5px] border-ink flex items-center justify-center rounded-sm bg-paper">
                            <svg class="size-5 text-ink" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <span class="lb-code text-[11px] text-ink-soft border border-ink/15 px-2 py-0.5 rounded-sm">MODUL.02</span>
                    </div>
                    <h3 class="lb-headline-sm text-ink font-bold mb-3">Fleksibel</h3>
                    <p class="lb-body-md text-ink-soft leading-relaxed mb-6">
                        Akses lembar kerja, modul digital, dan jadwal konsultasi tatap muka maupun daring kapan saja.
                    </p>
                </div>
                <div class="pt-4 border-t border-ink/10 flex items-center justify-between">
                    <div class="inline-flex items-center gap-1.5 px-2 py-1 bg-paper border border-ink/10 border-l-2 border-l-teal rounded-sm">
                        <span class="lb-label text-ink font-semibold">Sinkron Cloud 24/7</span>
                    </div>
                    <span class="lb-code text-[11px] text-ink-soft">HYBRID-SYS</span>
                </div>
            </div>

            <div class="bg-whitewarm p-6 rounded-sm border border-ink/10 flex flex-col justify-between hover:border-ink/40 transition-colors">
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <div class="w-10 h-10 border-[1.5px] border-ink flex items-center justify-center rounded-sm bg-paper">
                            <svg class="size-5 text-ink" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span class="lb-code text-[11px] text-ink-soft border border-ink/15 px-2 py-0.5 rounded-sm">MODUL.03</span>
                    </div>
                    <h3 class="lb-headline-sm text-ink font-bold mb-3">Terpercaya</h3>
                    <p class="lb-body-md text-ink-soft leading-relaxed mb-6">
                        Didukung evaluasi berkala dengan transparansi buku rapor kumulatif dan catatan diagnostik tutor.
                    </p>
                </div>
                <div class="pt-4 border-t border-ink/10 flex items-center justify-between">
                    <div class="inline-flex items-center gap-1.5 px-2 py-1 bg-paper border border-ink/10 border-l-2 border-l-teal rounded-sm">
                        <span class="lb-label text-ink font-semibold">Rapor Terbuka</span>
                    </div>
                    <span class="lb-code text-[11px] text-ink-soft">AUDIT-VALID</span>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ============ Interactive Question Matrix & Diagnostic Sample Sheet ============ --}}
<section class="w-full bg-whitewarm py-10 border-t border-ink/20">
    <div class="max-w-7xl mx-auto px-4 md:px-margin">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            {{-- Diagnostic Transcript Section --}}
            <div class="lg:col-span-8 bg-paper p-6 rounded-sm border border-ink/20">
                <div class="flex flex-wrap items-center justify-between pb-3 mb-4 border-b border-ink">
                    <div class="flex items-center gap-2">
                        <span class="lb-headline-sm text-ink font-bold">Simulasi Lembar Ujian (LJU-CBT)</span>
                        <span class="bg-ink text-paper lb-code text-[11px] px-2 py-0.5 rounded-sm">SOAL NO. 24</span>
                    </div>
                    <div class="lb-code text-ink">
                        SISA WAKTU: <span class="font-bold text-gold bg-ink px-2 py-0.5 rounded-sm">48:12</span>
                    </div>
                </div>

                <div class="bg-whitewarm p-5 border border-ink/10 rounded-sm mb-4">
                    <div class="lb-label text-ink-soft uppercase tracking-wider mb-2">BIDANG STUDI: PENALARAN MATEMATIKA &amp; LOGIKA</div>
                    <p class="lb-body-md text-ink leading-relaxed mb-4">
                        Sebuah grafik fungsi kuadrat memiliki titik puncak pada koordinat <span class="lb-code font-bold">(2, -9)</span> serta memotong sumbu X di titik <span class="lb-code font-bold">(5, 0)</span>. Jika fungsi tersebut dinyatakan dalam <span class="lb-code font-bold">f(x) = ax² + bx + c</span>, maka nilai dari <span class="lb-code font-bold">a + b + c</span> bernilai...
                    </p>

                    <div class="space-y-2 lb-body-md">
                        @foreach ([
                            ['A', '-8', false],
                            ['B', '-5', true],
                            ['C', '-2', false],
                            ['D', '3', false],
                            ['E', '7', false],
                        ] as [$letter, $value, $selected])
                            <label class="flex items-center gap-3 p-2.5 border rounded-sm cursor-pointer transition-colors {{ $selected ? 'border-ink bg-ink text-paper' : 'border-ink/20 bg-paper hover:bg-whitewarm' }}">
                                <span class="w-7 h-7 flex items-center justify-center border lb-code font-bold rounded-sm {{ $selected ? 'border-paper bg-whitewarm text-ink' : 'border-ink bg-whitewarm text-ink' }}">{{ $letter }}</span>
                                <span class="{{ $selected ? 'font-medium' : 'text-ink' }}">{{ $value }}</span>
                                @if ($selected)
                                    <span class="ml-auto lb-code text-[11px] text-gold font-bold tracking-wider">PILIHAN TERSIMPAN</span>
                                @endif
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex flex-wrap items-center justify-between gap-3 pt-2">
                    <div class="flex items-center gap-2">
                        <button class="px-4 py-2 border border-ink/30 text-ink lb-label-md rounded-sm bg-whitewarm hover:bg-paper" type="button">← Sebelumnya</button>
                        <button class="px-4 py-2 border border-dashed border-gold text-ink lb-label-md rounded-sm bg-gold/10 hover:bg-gold/20" type="button">Ragu-ragu</button>
                    </div>
                    <button class="px-5 py-2 bg-ink text-paper lb-label-md rounded-sm hover:bg-ink/90" type="button">Berikutnya →</button>
                </div>
            </div>

            {{-- Question Navigator ("Daftar Soal" matrix) --}}
            <div class="lg:col-span-4 bg-whitewarm p-5 rounded-sm border border-ink shadow-[2px_2px_0px_0px_#101A2E]">
                <div class="flex items-center justify-between pb-3 border-b border-ink mb-4">
                    <span class="lb-headline-sm text-ink font-bold">Daftar Nomor Soal</span>
                    <span class="lb-code text-[12px] text-ink-soft">30 BUTIR</span>
                </div>

                <div class="grid grid-cols-6 gap-1.5 mb-6">
                    @for ($i = 1; $i <= 30; $i++)
                        @php
                            $flag = '';
                            if ($i === 5) {
                                $flag = 'border border-dashed border-gold bg-gold/10 text-ink';
                            } elseif ($i === 24) {
                                $flag = 'bg-whitewarm text-ink font-bold border-2 border-gold';
                            } elseif ($i >= 25) {
                                $flag = 'bg-whitewarm text-ink font-medium border border-ink/20';
                            } else {
                                $flag = 'bg-ink text-paper font-bold border border-ink';
                            }
                        @endphp
                        <div class="h-9 flex items-center justify-center lb-code rounded-sm {{ $flag }}">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</div>
                    @endfor
                </div>

                <div class="pt-3 border-t border-ink/15 space-y-2 lb-code text-[11px] text-ink-soft">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2"><span class="w-3 h-3 bg-ink rounded-sm"></span><span>Dijawab</span></div>
                        <span class="font-bold text-ink">23</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2"><span class="w-3 h-3 border-2 border-gold bg-whitewarm rounded-sm"></span><span>Aktif</span></div>
                        <span class="font-bold text-ink">1</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2"><span class="w-3 h-3 border border-dashed border-gold bg-gold/20 rounded-sm"></span><span>Ragu-ragu</span></div>
                        <span class="font-bold text-ink">1</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2"><span class="w-3 h-3 border border-ink/30 bg-whitewarm rounded-sm"></span><span>Belum Terisi</span></div>
                        <span class="font-bold text-ink">5</span>
                    </div>
                </div>

                <div class="mt-5 pt-3 border-t border-ink/20">
                    <button class="w-full py-2.5 px-4 bg-gold text-ink lb-label-md font-bold rounded-sm border border-ink shadow-[2px_2px_0px_0px_#101A2E] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[1px_1px_0px_0px_#101A2E] transition-all flex items-center justify-center gap-2" type="button">
                        <svg class="size-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 17v3a1 1 0 001 1h3m9-21h3a1 1 0 011 1v3M7 14l7-7M14 7l3 3-7 7H7v-3z"/></svg>
                        Selesai &amp; Kumpulkan Jawaban
                    </button>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ============ Transcript Ledger Preview Table ============ --}}
<section class="w-full bg-paper py-10 border-t border-ink/15">
    <div class="max-w-7xl mx-auto px-4 md:px-margin">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-4 gap-2">
            <div>
                <span class="lb-code text-teal font-semibold uppercase">LEMBAR PERINGKAT TERKINI</span>
                <h2 class="lb-headline-md text-ink font-bold">Rapor Hasil Try Out Terbuka</h2>
            </div>
            <div class="lb-code text-ink-soft">STATUS: TERTUTUP - VERIFIKASI DESIMAL 2 ANGKA</div>
        </div>

        <div class="bg-whitewarm border border-ink/20 rounded-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-paper border-b-[1.5px] border-ink lb-label-md text-ink font-semibold">
                            <th class="py-3 px-4 lb-code">NO</th>
                            <th class="py-3 px-4">IDENTITAS SISWA</th>
                            <th class="py-3 px-4 lb-code">KODE KELAS</th>
                            <th class="py-3 px-4">STATUS</th>
                            <th class="py-3 px-4 text-right lb-code">SKOR TPS</th>
                            <th class="py-3 px-4 text-right lb-code">SKOR LITERASI</th>
                            <th class="py-3 px-4 text-right lb-code">AKHIR</th>
                        </tr>
                    </thead>
                    <tbody class="lb-body-md divide-y divide-ink/10">
                        <tr class="hover:bg-ink/[0.02] transition-colors">
                            <td class="py-3.5 px-4 lb-code font-bold text-ink">01</td>
                            <td class="py-3.5 px-4">
                                <div class="font-medium text-ink">Ahmad Dzaki Fadhillah</div>
                                <div class="lb-code text-[11px] text-ink-soft">NISN: 0068491201 • Target: FK UI</div>
                            </td>
                            <td class="py-3.5 px-4 lb-code">SNBT-EXT-A</td>
                            <td class="py-3.5 px-4">
                                <span class="inline-block px-2 py-0.5 text-[11px] lb-label font-semibold bg-whitewarm border border-ink/15 border-l-2 border-l-teal text-ink">Siswa Reguler</span>
                            </td>
                            <td class="py-3.5 px-4 text-right lb-code tabular-nums font-medium">785.40</td>
                            <td class="py-3.5 px-4 text-right lb-code tabular-nums font-medium">812.10</td>
                            <td class="py-3.5 px-4 text-right lb-code tabular-nums font-bold text-ink bg-paper/60">798.75</td>
                        </tr>
                        <tr class="hover:bg-ink/[0.02] transition-colors">
                            <td class="py-3.5 px-4 lb-code font-bold text-ink">02</td>
                            <td class="py-3.5 px-4">
                                <div class="font-medium text-ink">Nathania Sarah Larasati</div>
                                <div class="lb-code text-[11px] text-ink-soft">NISN: 0059124483 • Target: STEI ITB</div>
                            </td>
                            <td class="py-3.5 px-4 lb-code">SNBT-EXT-A</td>
                            <td class="py-3.5 px-4">
                                <span class="inline-block px-2 py-0.5 text-[11px] lb-label font-semibold bg-whitewarm border border-ink/15 border-l-2 border-l-teal text-ink">Siswa Reguler</span>
                            </td>
                            <td class="py-3.5 px-4 text-right lb-code tabular-nums font-medium">804.20</td>
                            <td class="py-3.5 px-4 text-right lb-code tabular-nums font-medium">780.00</td>
                            <td class="py-3.5 px-4 text-right lb-code tabular-nums font-bold text-ink bg-paper/60">792.10</td>
                        </tr>
                        <tr class="hover:bg-ink/[0.02] transition-colors">
                            <td class="py-3.5 px-4 lb-code font-bold text-ink">03</td>
                            <td class="py-3.5 px-4">
                                <div class="font-medium text-ink">Rizky Bagus Prakoso</div>
                                <div class="lb-code text-[11px] text-ink-soft">NISN: 0061209384 • Target: Akpol / STIN</div>
                            </td>
                            <td class="py-3.5 px-4 lb-code">KEDINASAN-B</td>
                            <td class="py-3.5 px-4">
                                <span class="inline-block px-2 py-0.5 text-[11px] lb-label font-semibold bg-whitewarm border border-ink/15 border-l-2 border-l-gold text-ink">Konsultasi Tutor</span>
                            </td>
                            <td class="py-3.5 px-4 text-right lb-code tabular-nums font-medium">762.50</td>
                            <td class="py-3.5 px-4 text-right lb-code tabular-nums font-medium">795.80</td>
                            <td class="py-3.5 px-4 text-right lb-code tabular-nums font-bold text-ink bg-paper/60">779.15</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-2.5 bg-paper border-t border-ink/15 flex items-center justify-between lb-code text-ink-soft">
                <span>MENAMPILKAN 3 DARI 1.200 DATA SISWA</span>
                <a class="text-ink font-bold hover:underline flex items-center gap-1" href="{{ route('classes') }}">BUKA BUKU RAPOR LENGKAP →</a>
            </div>
        </div>
    </div>
</section>

{{-- ============ Administrative Direct Action Stamp Banner ============ --}}
<section class="w-full bg-ink text-paper py-10">
    <div class="max-w-7xl mx-auto px-4 md:px-margin">
        <div class="border border-paper/20 p-8 rounded-sm relative">
            <div class="absolute top-0 right-0 -mt-3 mr-4 bg-gold text-ink lb-code text-[11px] font-bold px-3 py-0.5 uppercase tracking-wider rounded-sm border border-ink">KONTRAK BELAJAR TERJAMIN</div>
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
                <div class="lg:col-span-8">
                    <span class="lb-code text-gold uppercase tracking-wider block mb-1">PENDAFTARAN PERIODE INTENSIF</span>
                    <h2 class="lb-headline-lg text-paper font-bold tracking-tight mb-2">Mulai asesmen kompetensi awal hari ini.</h2>
                    <p class="lb-body-md text-paper/80 max-w-2xl">
                        Dapatkan modul diagnostik mandiri, analisis kelemahan materi berbasis butir soal, dan panduan target program studi impian Anda.
                    </p>
                </div>
                <div class="lg:col-span-4 flex flex-col sm:flex-row lg:flex-col items-stretch gap-3">
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-6 py-3 bg-gold text-ink lb-label-md font-bold rounded-sm border border-ink shadow-[2px_2px_0px_0px_#FFFFFF] hover:translate-x-[1px] hover:translate-y-[1px] transition-all text-center">Daftar Ujian Diagnostik</a>
                    <a href="{{ route('about') }}" class="inline-flex items-center justify-center px-6 py-3 bg-transparent text-paper lb-label-md font-medium rounded-sm border border-paper/30 hover:bg-whitewarm/5 transition-colors text-center">Unduh Panduan Kurikulum (PDF)</a>
                </div>
            </div>
        </div>
    </div>
</section>

</main>

{{-- ============ FOOTER (ledger sheet) ============ --}}
<footer class="w-full bg-paper border-t border-ink/20 mt-10">
    <div class="max-w-7xl mx-auto px-4 md:px-margin py-10">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 pb-6 border-b border-ink/10">
            <div class="space-y-1 md:col-span-2">
                <div class="flex items-center gap-2">
                    <img src="{{ asset('assets/images/logopintar.png') }}" alt="PintarKuy" class="h-8 w-auto object-contain">
                    <span class="lb-headline-sm text-ink">PintarKuy</span>
                    <span class="lb-code text-ink-soft bg-paper-100 px-1.5 py-0.5 rounded">PK.ID-2024</span>
                </div>
                <p class="lb-body-sm text-ink-soft max-w-md">Instrumen persiapan asesmen kompetensi, SNBT/UTBK, dan ujian kedinasan berstandar format lembar jawaban nasional.</p>
            </div>
            <div>
                <div class="lb-label text-ink tracking-wider uppercase mb-2">Direktori</div>
                <ul class="space-y-1 lb-body-sm text-ink-soft">
                    <li><a class="hover:text-ink" href="{{ url('/') }}">Beranda Portal</a></li>
                    <li><a class="hover:text-ink" href="{{ route('classes') }}">Katalog Bimbel</a></li>
                    <li><a class="hover:text-ink" href="{{ route('about') }}">Struktur Kurikulum</a></li>
                    <li><a class="hover:text-ink" href="{{ route('contact') }}">Pusat Bantuan</a></li>
                </ul>
            </div>
            <div>
                <div class="lb-label text-ink tracking-wider uppercase mb-2">Protokol &amp; Regulasi</div>
                <ul class="space-y-1 lb-code text-ink-soft">
                    <li>KODE POS: 10110-JKT</li>
                    <li>VERIFIKASI: B-ACCR/9842</li>
                    <li>STATUS SERVER: AKTIF (0.12s)</li>
                    <li>INTEGRITAS RAPOR: VALID</li>
                </ul>
            </div>
        </div>
        <div class="pt-4 flex flex-col md:flex-row items-start md:items-center justify-between gap-2 lb-label text-ink-soft">
            <div>© 2024 PintarKuy Indonesia. Lembar Jawaban &amp; Rapor Digital. Hak cipta dilindungi undang-undang.</div>
            <div class="flex items-center gap-4 lb-code">
                <span>PROTOKOL RESMI</span>
                <span class="w-1.5 h-1.5 rounded-full bg-gold"></span>
                <span>REV.04.28</span>
            </div>
        </div>
    </div>
</footer>

@endsection

@push('styles')
    @vite(['resources/css/halaman/landing.css'])
@endpush