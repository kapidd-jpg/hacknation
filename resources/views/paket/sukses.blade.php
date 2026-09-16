@extends('layout.dashboard')

@section('title', 'Pembayaran Berhasil - PintarKuy')

@section('pageContent')
<div class="flex flex-col items-center gap-6">
    <div class="dash-card dash-reveal w-full max-w-lg flex flex-col items-center text-center gap-5 px-8 py-10">
        <span class="flex items-center justify-center size-20 rounded-full bg-green/15">
            <svg class="size-10 text-green" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </span>
        <div class="flex flex-col gap-1.5">
            <h1 class="text-2xl font-black text-navy-950 tracking-tight">Pembayaran Berhasil!</h1>
            <p class="text-sm text-ink-soft">(Mode simulasi, transaksi tidak memindahkan uang sungguhan)</p>
        </div>

        @if ($paketNama)
            <span class="dash-pill dash-pill--green text-sm px-5 py-2">Paket {{ $paketNama }} aktif</span>
        @endif

        <div class="w-full rounded-xl bg-navy-50 p-4 flex flex-col gap-3 items-start text-left">
            <div class="flex items-center justify-between w-full">
                <span class="text-xs font-semibold text-ink-muted">Nomor pembayaran</span>
                <span class="text-xs font-bold text-navy-950">{{ $nomorPembayaran ?? 'PK-' . now()->format('Ymd') . '-' . strtoupper(substr(md5((string) auth()->id()), 0, 8)) }}</span>
            </div>
            @if ($metodeNama)
                <div class="flex items-center justify-between w-full">
                    <span class="text-xs font-semibold text-ink-muted">Metode</span>
                    <span class="text-xs font-bold text-navy-950">{{ $metodeNama }}</span>
                </div>
            @endif
            <div class="flex items-center justify-between w-full">
                <span class="text-xs font-semibold text-ink-muted">Status</span>
                <span class="dash-pill dash-pill--green">LUNAS</span>
            </div>
        </div>

        <div class="flex flex-col gap-3 w-full">
            <a href="{{ route('dashboard.kelas') }}" class="dash-btn dash-btn--primary w-full">
                Mulai Belajar
                <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
            <a href="{{ route('paket.index') }}" class="dash-btn dash-btn--ghost w-full">Ganti / Lihat Paket Lain</a>
        </div>
    </div>
</div>
@endsection

@push('styles')
    @vite(['resources/css/dashboard/site.css', 'resources/css/dashboard/paket.css'])
@endpush

@push('scripts')
    @vite(['resources/js/dashboard/paket.js'])
@endpush