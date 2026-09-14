@props(['paket', 'ctaUrl' => null, 'ctaLabel' => 'Mulai Sekarang', 'dataPaket' => null, 'current' => false, 'owned' => false, 'revealClass' => 'reveal', 'delay' => '0s'])

@php
    $dark = $paket->key === 'utbk';
    $old = $paket->harga_lama ? 'Rp ' . number_format($paket->harga_lama, 0, ',', '.') : null;
    $price = 'Rp ' . number_format($paket->harga, 0, ',', '.');
    $kategori = collect($paket->kategori ?? []);
@endphp

<div class="{{ $revealClass }} relative rounded-3xl p-8 shadow-card flex flex-col justify-between gap-8 {{ $dark ? 'bg-navy-800 md:-translate-y-4 ring-4 ring-brand-green/30' : 'bg-white' }}" style="animation-delay:{{ $delay }}">
    @if ($paket->tag)
        <span class="absolute -top-3.5 left-1/2 -translate-x-1/2 bg-brand-green text-white text-[11px] font-bold tracking-wide px-4 py-1.5 rounded-full shadow-lg">{{ $paket->tag }}</span>
    @endif
    @if ($owned)
        <span class="absolute top-5 right-5 bg-brand-greenlight/90 text-[#033830] text-[11px] font-bold tracking-wide px-3 py-1 rounded-full shadow-lg">Paket Aktif</span>
    @endif
    <div class="flex flex-col gap-4">
        <div class="flex items-center justify-between">
            <h3 class="font-bold text-xl {{ $dark ? 'text-white' : 'text-navy-900' }}">{{ $paket->nama }}</h3>
            <span class="flex items-center justify-center size-10 rounded-xl {{ $dark ? 'bg-white/10' : 'bg-navy-50' }}">
                <svg class="size-5 {{ $dark ? 'text-brand-greenlight' : 'text-navy-800' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </span>
        </div>
        <div class="flex flex-col gap-1">
            @if ($old)
                <p class="text-xs font-semibold line-through {{ $dark ? 'text-white/50' : 'text-ink-muted' }}">{{ $old }}</p>
            @endif
            <div class="flex items-baseline gap-1.5">
                <span class="text-4xl font-black tracking-tight {{ $dark ? 'text-white' : 'text-navy-950' }}">{{ $price }}</span>
                <span class="text-xs font-semibold {{ $dark ? 'text-white/70' : 'text-ink-muted' }}">/bulan</span>
            </div>
        </div>
        <div class="h-px w-full {{ $dark ? 'bg-white/10' : 'bg-navy-100' }}"></div>
        <div class="flex flex-wrap gap-1.5">
            @foreach ($kategori as $cat)
                <span class="text-[10px] font-bold tracking-wide uppercase px-2.5 py-1 rounded-full {{ $dark ? 'bg-white/10 text-brand-greenlight' : 'bg-navy-50 text-navy-800' }}">{{ $cat }}</span>
            @endforeach
        </div>
        <ul class="flex flex-col gap-3">
            @foreach ($paket->fitur ?? [] as $fitur)
                <li class="flex items-start gap-2.5 text-sm {{ $dark ? 'text-white/85' : 'text-ink-soft' }}">
                    <svg class="size-4 mt-0.5 shrink-0 {{ $dark ? 'text-brand-greenlight' : 'text-brand-green' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    {{ $fitur }}
                </li>
            @endforeach
        </ul>
    </div>
    @if ($ctaUrl)
        <a href="{{ $ctaUrl }}" @if ($dataPaket) data-paket="{{ $dataPaket }}" @endif class="text-center text-sm font-bold rounded-full py-3.5 transition
            {{ $dark ? 'bg-brand-greenlight text-navy-950 hover:bg-brand-greenlight/90 shadow-lg hover:shadow-[0_0_22px_rgba(94,234,212,0.75),0_0_50px_rgba(94,234,212,0.35)] hover:-translate-y-0.5' : 'bg-navy-800 text-white hover:bg-navy-950' }}">
            {{ $ctaLabel }}
        </a>
    @endif
</div>