@extends('layout.dashboard')

@section('title', 'Hasil Latihan - PintarKuy')

@section('pageContent')
    @php
        $benar = $p->benar;
        $total = $p->total;
        $akurasi = $p->akurasi;
        $skor = $p->skor;
        $salah = $total - $benar;
        $kosong = $p->jawaban->filter(function ($j) { return $j->pilihan === null; })->count();
        $gj = $akurasi >= 85 ? 'A' : ($akurasi >= 70 ? 'B' : 'C');
        $gjTeks = $gj === 'A' ? 'Luar biasa! Pertahankan.' : ($gj === 'B' ? 'Bagus, tingkatkan lagi supaya dapat A.' : 'Tetap semangat, ulangi latihan ini.');
        $c = round(2 * 3.14159 * 66, 2);
        $offset = round($c * (1 - $akurasi / 100), 2);
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
            <div class="latsol-ring-box">
                <svg class="latsol-ring" viewBox="0 0 148 148">
                    <circle class="latsol-ring-track" cx="74" cy="74" r="66" fill="none" stroke-width="13"/>
                    <circle class="latsol-ring-bar is-{{ strtolower($gj) }}" cx="74" cy="74" r="66" fill="none" stroke-width="13" stroke-linecap="round" stroke-dasharray="{{ $c }}" stroke-dashoffset="{{ $offset }}" transform="rotate(-90 74 74)"/>
                </svg>
                <div class="latsol-ring-center">
                    <span class="latsol-ring-val">{{ $akurasi }}</span>
                    <span class="latsol-ring-lbl">Skor</span>
                </div>
            </div>
            <div class="latsol-hasil-hero-info" style="flex:1;min-width:240px;">
                <p class="latsol-hasil-hero-title">Predikat <b>{{ $gj }}</b></p>
                <p class="latsol-hasil-hero-meta">
                    <span class="dash-pill dash-pill--green">{{ $benar }} dari {{ $total }} benar</span>
                    <span class="dash-pill">Akurasi {{ $akurasi }}%</span>
                    <span class="dash-pill">Percobaan ke-{{ $percobaan }}</span>
                    @if ($terbaik !== null)
                        <span class="dash-pill">Rekor {{ $terbaik }}%</span>
                    @endif
                </p>
                <p class="latsol-hasil-hero-sub">{{ $gjTeks }} • Dikerjakan {{ $p->created_at->format('d M Y, H:i') }}.</p>
            </div>
        </div>

        <div class="latsol-stat-grid dash-reveal">
            <div class="latsol-stat-box latsol-stat-box--green">
                <p class="latsol-stat-box-val">{{ $benar }}</p>
                <p class="latsol-stat-box-key">Jawaban Benar</p>
            </div>
            <div class="latsol-stat-box latsol-stat-box--red">
                <p class="latsol-stat-box-val">{{ $salah }}</p>
                <p class="latsol-stat-box-key">Jawaban Salah</p>
            </div>
            <div class="latsol-stat-box latsol-stat-box--muted">
                <p class="latsol-stat-box-val">{{ $kosong }}</p>
                <p class="latsol-stat-box-key">Tidak Dijawab</p>
            </div>
            <div class="latsol-stat-box latsol-stat-box--gold">
                <p class="latsol-stat-box-val">{{ $skor }}</p>
                <p class="latsol-stat-box-key">Nilai Masuk Rekap</p>
            </div>
        </div>

        <div class="dash-card dash-reveal">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;flex-wrap:wrap;">
                <p class="dash-card-title"><span class="dash-dot"></span>Pembahasan</p>
                <p class="latsol-hasil-hero-sub" style="margin:4px 0 0;">
                    <b style="color:var(--sukses);">{{ $benar }}</b> benar
                    • <b style="color:var(--danger);">{{ $salah }}</b> salah
                    @if ($kosong)
                        • <b style="color:var(--ink-muted);">{{ $kosong }}</b> kosong
                    @endif
                </p>
            </div>
            <div class="flex flex-col gap-4">
                @forelse ($p->jawaban as $j)
                    @php
                        $soal = $j->soal;
                        $opsi = $soal?->opsi ?? [];
                        $kunci = (int) ($soal?->kunci ?? 0);
                        $kunciHuruf = $opsi ? array_keys($opsi)[$kunci] : 'A';
                        $jawabHuruf = $j->pilihan !== null && $opsi ? array_keys($opsi)[$j->pilihan] : '';
                        $status = $j->benar ? 'Benar' : ($j->pilihan === null ? 'Kosong' : 'Salah');
                        $cls = $j->benar ? 'is-benar' : ($j->pilihan === null ? 'is-kosong' : 'is-salah');
                    @endphp
                    <div class="latsol-pembahasan {{ $cls }}">
                        <div class="latsol-pembahasan-head">
                            <span class="latsol-pembahasan-no">Soal {{ $loop->iteration }}</span>
                            <span class="dash-pill {{ $status === 'Benar' ? 'dash-pill--green' : '' }}" style="{{ $status === 'Salah' ? 'background:rgba(255,120,120,.15);color:#ff7a7a;' : ($status === 'Kosong' ? 'background:var(--paper);color:var(--ink-muted);' : '') }}">
                                {{ $status }}
                            </span>
                        </div>
                        <p class="latsol-soal-text" style="font-size:14px;line-height:1.65;color:var(--navy-900);font-weight:600;margin:10px 0;">{{ $soal?->pertanyaan }}</p>
                        @if ($opsi)
                            <p class="latsol-pembahasan-opsi" style="font-size:13.5px;color:var(--ink-soft);">
                                @if ($j->pilihan !== null)
                                    Jawabanmu: <b style="color:var(--navy-900);">{{ $jawabHuruf }}</b> • Kunci: <b style="color:var(--green-text);">{{ $kunciHuruf }}</b>
                                @else
                                    Kunci: <b style="color:var(--green-text);">{{ $kunciHuruf }}</b> • <b style="color:var(--ink-muted);">Tidak dijawab</b>
                                @endif
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
            <a href="{{ route('dashboard.latsol.mulai', [$p->kelas_id, $p->set_label]) }}" class="dash-btn dash-btn--primary">Ulangi Latihan</a>
            <a href="{{ route('dashboard.latsol') }}" class="dash-btn dash-btn--ghost">Kerjakan Latihan Lain</a>
        </div>
    </div>
@endsection

@push('styles')
    @vite(['resources/css/dashboard/site.css', 'resources/css/dashboard/latsol.css'])
@endpush