@extends('layout.dashboard')

@section('title', 'Checkout — PintarKuy')

@section('pageContent')
@php
    $isOwned = in_array($paket->key, $ownedKeys, true);
    $paketLain = $hasAnyPaket && ! $isOwned;
    $bayar = $paketLain ? $paket->harga_lama : $paket->harga;
@endphp
<div class="flex flex-col gap-6">
    <div class="dash-head dash-reveal">
        <div>
            <h1>Checkout Paket</h1>
            <p class="dash-head-sub">Selesaikan pembayaran simulasi untuk mengaktifkan paket <span class="font-bold text-navy-900">{{ $paket->nama }}</span>.</p>
        </div>
        <a href="{{ route('paket.index') }}" class="dash-btn dash-btn--ghost">
            <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="dash-reveal rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm px-5 py-4">
            {{ $errors->first() }}
        </div>
    @endif

    @if ($isOwned)
        <div class="dash-reveal rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm px-5 py-4 flex items-center gap-2.5">
            <svg class="size-4 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Kamu sudah memiliki paket ini. Paket tambahan bisa langsung dipilih dari halaman paket.
        </div>
    @endif

    <div class="dash-grid dash-grid--2 dash-reveal">
        <div class="dash-card flex flex-col gap-6">
            <h3 class="dash-card-title">
                <span class="dash-dot"></span>
                Ringkasan Pesanan
            </h3>
            <div class="flex flex-col gap-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-semibold text-ink-soft">Paket dipilih</span>
                    <span class="text-sm font-bold text-navy-950">{{ $paket->nama }}</span>
                </div>
                @if ($paket->tag)
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-semibold text-ink-soft">Tag</span>
                        <span class="dash-pill {{ $paket->key === 'utbk' ? 'dash-pill--green' : 'dash-pill--amber' }}">{{ $paket->tag }}</span>
                    </div>
                @endif
                <div class="flex items-center justify-between">
                    <span class="text-sm font-semibold text-ink-soft">Akses kategori</span>
                    <span class="text-sm font-bold text-navy-950">{{ implode(' · ', $paket->kategori ?? []) }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm font-semibold text-ink-soft">Batas daftar kelas</span>
                    <span class="text-sm font-bold text-navy-950">Tanpa batas</span>
                </div>
                @if ($paket->harga_lama && ! $paketLain)
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-semibold text-ink-soft">Harga asli</span>
                        <span class="text-sm font-semibold line-through text-ink-muted">Rp {{ number_format($paket->harga_lama, 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="flex items-center justify-between pt-2 border-t border-navy-100">
                    <span class="text-sm font-bold text-navy-950">Total dibayar</span>
                    <span class="text-lg font-black text-navy-950">Rp {{ number_format($bayar, 0, ',', '.') }}<span class="text-xs font-semibold text-ink-muted">/bulan</span></span>
                </div>
            </div>
            <div class="h-px w-full bg-navy-100"></div>
            <div class="flex flex-col gap-2.5">
                <p class="text-xs font-bold text-navy-900 tracking-widest uppercase mb-1">Fitur paket ini</p>
                @foreach (($paket->fitur ?? []) as $fitur)
                    <li class="flex items-start gap-2.5 text-sm text-ink-soft list-none">
                        <svg class="size-4 mt-0.5 shrink-0 text-green" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        {{ $fitur }}
                    </li>
                @endforeach
            </div>
        </div>

        <div class="dash-card flex flex-col gap-6">
            <h3 class="dash-card-title">
                <span class="dash-dot"></span>
                Metode Pembayaran
            </h3>
            <form method="POST" action="{{ route('paket.bayar') }}" class="flex flex-col gap-5" id="paketCheckoutForm">
                @csrf
                <input type="hidden" name="paket" value="{{ $paket->key }}">

                <label class="paket-metode flex items-center gap-4 p-4 bg-navy-50 rounded-xl border-2 border-transparent hover:border-navy-200 cursor-pointer transition">
                    <input type="radio" name="metode" value="va" required class="size-4 accent-navy-800">
                    <span class="flex items-center gap-3">
                        <span class="flex items-center justify-center size-10 rounded-lg bg-navy-100">
                            <svg class="size-5 text-navy-800" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </span>
                        <div>
                            <p class="text-sm font-bold text-navy-950">Virtual Account</p>
                            <p class="text-xs text-ink-muted">Bank BCA / Mandiri / BRI / BNI</p>
                        </div>
                    </span>
                    <span class="ml-auto">
                        <span class="paket-dot"></span>
                    </span>
                </label>

                <label class="paket-metode flex items-center gap-4 p-4 bg-navy-50 rounded-xl border-2 border-transparent hover:border-navy-200 cursor-pointer transition">
                    <input type="radio" name="metode" value="qris" class="size-4 accent-navy-800">
                    <span class="flex items-center gap-3">
                        <span class="flex items-center justify-center size-10 rounded-lg bg-navy-100">
                            <svg class="size-5 text-navy-800" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 16v-8m0 8h.01M16 16h.01M8 16h.01m-4 0a2 2 0 114 0 12 12 0 008 4 2 2 0 110 4 12 12 0 01-8-4z"/></svg>
                        </span>
                        <div>
                            <p class="text-sm font-bold text-navy-950">QRIS</p>
                            <p class="text-xs text-ink-muted">GoPay / OVO / DANA / ShopeePay</p>
                        </div>
                    </span>
                    <span class="ml-auto">
                        <span class="paket-dot"></span>
                    </span>
                </label>

                <label class="paket-metode flex items-center gap-4 p-4 bg-navy-50 rounded-xl border-2 border-transparent hover:border-navy-200 cursor-pointer transition">
                    <input type="radio" name="metode" value="transfer" class="size-4 accent-navy-800">
                    <span class="flex items-center gap-3">
                        <span class="flex items-center justify-center size-10 rounded-lg bg-navy-100">
                            <svg class="size-5 text-navy-800" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        </span>
                        <div>
                            <p class="text-sm font-bold text-navy-950">Transfer Bank</p>
                            <p class="text-xs text-ink-muted">Manual transfer rekening bank lokal</p>
                        </div>
                    </span>
                    <span class="ml-auto">
                        <span class="paket-dot"></span>
                    </span>
                </label>

                <button type="submit" id="paketBayarBtn" data-owned="{{ $isOwned ? '1' : '' }}" class="dash-btn dash-btn--primary w-full mt-2" @if ($isOwned) disabled @endif>
                    {{ $isOwned ? 'Paket Sudah Aktif' : 'Bayar Sekarang (Simulasi)' }}
                    @if (! $isOwned)
                        <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    @endif
                </button>
            </form>
            <div class="rounded-xl bg-amber-50 border border-amber-200 text-amber-700 text-xs font-semibold px-4 py-3 flex items-start gap-2.5">
                <svg class="size-4 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p>Ini adalah mode simulasi. Tidak ada uang yang benar-benar ditransfer. Kamu bisa mencoba alur pembayaran dengan aman.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
    @vite(['resources/css/dashboard/site.css', 'resources/css/dashboard/paket.css'])
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const radios = document.querySelectorAll('input[name="metode"]');
            const btn = document.getElementById('paketBayarBtn');
            const isOwned = btn?.dataset.owned === '1';
            const updateBtnState = () => {
                if (isOwned) { btn.disabled = true; return; }
                const checked = document.querySelector('input[name="metode"]:checked');
                if (btn) btn.disabled = !checked;
            };
            radios.forEach((r) => r.addEventListener('change', updateBtnState));
            updateBtnState();
        });
    </script>
    @vite(['resources/js/dashboard/paket.js'])
@endpush