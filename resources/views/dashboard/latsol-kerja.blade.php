@extends('layout.dashboard')

@section('title', $set . ' - ' . $kelas->name . ' - PintarKuy')

@section('pageContent')
    <div class="flex flex-col gap-6">
        <div class="dash-head dash-reveal">
            <div>
                <a href="{{ route('dashboard.latsol') }}" class="materi-back">← Kembali ke Latihan Soal</a>
                <h1 class="materi-title">{{ $set }}</h1>
                <p class="dash-head-sub">{{ $kelas->name }} • {{ $soals->count() }} soal • Jawab semua lalu klik "Kumpulkan".</p>
            </div>
            <div class="dash-head-actions">
                <span class="dash-pill dash-pill--green" id="latsolAnswered">0/{{ $soals->count() }} terjawab</span>
            </div>
        </div>

        <form method="POST" action="{{ route('dashboard.latsol.kirim') }}" id="latsolForm" class="flex flex-col gap-5">
            @csrf
            <input type="hidden" name="kelas_id" value="{{ $kelas->id }}">
            <input type="hidden" name="set" value="{{ $set }}">
            <input type="hidden" name="waktu_mulai" id="latsolWaktuMulai" value="{{ now()->toIso8601String() }}">

            @foreach ($soals as $i => $soal)
                <div class="dash-card dash-reveal latsol-soal" data-index="{{ $i }}">
                    <div class="latsol-soal-head">
                        <span class="dash-pill">Soal {{ $i + 1 }}</span>
                        @if ($soal->materi)
                            <span class="dash-pill" style="opacity:.7;">{{ $soal->materi->judul }}</span>
                        @endif
                    </div>
                    <p class="latsol-soal-text" style="font-size:15px;line-height:1.65;color:var(--navy-900);font-weight:600;margin:14px 0;">{{ $soal->pertanyaan }}</p>

                    <div class="latsol-opsi-list">
                        @foreach ($soal->opsi ?? ['A', 'B', 'C', 'D'] as $huruf => $teks)
                            <label class="latsol-opsi">
                                <input type="radio" name="jawaban[{{ $soal->id }}]" value="{{ $loop->index }}" required>
                                <span class="latsol-opsi-huruf">{{ $huruf }}</span>
                                <span class="latsol-opsi-teks">{{ $teks }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <div class="dash-card dash-reveal" style="display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;">
                <p class="dash-head-sub" style="margin:0;">Periksa kembali jawabanmu sebelum mengumpulkan. Nilai langsung masuk ke rekap.</p>
                <button type="submit" class="dash-btn dash-btn--primary" id="latsolSubmit">Kumpulkan Jawaban</button>
            </div>
        </form>
    </div>
@endsection

@push('styles')
    @vite(['resources/css/dashboard/site.css', 'resources/css/dashboard/latsol.css'])
@endpush

@push('scripts')
    @vite(['resources/js/dashboard/latsol.js'])
@endpush