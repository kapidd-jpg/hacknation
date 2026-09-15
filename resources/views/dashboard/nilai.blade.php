@extends('layout.dashboard')

@section('title', 'Nilai — PintarKuy')

@section('pageContent')
    @php
        $empty = empty($nilaiData['ganjil']['rows']) && empty($nilaiData['genap']['rows']);
    @endphp
    <div class="flex flex-col gap-6">
        <div class="dash-head dash-reveal">
            <div>
                <h1>Nilai</h1>
                <p class="dash-head-sub">Rekap nilai latihan soal tiap mapel yang kamu kerjakan.</p>
            </div>
            <div class="dash-filter" id="semesterFilter">
                <button type="button" data-sem="ganjil" class="active">Ganjil 2026/2027</button>
                <button type="button" data-sem="genap">Genap 2025/2026</button>
            </div>
        </div>

        <div class="nilai-summary dash-reveal">
            <div class="nilai-sum-card">
                <span class="nilai-sum-label">Rata-rata Total</span>
                <span class="nilai-sum-value" id="sumAvg">0</span>
                <span class="nilai-sum-note" id="sumAvgBadge">Kategori A</span>
            </div>
            <div class="nilai-sum-card">
                <span class="nilai-sum-label">Nilai Tertinggi</span>
                <span class="nilai-sum-value" id="sumMax">—</span>
                <span class="nilai-sum-note" id="sumMaxSubj">—</span>
            </div>
            <div class="nilai-sum-card">
                <span class="nilai-sum-label">Latihan Dikerjakan</span>
                <span class="nilai-sum-value" id="sumLatihan">0</span>
                <span class="nilai-sum-note" id="sumLatihanNote">Paket</span>
            </div>
            <div class="nilai-sum-card">
                <span class="nilai-sum-label">Predikat Umum</span>
                <span class="nilai-sum-value" id="sumPred">—</span>
                <span class="nilai-sum-note">0–100</span>
            </div>
        </div>

        <div class="dash-card dash-reveal">
            <p class="dash-card-title"><span class="dash-dot"></span>Rekap Nilai <span class="dash-pill" id="semesterLabel">Semester Ganjil 2026/2027</span></p>
            <div class="nilai-table-wrap">
                <table class="nilai-table">
                    <thead>
                        <tr>
                            <th>Mata Pelajaran</th>
                            <th>Latihan</th>
                            <th>Soal</th>
                            <th>Akurasi</th>
                            <th>Rata-rata</th>
                            <th>Terbaik</th>
                            <th>Predikat</th>
                        </tr>
                    </thead>
                    <tbody id="nilaiRows"></tbody>
                </table>
            </div>
        </div>

        <div class="nilai-note dash-reveal">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div>Nilai diambil otomatis dari hasil latihan soal yang kamu kumpulkan (skala 0–100). Predikat A ≥ 85, B ≥ 70, dan C &lt; 70.</div>
        </div>

        @if ($empty)
            <div class="nilai-note dash-reveal">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div>Belum ada latihan soal yang dikerjakan. Kerjakan latihan di menu <a href="{{ route('dashboard.latsol') }}" style="text-decoration:underline;font-weight:700;color:var(--green-text);">Latihan Soal</a> agar nilai tampil di sini.</div>
            </div>
        @endif
    </div>

    <script>
        window.pintarKuyNilai = @json($nilaiData);
    </script>
@endsection

@push('styles')
    @vite(['resources/css/dashboard/site.css', 'resources/css/dashboard/nilai.css'])
@endpush

@push('scripts')
    @vite(['resources/js/dashboard/nilai.js'])
@endpush