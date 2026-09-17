@extends('layout.dashboard')

@section('title', 'Pilih Paket - PintarKuy')

@section('pageContent')
<div class="flex flex-col gap-6">
    <div class="dash-head dash-reveal">
        <div>
            <h1>Pilih Paket Belajarmu</h1>
            <p class="dash-head-sub">
                Halo <span class="font-bold text-ink">{{ auth()->user()->name }}</span>
                ({{ auth()->user()->kelas_jurusan ?? '-' }} · {{ auth()->user()->sekolah ?? '-' }}).
                Kamu bisa membeli satu paket atau kombinasi beberapa paket sekaligus.
                Setelah aktif, daftar kelas di kategorinya gratis tanpa biaya tambahan.
            </p>
        </div>
        <div class="dash-pill dash-pill--green">
            {{ count($ownedKeys) ? 'Paket aktif: ' . strtoupper(str_replace('-', ' ', implode(', ', $ownedKeys))) : 'Belum punya paket' }}
        </div>
    </div>

    <div class="dash-card dash-reveal" style="padding:18px 22px;display:flex;align-items:center;gap:14px;flex-wrap:wrap;">
        <div class="flex items-center gap-2.5 text-sm font-semibold">
            <span class="flex items-center gap-1.5 text-ink-soft">
                <span class="flex items-center justify-center size-6 rounded-full bg-sukses text-white text-xs">
                    <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                </span>
                Daftar
            </span>
            <span class="paket-sep"></span>
            <span class="flex items-center gap-1.5 text-ink">
                <span class="flex items-center justify-center size-6 rounded-full bg-brick text-white text-xs">2</span>
                Pilih Paket
            </span>
            <span class="paket-sep"></span>
            <span class="flex items-center gap-1.5 text-ink-muted">
                <span class="flex items-center justify-center size-6 rounded-full bg-paper-100 text-ink-muted text-xs">3</span>
                Pembayaran
            </span>
        </div>
        <p class="text-xs text-ink-muted ml-auto">Pembayaran simulasi, tidak ada uang yang benar-benar ditransfer.</p>
    </div>

    <div class="w-full grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch">
        @foreach ($pakets as $paket)
            @php $owned = in_array($paket->key, $ownedKeys, true); @endphp
            @include('komponen.kartu-paket', [
                'paket' => $paket,
                'ctaUrl' => route('paket.checkout', $paket->key),
                'ctaLabel' => $owned ? 'Sudah Aktif' : 'Beli Paket Ini',
                'owned' => $owned,
                'hasAnyPaket' => $hasAnyPaket ?? false,
                'revealClass' => 'reveal dash-reveal',
                'delay' => ($loop->iteration * 0.15) . 's',
            ])
        @endforeach
    </div>
</div>
@endsection

@push('styles')
    @vite(['resources/css/dashboard/site.css', 'resources/css/dashboard/paket.css'])
@endpush

@push('scripts')
    @vite(['resources/js/dashboard/paket.js'])
@endpush