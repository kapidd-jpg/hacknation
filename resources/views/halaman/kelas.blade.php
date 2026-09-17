@extends('layout.app')

@section('title', 'Program Kelas - PintarKuy')

@section('content')
@include('komponen.header')

<div class="page-hero">
    <span class="pk-eyebrow">Katalog Kelas</span>
    <h1 class="page-hero-title">Temukan Kelas yang Sesuai Targetmu</h1>
    <p class="page-hero-sub">Dari persiapan UTBK-SNBT hingga ekstrakurikuler pengembangan skill, semua kurikulumnya disusun tutor lulusan PTN dan bisa diakses dari perangkat mana saja.</p>
</div>

<!-- ============ Katalog ============ -->
<section class="pk-section pk-section--muted" style="padding-top:56px;">
    <div class="pk-container">

        <div class="kelas-filter" role="tablist">
            <button type="button" data-cat="Semua" class="active">Semua</button>
            @foreach ($kategori as $cat)
                <button type="button" data-cat="{{ $cat }}">{{ $cat }}</button>
            @endforeach
        </div>

        <div id="kelasGrid" class="kelas-grid" role="list"></div>

        <div id="kelasEmpty" class="kelas-empty">
            <p>Tidak ada kelas pada kategori ini untuk saat ini.</p>
        </div>

        <div class="pk-cta" style="margin-top:64px;">
            <h2>Tidak Menemukan Kelas yang Kamu Cari?</h2>
            <p>Kami selalu membuka kelas baru berdasarkan permintaan siswa. Hubungi tim kami dan mintakan kelas yang kamu butuhkan.</p>
            <a href="{{ route('contact') }}" class="pk-btn" style="background:#fff;color:var(--navy-950);box-shadow:0 10px 24px -10px rgba(0,0,0,0.5);">Usulkan Kelas Baru</a>
        </div>
    </div>
</section>

@include('komponen.footer')
@endsection

@push('styles')
    @vite(['resources/css/halaman/site.css', 'resources/css/halaman/kelas.css'])
@endpush

@push('scripts')
    <script>window.pintarKuyRegisterUrl = window.pintarKuyRegisterUrl || @json(route('register'));</script>
    <script>window.pintarKuyKelas = window.pintarKuyKelas || @json($kelas);</script>
    @vite(['resources/js/halaman/kelas.js'])
@endpush