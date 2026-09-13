@extends('layout.app')

@section('title', 'Tentang Kami — PintarKuy')

@section('content')
@include('komponen.header')

<div class="page-hero">
    <span class="pk-eyebrow">Tentang Kami</span>
    <h1 class="page-hero-title">Membangun Generasi Kampus Impian</h1>
    <p class="page-hero-sub">Kami percaya setiap siswa berhak mendapatkan bimbingan berkualitas tanpa hambatan jarak, waktu, atau biaya yang berlebihan. PintarKuy hadir untuk menjembataninya.</p>
    <div class="page-hero-actions">
        <a href="{{ route('classes') }}" class="pk-btn pk-btn--dark">Jelajahi Kelas</a>
        <a href="{{ route('contact') }}" class="pk-btn pk-btn--ghost">Hubungi Kami</a>
    </div>
</div>

<!-- ============ Misi / Visi / Nilai ============ -->
<section class="pk-section pk-section--white">
    <div class="pk-container">
        <div class="pk-section-head pk-reveal">
            <span class="pk-eyebrow">Fundasi Kami</span>
            <h2>Nilai yang Membimbing Kami</h2>
            <p>Di balik setiap fitur dan materi, ada prinsip yang kami pegang untuk memastikan pengalaman belajar terbaik bagi siswa Indonesia.</p>
        </div>

        <div class="tentang-story-grid">
            @php
                $stories = [
                    ['icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 'label' => 'Misi', 'title' => 'Demokratisasi Pendidikan Premium', 'desc' => 'Menyediakan akses bimbingan berkualitas tinggi yang sebelumnya hanya bisa didapatkan segelintir orang, kini tersedia untuk semua siswa dari Sabang sampai Merauke.', 'color' => 'green'],
                    ['icon' => 'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z', 'label' => 'Visi', 'title' => 'Platform Nasional #1 untuk Ujian Nasional', 'desc' => 'Membangun ekosistem belajar adaptif berbasis data yang dapat memprediksi dan meningkatkan potensi akademik siswa secara presisi.', 'color' => 'navy'],
                    ['icon' => 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Komitmen', 'title' => 'Berorientasi Hasil, Bukan Janji', 'desc' => 'Setiap klaim yang kami sampaikan selalu diuji dengan data. Kami transparan soal statistik kelulusan, skor rata-rata, dan kepuasan siswa.', 'color' => 'amber'],
                    ['icon' => 'M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4 4 4 0 004 4z', 'label' => 'Komunitas', 'title' => 'Siswa Membantu Siswa', 'desc' => 'Forum diskusi aktif antar siswa dan tutor membentuk lingkungan belajar kolaboratif yang tidak kaku, tapi tetap serius dan terarah.', 'color' => 'purple'],
                ];
            @endphp
            @foreach ($stories as $story)
            <div class="tentang-story-card pk-reveal">
                <span class="tentang-story-icon tentang-story-icon--{{ $story['color'] }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $story['icon'] }}"/></svg>
                </span>
                <span class="pk-eyebrow" style="font-size:10px; padding: 5px 12px;">{{ $story['label'] }}</span>
                <h3>{{ $story['title'] }}</h3>
                <p>{{ $story['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ============ Stats ============ -->
<section class="pk-section pk-section--muted">
    <div class="pk-container">
        <div class="tentang-stats pk-reveal">
            @php
                $statsData = [
                    ['val' => 1200, 'suffix' => '+', 'label' => 'Siswa Aktif'],
                    ['val' => 150,  'suffix' => '+', 'label' => 'Master Tutor'],
                    ['val' => 300,  'suffix' => '+', 'label' => 'Kelas & Modul'],
                    ['val' => 89,   'suffix' => '.4%', 'label' => 'Tingkat Kelulusan PTN'],
                ];
            @endphp
            @foreach ($statsData as $stat)
            <div class="tentang-stat">
                <span class="tentang-stat-value" data-counter="{{ $stat['val'] }}" data-suffix="{{ $stat['suffix'] }}">0</span>
                <p>{{ $stat['label'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ============ Timeline ============ -->
<section class="pk-section pk-section--white">
    <div class="pk-container">
        <div class="pk-section-head pk-reveal">
            <span class="pk-eyebrow">Perjalanan Kami</span>
            <h2>Dari Ide Kecil hingga 10.000+ Siswa</h2>
            <p>Bermula dari obsesi sederhana — membuat persiapan UTBK lebih adil — PintarKuy terus bertumbuh berkat kepercayaan ribuan siswa Indonesia.</p>
        </div>

        <ol class="tentang-timeline">
            @php
                $milestones = [
                    ['year' => '2020', 'title' => 'Pendirian & Riset Prototipe', 'desc' => 'Berdiri di Bandung dengan riset awal soal adaptif IRT (Item Response Theory) bersama tim pengembang beranggotakan 4 orang dari ITB.'],
                    ['year' => '2021', 'title' => 'Peluncuran Beta & 500 Siswa Pertama', 'desc' => 'Aplikasi beta resmi diluncurkan. Jaringan internet masih lambat, tapi fitur simulasi adaptif langsung mendapat sambutan hangat dari siswa SMA.'],
                    ['year' => '2022', 'title' => 'Ekspansi 150+ Master Tutor', 'desc' => 'Tim tutor tumbuh cepat dengan rekrutmen ketat — lulusan UI, ITB, UGM, dan Universitas Bina Nusantara. Bank soal mencapai 4.000+.'],
                    ['year' => '2023', 'title' => 'Fitur Live Tutoring & Tanya Tutor 24/7', 'desc' => 'Peluncuran live class interaktif dan fitur unggulan Tanya Tutor yang menjawab pertanyaan dalam hitungan menit.'],
                    ['year' => '2024', 'title' => '1.200+ Siswa & Penghargaan Startup Pendidikan', 'desc' => 'Meraih penghargaan Startup Pendidikan Terbaik dari Kemendikbudristek. Jumlah siswa aktif menembus 1.200+.'],
                    ['year' => '2025', 'title' => 'Target Nasional & Aliansi Kampus', 'desc' => 'Bekerja sama dengan beberapa kampus negeri untuk program beasiswa siswa berprestasi. Menuju 10.000 siswa aktif.'],
                    ['year' => '2026', 'title' => 'UTBK 2026 & Program Kampus Impian', 'desc' => 'Tingkat SNBT 2026 keluar membanggakan — ratusan siswa diterima di kampus impian. Live tutoring adaptif dan Tanya Tutor AI resmi diluncurkan.'],
                ];
            @endphp
            @foreach ($milestones as $m)
            <li class="tentang-milestone pk-reveal">
                <span class="tentang-milestone-year">{{ $m['year'] }}</span>
                <h3>{{ $m['title'] }}</h3>
                <p>{{ $m['desc'] }}</p>
            </li>
            @endforeach
        </ol>
    </div>
</section>

<!-- ============ CTA ============ -->
<section class="pk-section pk-section--muted">
    <div class="pk-container">
        <div class="pk-cta pk-reveal">
            <h2>Siap Bergabung Bersama Ribuan Siswa Lain?</h2>
            <p>Mulai langsung dari paket yang paling sesuai targetmu. Belajar dengan sistem adaptif yang benar-benar memahami kemampuanmu, tanpa kontrak dan bisa berhenti kapan saja.</p>
            <div class="page-hero-actions" style="margin-top:12px;">
                <a href="{{ route('register') }}" class="pk-btn" style="background:#fff;color:var(--navy-950);box-shadow:0 10px 24px -10px rgba(0,0,0,0.5);">Mulai Sekarang</a>
                <a href="{{ route('contact') }}" class="pk-btn pk-btn--ghost" style="background:transparent;color:#fff;border:1px solid rgba(255,255,255,0.2);">Tanya Kami</a>
            </div>
        </div>
    </div>
</section>

@include('komponen.footer')
@endsection

@push('styles')
    @vite(['resources/css/halaman/site.css', 'resources/css/halaman/tentang.css'])
@endpush

@push('scripts')
    @vite(['resources/js/halaman/tentang.js'])
@endpush