@extends('layout.dashboard')

@section('title', 'Kelas Saya - PintarKuy')

@php
    $kelasAll = $kelasAll ?? [];
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

                    <p class="kelas-desc">{{ $k['pertemuan'] }} • Rekaman kelas tersedia 24/7.</p>

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