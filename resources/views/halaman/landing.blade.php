@extends('layout.app')

@section('title', 'PintarKuy - Belajar lebih terarah, berkembang lebih cepat')

@section('content')

@php
    $pkLandingStartUrl = Auth::check()
        ? (Auth::user()->isGuru() ? route('guru.dashboard') : route('dashboard'))
        : route('register');
@endphp

{{-- ============ SHARED NAVBAR ============ --}}
@include('komponen.header')

<main class="w-full bg-paper min-h-screen">

{{-- ============ Hero Section ============ --}}
<section class="w-full bg-paper paper-bg py-10 lg:py-16">
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
                    </div>
                </div>
            </div>

            {{-- Right Visual Ledger Frame (5 Cols) --}}
            <div class="lg:col-span-5 relative mt-4 lg:mt-0">
                <div class="absolute -top-3 -right-3 w-full h-full border border-ink/30 bg-whitewarm rounded-sm pointer-events-none"></div>

                <div class="relative z-10 bg-whitewarm p-2 rounded-sm border border-ink shadow-[2px_2px_0px_0px_#101A2E]">
                    <div class="flex items-center justify-between px-2 py-1.5 mb-2 bg-paper border border-ink/15 lb-code text-[11px] text-ink">
                        <span class="font-bold italic translate-x-[2px]">NEWS</span>
                    </div>

                    <div class="relative w-full aspect-[4/3] overflow-hidden rounded-sm border border-ink/20 bg-ink">
                        <img src="{{ asset('assets/images/hero-landing.jpg') }}"
                             alt="Natural window light, realistic Indonesian study desk with notebooks, exam prep books, pens, laptop, candid student study setting, subtle navy and warm gold tone lighting, clean authentic educational aesthetic"
                             class="w-full h-full object-cover mix-blend-luminosity opacity-90 filter contrast-125">
                        <div class="absolute inset-0 bg-ink/20 pointer-events-none"></div>
                        <div class="absolute inset-0 bg-ink/15 pointer-events-none"></div>
                    </div>

                    <div class="absolute -bottom-6 -left-4 z-20 bg-whitewarm p-3.5 rounded-sm border border-ink shadow-[2px_2px_0px_0px_#101A2E] max-w-[240px]">
                        <div class="w-full border-t border-b border-ink py-1.5 my-0.5">
                            <div class="flex items-baseline justify-between gap-3">
                                <span class="lb-score text-[44px] leading-none text-gold font-bold tabular-nums">{{ isset($statistikGlobal['rata_rata']) ? $statistikGlobal['rata_rata'] : '-' }}</span>
                                <span class="lb-code text-[13px] text-teal font-bold bg-teal/10 px-1.5 py-0.5 border-l-2 border-teal">TERVERIFIKASI</span>
                            </div>
                            <div class="lb-body-sm text-ink font-medium mt-1">Rata-rata nilai siswa</div>
                        </div>
                        <div class="flex items-center justify-between mt-1 text-[10px] lb-code text-ink-soft">
                            <span>SKALA 0-100</span>
                            <span>N = {{ number_format($statistikGlobal['total_ujian'] ?? 0, 0, '.', '.') }} UJIAN</span>
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
                <div class="lb-headline-xl text-ink font-bold tabular-nums tracking-tight">1.200+</div>
                <div class="mt-1 lb-body-md text-ink font-medium flex items-center gap-2">
                    <span class="w-2 h-0.5 bg-gold"></span>
                    Siswa Aktif Terdaftar
                </div>
                <div class="mt-2 lb-code lb-body-sm text-ink-soft">Penyebaran: 28 Provinsi &amp; Konsorsium Mandiri</div>
            </div>

            <div class="py-6 px-4 flex flex-col justify-center">
                <div class="lb-headline-xl text-ink font-bold tabular-nums tracking-tight">150+</div>
                <div class="mt-1 lb-body-md text-ink font-medium flex items-center gap-2">
                    <span class="w-2 h-0.5 bg-teal"></span>
                    Pengajar &amp; Konsultan Ahli
                </div>
                <div class="mt-2 lb-code lb-body-sm text-ink-soft">Alumni UI, ITB, UGM, ITS &amp; Tenaga Asesor</div>
            </div>

            <div class="py-6 px-4 flex flex-col justify-center">
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

{{-- ============ Simulasi IRT Interaktif (Quiz 5 Soal) ============ --}}
<section id="uji-kemampuan" class="w-full bg-whitewarm py-10 border-t border-ink/20 scroll-mt-20">
    <div class="max-w-7xl mx-auto px-4 md:px-margin">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            {{-- Quiz interactive card --}}
            <div class="lg:col-span-8 bg-paper p-6 rounded-sm border border-ink shadow-[2px_2px_0px_0px_#101A2E]">
                <div class="flex flex-wrap items-center justify-between gap-3 pb-3 mb-4 border-b border-ink">
                    <div class="flex items-center gap-2">
                        <span class="lb-headline-sm text-ink font-bold">Simulasi IRT Real-Time</span>
                        <span class="bg-ink text-paper lb-code text-[11px] px-2 py-0.5 rounded-sm">BOBOT IRT: TINGGI</span>
                    </div>
                    <div class="lb-code text-[11px] text-ink-soft">
                        ESTIMASI: <span class="font-bold text-ink">2 MENIT</span>
                    </div>
                </div>

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
                        <div class="flex flex-wrap items-center justify-between gap-2 border-b border-ink/10 pb-3">
                            <span class="lb-label text-ink-soft uppercase tracking-wider">Soal {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }} / {{ str_pad(count($quiz), 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="bg-gold/15 text-ink border border-gold/40 lb-code text-[10px] font-bold px-2.5 py-0.5 rounded-sm uppercase tracking-wider">{{ $q['tag'] }}</span>
                        </div>
                        <p class="lb-body-md text-ink font-medium leading-relaxed py-4">{{ $q['text'] }}</p>
                        <div class="flex flex-col gap-2.5 pb-2">
                            @foreach ($q['options'] as $value => $option)
                            <label class="quiz-option bg-whitewarm border border-ink/20 rounded-sm px-4 py-3 flex items-center justify-between gap-3 cursor-pointer hover:border-gold transition">
                                <span class="lb-body-md text-ink">{{ $value }}. {{ $option }}</span>
                                <input type="radio" name="quiz-q{{ $i }}" value="{{ $value }}" class="size-5 accent-ink shrink-0">
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="flex flex-wrap items-center gap-3 pt-4">
                    <button type="button" id="quizCheck" class="inline-flex items-center gap-2 bg-ink text-paper lb-label-md font-bold px-5 py-2.5 rounded-sm border border-ink shadow-[2px_2px_0px_0px_#E3A23B] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[1px_1px_0px_0px_#E3A23B] transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Periksa Jawaban
                    </button>
                    <button type="button" id="quizNext" class="hidden inline-flex items-center gap-2 bg-ink text-paper lb-label-md font-bold px-5 py-2.5 rounded-sm border border-ink shadow-[2px_2px_0px_0px_#E3A23B] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[1px_1px_0px_0px_#E3A23B] transition-all">
                        Soal Berikutnya
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </button>
                    <a href="{{ route('register') }}" data-auth-cta class="inline-flex items-center gap-1.5 lb-label-md text-ink font-bold hover:text-teal transition ml-auto">
                        Daftar Gratis &amp; Uji Penuh dalam 2 Menit
                        <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
                <p id="quizFeedback" class="hidden min-h-4 lb-label-md text-ink leading-relaxed pt-3"></p>

                <div id="quizResult" class="hidden mt-5 bg-whitewarm border border-ink rounded-sm p-5 flex flex-col gap-3">
                    <div class="flex items-center justify-between">
                        <span class="lb-label text-ink uppercase tracking-wider font-bold">Hasil Akhir</span>
                        <span class="bg-teal text-paper lb-code text-[10px] font-bold px-2.5 py-0.5 rounded-sm uppercase tracking-wider">5 Soal Selesai</span>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <span id="quizScore" class="text-4xl font-black text-ink">0</span>
                        <span class="lb-body-md font-semibold text-ink-soft">/ 100 poin</span>
                    </div>
                    <p id="quizScoreMessage" class="lb-body-sm text-ink-soft leading-relaxed"></p>
                    <a href="{{ route('register') }}" data-auth-cta class="text-center lb-label-md font-bold bg-gold text-ink rounded-sm border border-ink shadow-[2px_2px_0px_0px_#101A2E] px-4 py-2.5 hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[1px_1px_0px_0px_#101A2E] transition-all">
                        Lanjutkan ke Tryout Penuh UTBK-SNBT →
                    </a>
                </div>
            </div>

            {{-- Sidebar info IRT --}}
            <div class="lg:col-span-4 flex flex-col gap-4">
                <div class="bg-whitewarm p-5 rounded-sm border border-ink/20">
                    <div class="flex items-center justify-between pb-3 border-b border-ink mb-4">
                        <span class="lb-headline-sm text-ink font-bold">Profil Simulasi</span>
                        <span class="lb-code text-[11px] text-gold font-bold uppercase">Live</span>
                    </div>
                    <div class="space-y-3 lb-body-md text-ink-soft">
                        <div class="flex items-center justify-between">
                            <span>Jumlah Butir</span>
                            <span class="font-bold text-ink">5 Soal</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Skor Maksimal</span>
                            <span class="font-bold text-ink">100 Poin</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Bobot IRT</span>
                            <span class="font-bold text-gold bg-ink px-2 py-0.5 rounded-sm">TINGGI</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Estimasi Waktu</span>
                            <span class="font-bold text-ink">± 2 Menit</span>
                        </div>
                    </div>
                </div>
                <div class="bg-ink text-paper p-5 rounded-sm border border-ink shadow-[2px_2px_0px_0px_#E3A23B]">
                    <span class="lb-code text-gold uppercase tracking-wider block mb-2">Cara Kerja</span>
                    <ul class="space-y-2 lb-body-sm text-paper/80">
                        <li class="flex gap-2"><span class="font-bold text-paper">1.</span> Pilih satu jawaban tiap soal.</li>
                        <li class="flex gap-2"><span class="font-bold text-paper">2.</span> Tekan <span class="text-gold font-bold">Periksa Jawaban</span> untuk umpan balik instan.</li>
                        <li class="flex gap-2"><span class="font-bold text-paper">3.</span> Kumpulkan otomatis di soal ke-5 dan lihat skor akhirmu.</li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ============ Testimoni Alumni ============ --}}
<section class="w-full bg-paper py-12 border-t border-ink/15">
    <div class="max-w-7xl mx-auto px-4 md:px-margin">
        <div class="flex flex-col items-center text-center gap-2 mb-8">
            <span class="lb-code text-teal font-semibold uppercase tracking-wider">CERITA MEREKA</span>
            <h2 class="lb-headline-md text-ink font-bold">Lolos PTN Bersama PintarKuy</h2>
            <p class="lb-body-md text-ink-soft max-w-xl">Ini sebagian dari cerita mereka. Kamu bisa jadi salah satunya.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach ([
                ['N', 'Nabila Zahra', 'FK UI 2026', 'Skor naik dari 480 ke 705 dalam 6 bulan. Live class-nya paling nge-zoom jelasin konsep, beda banget sama sekadar nonton video.', 'bg-ink', 'text-gold'],
                ['R', 'Rangga Prasetyo', 'STEI ITB 2026', 'Fitur analitik IRT-nya gila sih. Tiap minggu aku tau persis lemah di mana dan langsung direkomendasiin materi yang harus diulang.', 'bg-teal', 'text-paper'],
                ['S', 'Salsabila Putri', 'FEB UGM 2026', 'Tryout mingguannya bikin mental juara. Jaringan internet lemot di kampung juga tetap lancar karena video-nya compact. Recommended 100%.', 'bg-whitewarm border border-ink', 'text-ink'],
            ] as [$initial, $name, $campus, $quote, $avatarBg, $avatarText])
            <div class="bg-whitewarm border border-ink rounded-sm shadow-[2px_2px_0px_0px_#101A2E] p-6 flex flex-col gap-4">
                <div class="flex items-center justify-between">
                    <div class="flex gap-0.5">
                        @for ($i = 0; $i < 5; $i++)
                            <svg class="size-4 text-gold" fill="currentColor" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        @endfor
                    </div>
                    <span class="lb-code text-[10px] text-ink-soft uppercase tracking-wider">Alumni 2026</span>
                </div>
                <p class="lb-body-md text-ink leading-relaxed flex-1">"{{ $quote }}"</p>
                <div class="flex items-center gap-3 pt-3 border-t border-ink/10">
                    <span class="flex items-center justify-center size-10 rounded-full shrink-0 lb-code font-bold {{ $avatarBg }} {{ $avatarText }}">{{ $initial }}</span>
                    <div class="min-w-0">
                        <div class="lb-body-md font-bold text-ink truncate">{{ $name }}</div>
                        <div class="lb-code text-[11px] text-ink-soft">{{ $campus }}</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

</main>

{{-- ============ FOOTER ============ --}}
@include('komponen.footer')

@endsection

@push('styles')
    @vite(['resources/css/halaman/landing.css'])
@endpush

@push('scripts')
    @vite(['resources/js/halaman/landing.js'])
@endpush