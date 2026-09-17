@extends('layout.guru')

@section('title', 'Ruang Belajar - PintarKuy')

@php
    $groups = $rooms->groupBy(fn ($r) => $r->kategori);
    $kategoriIcon = [
        'UTBK-SNBT' => 'M3 12l9-9 9 9M5 10v10a1 1 0 001 1h4v-6h4v6h4a1 1 0 001-1V10',
        'SMA' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18s-3.332.477-4.5 1.253',
        'Bahasa' => 'M3 5h1.72l1.29 10.32A2 2 0 008.02 17h8.08m-8.86-1h7.24a2 2 0 001.93-1.5l1.53-5.5A1 1 0 0018 8H5m6-3h.01M9 21h.01M15 21h.01M7 8h1',
    ];
@endphp

@section('pageContent')
    <div class="flex flex-col gap-6 dash-reveal">
        <div class="dash-head">
            <div>
                <h1>Ruang Belajar</h1>
                <p class="dash-head-sub">Pilih room untuk mulai mengajar live: kendalikan materi yang tampil ke semua siswa di room, lengkap dengan voice.</p>
            </div>
            <div class="dash-head-actions">
                <span class="room-badge room-badge--live"><span class="r-dot"></span> LIVE SYNC</span>
            </div>
        </div>

        @forelse ($groups as $kategori => $roomsGroup)
            <section class="flex flex-col gap-3">
                <div class="flex items-center gap-2.5 border-b border-ink/15 pb-2">
                    <span class="w-8 h-8 grid place-items-center rounded-md bg-paper-100 border border-ink/10 text-ink">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $kategoriIcon[$kategori] ?? 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z' }}"/></svg>
                    </span>
                    <h2 class="lb-headline-sm text-ink font-bold">{{ $kategori }}</h2>
                </div>

                <div class="room-grid">
                    @foreach ($roomsGroup as $room)
                        <div class="room-card">
                            <div class="room-card-head">
                                <span class="room-card-ico is-live">
                                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11a7 7 0 01-14 0m7 7v4m-4 1h8M12 11a2 2 0 002-2V6a2 2 0 10-4 0v3a2 2 0 002 2z"/></svg>
                                </span>
                                <span class="room-badge room-badge--live">Ready</span>
                            </div>
                            <div>
                                <p class="room-card-kategori">Section {{ $room->kategori }}</p>
                                <h3 class="room-card-nama">{{ $room->nama }}</h3>
                                <p class="room-card-meta">Mulai sesi: pilih kelas lalu navigasikan materi. Semua siswa di room mengikuti secara real-time.</p>
                            </div>
                            <div class="room-card-rate">
                                <span class="room-badge room-badge--online">
                                    <svg class="size-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2a5 5 0 015 5v3a1 1 0 001 1 3 3 0 013 3v5a3 3 0 01-3 3H6a3 3 0 01-3-3v-5a3 3 0 013-3 1 1 0 001-1V7a5 5 0 015-5z"/></svg>
                                    Voice + Materi
                                </span>
                                <a href="{{ route('guru.room.show', $room->slug) }}" class="dash-btn dash-btn--primary dash-btn--sm">Mulai Mengajar →</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @empty
            <div class="dash-card" style="padding:48px;text-align:center;">
                <p style="font-size:40px;margin-bottom:8px;">🎙️</p>
                <p class="materi-title" style="font-size:22px;margin-bottom:6px;">Belum ada room aktif</p>
                <p class="dash-head-sub">Room belajar akan segera hadir.</p>
            </div>
        @endforelse
    </div>
@endsection

@push('styles')
    @vite(['resources/css/dashboard/site.css', 'resources/css/dashboard/guru.css', 'resources/css/dashboard/room.css'])
@endpush