@extends('layouts.app')

@section('title', 'Program Kelas — PintarKuy')

@section('content')
@include('partials.header')

<div class="page-hero">
    <span class="pk-eyebrow">Katalog Kelas</span>
    <h1 class="page-hero-title">Temukan Kelas yang Sesuai Targetmu</h1>
    <p class="page-hero-sub">Dari persiapan UTBK-SNBT hingga ekstrakurikuler pengembangan skill — semua disusun kurikulumnya oleh tutor master dan bisa diakses dari perangkat mana saja.</p>
</div>

<!-- ============ Katalog ============ -->
<section class="pk-section pk-section--muted" style="padding-top:56px;">
    <div class="pk-container">

        <div class="kelas-filter" role="tablist">
            @foreach (['Semua', 'UTBK-SNBT', 'SMA', 'Bahasa', 'Ekstra'] as $i => $cat)
                <button type="button" data-cat="{{ $cat }}" {{ $i === 0 ? 'class="active"' : '' }}>{{ $cat }}</button>
            @endforeach
        </div>

        <div id="kelasGrid" class="kelas-grid" role="list"></div>

        <div id="kelasEmpty" class="kelas-empty">
            <p>Tidak ada kelas pada kategori ini untuk saat ini.</p>
        </div>

        <div class="pk-cta pk-reveal" style="margin-top:64px;">
            <h2>Tidak Menemukan Kelas yang Kamu Cari?</h2>
            <p>Kami selalu membuka kelas baru berdasarkan permintaan siswa. Hubungi tim kami dan mintakan kelas yang kamu butuhkan.</p>
            <a href="{{ route('contact') }}" class="pk-btn" style="background:#fff;color:var(--navy-950);box-shadow:0 10px 24px -10px rgba(0,0,0,0.5);">Usulkan Kelas Baru</a>
        </div>
    </div>
</section>

@include('partials.footer')
@endsection

@push('styles')
    @vite(['resources/css/pages/site.css', 'resources/css/pages/kelas.css'])
@endpush

@push('scripts')
    <script>window.pintarKuyRegisterUrl = window.pintarKuyRegisterUrl || @json(route('register'));</script>
    @vite(['resources/js/pages/kelas.js'])
@endpush