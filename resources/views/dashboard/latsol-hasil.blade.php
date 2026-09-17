@extends('layout.dashboard')

@section('title', 'Hasil Latihan - PintarKuy')

@section('pageContent')
    @php
        $benar = $p->benar;
        $salah = $p->salah ?? $p->total - $p->benar;
        $kosong = $p->kosong ?? 0;
        $total = $p->total;
        $akurasi = $p->akurasi;
        $skor = $p->skor;
        $gj = $akurasi >= 85 ? 'A' : ($akurasi >= 70 ? 'B' : 'C');
    @endphp
    <div class="flex flex-col gap-6">
        <div class="dash-head dash-reveal">
            <div>
                <a href="{{ route('dashboard.latsol') }}" class="materi-back">← Kembali ke Latihan Soal</a>
                <h1 class="materi-title">Hasil Latihan</h1>
                <p class="dash-head-sub">{{ $p->kelas?->name }} • {{ $p->set_label }}</p>
            </div>
            <div class="dash-head-actions">
                <a href="{{ route('dashboard.nilai') }}" class="dash-btn dash-btn--ghost">Lihat Nilai</a>
                <a href="{{ route('dashboard.laporan') }}" class="dash-btn dash-btn--ghost">Lihat Laporan</a>
            </div>
        </div>

        <div class="dash-card dash-reveal latsol-hasil-hero">
            <div class="latsol-score-ring" style="--score:{{ $akurasi }}%">
                <div class="latsol-score-inner">
                    <span class="latsol-score-val">{{ $akurasi }}</span>
                    <span class="latsol-score-suffix">/100</span>
                </div>
            </div>
            <div class="latsol-hasil-hero-info">
                <p class="latsol-hasil-hero-title">Skor Kamu: <b style="color:var(--green-text);">{{ $skor }}</b></p>
                <p class="latsol-hasil-hero-meta">
                    <span class="dash-pill dash-pill--green">Benar {{ $benar }}</span>
                    <span class="dash-pill" style="background:rgba(255,120,120,.15);color:#ff7a7a;">Salah {{ $salah }}</span>
                    <span class="dash-pill" style="background:rgba(154,169,196,.15);color:#64748b;">Kosong {{ $kosong }}</span>
                    <span class="dash-pill">Akurasi {{ $akurasi }}%</span>
                </p>
                <p class="latsol-hasil-hero-sub">
                    @if (isset($tuntas) && $tuntas)
                        <b style="color:var(--green-text);">✓ Tuntas</b> • Predikat <b>{{ $gj }}</b> — capai {{ $passing ?? 70 }} untuk lanjut bab berikutnya.
                    @else
                        <b style="color:#ff7a7a;">Belum tuntas</b> • Tunggu, kamu butuh skor minimal {{ $passing ?? 70 }} untuk lanjut ke bab berikutnya. Predikat <b>{{ $gj }}</b>.
                    @endif
                </p>
            </div>
        </div>

        <div class="dash-card dash-reveal">
            <p class="dash-card-title"><span class="dash-dot"></span>Pembahasan</p>
            <div class="flex flex-col gap-4">
                @forelse ($p->jawaban as $j)
                    @php
                        $soal = $j->soal;
                        $opsi = $soal?->opsi ?? [];
                        $kunci = (int) ($soal?->kunci ?? 0);
                        $kunciHuruf = $opsi ? array_keys($opsi)[$kunci] : 'A';
                    @endphp
                    <div class="latsol-pembahasan {{ $j->benar ? 'is-benar' : 'is-salah' }}">
                        <div class="latsol-pembahasan-head">
                            <span class="latsol-pembahasan-no">Soal {{ $loop->iteration }}</span>
                            <span class="dash-pill {{ $j->benar ? 'dash-pill--green' : '' }}" style="{{ !$j->benar ? 'background:rgba(255,120,120,.15);color:#ff7a7a;' : '' }}">
                                {{ $j->benar ? 'Benar' : 'Salah' }}
                            </span>
                        </div>
                        <p class="latsol-soal-text" style="font-size:14px;line-height:1.65;color:var(--navy-900);font-weight:600;margin:10px 0;">{{ $soal?->pertanyaan }}</p>
                        @if ($opsi)
                            <p class="latsol-pembahasan-opsi" style="font-size:13.5px;color:var(--ink-soft);">
                                Jawabanmu: <b style="color:var(--navy-900);">{{ $j->pilihan !== null ? array_keys($opsi)[$j->pilihan] : '-' }}</b>
                                • Kunci: <b style="color:var(--green-text);">{{ $kunciHuruf }}</b>
                            </p>
                        @endif
                        @if ($soal?->pembahasan)
                            <p class="latsol-pembahasan-teks" style="font-size:13.5px;line-height:1.7;color:var(--ink-soft);margin-top:8px;">
                                <b style="color:var(--navy-900);">Pembahasan: </b>{{ $soal->pembahasan }}
                            </p>
                        @endif
                    </div>
                @empty
                    <p class="guru-empty">Detail jawaban tidak ditemukan.</p>
                @endforelse
            </div>
        </div>

        <div style="display:flex;gap:12px;justify-content:flex-end;flex-wrap:wrap;">
            <a href="{{ route('dashboard.latsol') }}" class="dash-btn dash-btn--primary">Kerjakan Latihan Lain</a>
        </div>
    </div>
@endsection

@push('styles')
    @vite(['resources/css/dashboard/site.css', 'resources/css/dashboard/latsol.css'])
@endpush