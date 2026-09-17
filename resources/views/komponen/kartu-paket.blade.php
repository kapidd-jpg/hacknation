@props(['paket', 'ctaUrl' => null, 'ctaLabel' => 'Mulai Sekarang', 'dataPaket' => null, 'current' => false, 'owned' => false, 'hasAnyPaket' => false, 'revealClass' => 'reveal', 'delay' => '0s'])

@php
    $rekomendasi = $paket->key === 'utbk';
    $paketLain = $hasAnyPaket && ! $owned;
    $old = (! $paketLain && $paket->harga_lama) ? 'Rp ' . number_format($paket->harga_lama, 0, ',', '.') : null;
    $price = 'Rp ' . number_format(($paketLain ? $paket->harga_lama : $paket->harga), 0, ',', '.');
    $kategori = collect($paket->kategori ?? []);
@endphp

<div class="kartu-paket {{ $rekomendasi ? 'kartu-paket--rekomendasi' : '' }} {{ $revealClass }} pk-card-hover" style="animation-delay:{{ $delay }}">
    @if ($paket->tag)
        <span class="kartu-paket-tag">{{ $paket->tag }}</span>
    @endif
    @if ($owned)
        <span class="kartu-paket-owned">
            <svg class="size-3 inline-block -mt-0.5 me-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            Paket Aktif
        </span>
    @endif

    <div class="kartu-paket-main">
        <div class="kartu-paket-head">
            <h3 class="kartu-paket-name">{{ $paket->nama }}</h3>
            <span class="kartu-paket-ico">
                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </span>
        </div>

        <div class="kartu-paket-harga">
            @if ($old)
                <p class="kartu-paket-old tabnums">{{ $old }}</p>
            @endif
            <div class="kartu-paket-price-row">
                <span class="kartu-paket-price tabnums">{{ $price }}</span>
                <span class="kartu-paket-unit">/bulan</span>
            </div>
        </div>

        <div class="kartu-paket-div"></div>

        <div class="kartu-paket-chips">
            @foreach ($kategori as $cat)
                <span class="kartu-paket-chip">{{ $cat }}</span>
            @endforeach
        </div>

        <ul class="kartu-paket-feats">
            @foreach ($paket->fitur ?? [] as $fitur)
                <li>
                    <svg class="kartu-paket-check" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    {{ $fitur }}
                </li>
            @endforeach
        </ul>
    </div>

    @if ($ctaUrl)
        <a href="{{ $ctaUrl }}" @if ($dataPaket) data-paket="{{ $dataPaket }}" @endif class="kartu-paket-cta">
            {{ $ctaLabel }}
            <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </a>
    @endif
</div>