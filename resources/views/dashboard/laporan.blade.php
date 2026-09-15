@extends('layout.dashboard')

@section('title', 'Laporan — PintarKuy')

@section('pageContent')
    <div class="flex flex-col gap-6">
        <div class="dash-head dash-reveal">
            <div>
                <h1>Laporan Belajar</h1>
                <p class="dash-head-sub">Perkembangan skor dan rekomendasi materi berdasarkan analitik IRT.</p>
            </div>
            <div class="dash-filter" id="rangeFilter">
                <button type="button" data-range="1">1 Bulan</button>
                <button type="button" data-range="3">3 Bulan</button>
                <button type="button" data-range="6" class="active">6 Bulan</button>
            </div>
        </div>

        <div class="dash-grid dash-grid--4 dash-reveal">
            <div class="lap-stat dash-card" style="gap:14px;">
                <span class="lap-stat-label">Skor Prediksi Terkini</span>
                <span class="lap-stat-value" id="statSkor">{{ $laporan['skor'] ?? '—' }}</span>
                <span class="lap-stat-delta" id="statDeltaSkor">{{ is_null($laporan['delta']) ? 'Belum ada data' : (($laporan['delta'] >= 0 ? '▲ +' : '▼ ') . $laporan['delta'] . ' pts') }}</span>
            </div>
            <div class="lap-stat dash-card" style="gap:14px;">
                <span class="lap-stat-label">Perkembangan</span>
                <span class="lap-stat-value" id="statDelta">—</span>
                <span class="lap-stat-delta" id="statDeltaSub">Sejak 6 bulan lalu</span>
            </div>
            <div class="lap-stat dash-card" style="gap:14px;">
                <span class="lap-stat-label">Tingkat Akurasi Soal</span>
                <span class="lap-stat-value" id="statAkurasi">{{ $laporan['akurasi'] ?? '—' }}%</span>
                <span class="lap-stat-delta" id="statAkurasiSub">Rata-rata 6 bulan</span>
            </div>
            <div class="lap-stat dash-card" style="gap:14px;">
                <span class="lap-stat-label">Total Latihan Soal</span>
                <span class="lap-stat-value">{{ $laporan['total_latihan'] ?? 0 }}</span>
                <span class="lap-stat-delta lap-delta--up">{{ $laporan['total_soal'] ?? 0 }} soal dikerjakan</span>
            </div>
        </div>

        <div class="dash-grid dash-grid--2 dash-reveal">
            <div class="dash-card">
                <p class="dash-card-title"><span class="dash-dot"></span>Skor Tryout <span class="dash-pill" id="rangeLabel">6 Bulan Terakhir</span></p>
                <div class="lap-chart" id="lapChart"></div>
            </div>

            <div class="dash-card">
                <p class="dash-card-title"><span class="dash-dot"></span>Rekomendasi Materi</p>
                <div class="lap-materi" id="lapMateri"></div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    @vite(['resources/css/dashboard/site.css', 'resources/css/dashboard/laporan.css'])
@endpush

@push('scripts')
    <script>
        window.pintarKuyLaporan = @json($laporan);
    </script>
    @vite(['resources/js/dashboard/laporan.js'])
@endpush