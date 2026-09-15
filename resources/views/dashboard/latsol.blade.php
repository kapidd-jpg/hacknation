@extends('layout.dashboard')

@section('title', 'Latihan Soal — PintarKuy')

@section('pageContent')
    <div class="flex flex-col gap-6">
        <div class="dash-head dash-reveal">
            <div>
                <h1>Latihan Soal</h1>
                <p class="dash-head-sub">Kerjakan soal latihan per mapel, skor terbaik otomatis masuk ke Nilai & Laporan.</p>
            </div>
            <div class="dash-head-actions">
                <a href="{{ route('dashboard.nilai') }}" class="dash-btn dash-btn--ghost">Lihat Nilai</a>
            </div>
        </div>

        @forelse ($setsList as $set)
            <div class="dash-reveal latsol-item">
                <div class="latsol-item-main">
                    <span class="latsol-item-ico" style="background:{{ $set['bg'] }};color:{{ $set['color'] }}">{{ $set['ico'] }}</span>
                    <div class="latsol-item-info">
                        <p class="latsol-item-kelas">{{ $set['kelas'] }}</p>
                        <p class="latsol-item-label">{{ $set['label'] }}</p>
                    </div>
                </div>
                <div class="latsol-item-stats">
                    <div class="latsol-stat">
                        <span class="latsol-stat-val">{{ $set['total'] }}</span>
                        <span class="latsol-stat-key">Soal</span>
                    </div>
                    <div class="latsol-stat">
                        <span class="latsol-stat-val">{{ $set['attempts'] }}</span>
                        <span class="latsol-stat-key">Dikerjakan</span>
                    </div>
                    <div class="latsol-stat">
                        <span class="latsol-stat-val">{{ $set['best'] !== null ? $set['best'] : '—' }}</span>
                        <span class="latsol-stat-key">Skor Terbaik</span>
                    </div>
                </div>
                <div class="latsol-item-action">
                    <a href="{{ route('dashboard.latsol.mulai', [$set['kelas_id'], $set['label']]) }}" class="dash-btn dash-btn--primary">
                        Mulai
                    </a>
                </div>
            </div>
        @empty
            <div class="dash-card dash-reveal" style="padding:48px;text-align:center;">
                <p style="font-size:40px;margin-bottom:8px;">📝</p>
                <p class="materi-title" style="font-size:22px;margin-bottom:6px;">Belum ada paket latihan</p>
                <p class="dash-head-sub" style="margin-bottom:20px;">Guru belum menambahkan soal untuk mapel yang kamu ikuti. Coba cek lagi nanti ya!</p>
                <div class="flex justify-center gap-3 flex-wrap">
                    <a href="{{ route('dashboard.materi') }}" class="dash-btn dash-btn--primary">Ke Materi</a>
                    <a href="{{ route('dashboard.katalog') }}" class="dash-btn dash-btn--ghost">Jelajahi Katalog</a>
                </div>
            </div>
        @endforelse
    </div>
@endsection

@push('styles')
    @vite(['resources/css/dashboard/site.css', 'resources/css/dashboard/latsol.css'])
@endpush

@push('scripts')
    @vite(['resources/js/dashboard/latsol.js'])
@endpush