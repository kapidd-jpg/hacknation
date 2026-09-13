@extends('layout.dashboard')

@section('title', 'Katalog — PintarKuy')

@section('pageContent')
    <div class="flex flex-col gap-6">
        <div class="dash-head dash-reveal">
            <div>
                <h1>Katalog Kelas</h1>
                <p class="dash-head-sub">Temukan kelas baru sesuai target belajarmu. Kelas yang kamu daftar akan muncul di "Kelas Saya".</p>
            </div>
            <div class="dash-head-actions" id="katalogPaketBadge"></div>
        </div>

        <div class="dash-card dash-reveal" style="display:flex;flex-wrap:wrap;gap:16px;align-items:center;justify-content:space-between;">
            <div class="katalog-search">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" id="katalogSearch" placeholder="Cari kelas, mata pelajaran, atau tutor...">
            </div>
            <div class="dash-filter" id="katalogFilter">
                <button type="button" data-cat="Semua" class="active">Semua</button>
                <button type="button" data-cat="UTBK-SNBT">UTBK-SNBT</button>
                <button type="button" data-cat="SMA">SMA</button>
                <button type="button" data-cat="Bahasa">Bahasa</button>
                <button type="button" data-cat="Ekstra">Ekstra</button>
            </div>
        </div>

        <div id="katalogGrid" class="katalog-grid"></div>
        <div id="katalogEmpty" class="katalog-empty">Tidak ada kelas yang cocok dengan pencarianmu.</div>
    </div>
@endsection

@push('styles')
    @vite(['resources/css/dashboard/site.css', 'resources/css/dashboard/katalog.css'])
@endpush

@push('scripts')
    <script>
        window.pintarKuyKatalog = @json($katalog);
        window.pintarKuyTerdaftarIds = @json($terdaftarIds);
        window.pintarKuyDaftarUrl = @json(route('dashboard.katalog.daftar'));
        window.pintarKuyUpgradeUrl = @json(route('dashboard.paket.upgrade'));
        window.pintarKuyPaket = @json(auth()->user()->paket);
        window.pintarKuyKelasUrl = window.pintarKuyKelasUrl || @json(route('dashboard.kelas'));
        window.pintarKuyPaketUrl = window.pintarKuyPaketUrl || @json(route('home') . '#program');
    </script>
    @vite(['resources/js/dashboard/katalog.js'])
@endpush