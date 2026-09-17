@extends('layout.dashboard')

@section('title', $room->nama . ' - PintarKuy')

@php
    $st = $state;
    $stKelas = $st?->kelas;
    $stMateri = $st?->materi;

    $youtubeId = fn ($url) => preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/|live\/)|youtu\.be\/)([\w-]{6,})/', (string) $url, $m) ? $m[1] : null;
    $tipeLabel = ['video' => 'Video Pembelajaran', 'teks' => 'Ringkasan Teks', 'video_teks' => 'Video + Ringkasan'];
@endphp

@section('pageContent')
    <div class="flex flex-col gap-6 dash-reveal">
        <div class="dash-head">
            <div>
                <a href="{{ route('room.index') }}" class="materi-back">← Semua Ruang Belajar</a>
                <h1>{{ $room->nama }}</h1>
                <p class="dash-head-sub">Section {{ $room->kategori }} • Ikuti materi yang dibawakan tutor secara real-time dan diskusikan lewat voice.</p>
            </div>
            <div class="dash-head-actions">
                <span class="room-badge room-badge--live"><span class="r-dot"></span> Live Sync</span>
                <span class="room-badge room-badge--online"><span class="r-dot"></span> <span id="roomPesertaCount">0</span> online</span>
            </div>
        </div>

        <div class="room-layout">
            {{-- ============ MATERI LIVE ============ --}}
            <div class="room-panel">
                <div class="room-panel-head">
                    <p class="room-panel-title">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        Layar Materi
                    </p>
                    <span class="room-sinkron-status" id="roomSyncBadge"><span class="r-dot"></span> Tersinkron LIVE</span>
                </div>

                <div class="room-panel-body" style="display:flex;flex-direction:column;gap:14px;">
                    <div class="room-player" id="roomPlayer">
                        @if ($stMateri && $stMateri->tipe !== 'teks' && $stMateri->video_url)
                            @php $yt = $youtubeId($stMateri->video_url); @endphp
                            <iframe class="room-player-frame" src="https://www.youtube.com/embed/{{ $yt ?: e($stMateri->video_url) }}" title="Video" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        @else
                            <div class="room-player-empty" id="roomPlayerEmpty">
                                <strong>Menunggu tutor memulai materi</strong>
                                <span id="roomPlayerEmptySub">Belum ada materi yang dibawakan di room ini.</span>
                            </div>
                        @endif
                    </div>

                    <div id="roomMateriInfo">
                        @if ($stMateri)
                            <p class="room-materi-kicker">{{ $tipeLabel[$stMateri->tipe] ?? $stMateri->tipe }} • Dibawakan oleh <span class="lb-code">{{ $st->pengirim ?: 'Tutor' }}</span></p>
                            <h2 class="room-materi-title">{{ $stMateri->judul }}</h2>
                            <p class="room-materi-kelas">{{ $stKelas?->name ?? '' }} @if ($st->halaman) • Sedang di halaman <strong>{{ $st->halaman }}</strong> @endif</p>
                            <p class="room-konten">@if ($stMateri->konten) {{ $stMateri->konten }} @else <em class="room-konten-empty">Tutor sedang menampilkan video di halaman ini.</em> @endif</p>
                        @else
                            <p class="room-materi-kicker">Menunggu tutor</p>
                            <h2 class="room-materi-title">Belum ada materi</h2>
                            <p class="room-konten" id="roomMateriKonten"><em class="room-konten-empty">Tunggu sampai guru memilih materi di ruang ini.</em></p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ============ VOICE ROOM ============ --}}
            <div class="room-panel">
                <div class="room-panel-head">
                    <p class="room-panel-title">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11a7 7 0 01-14 0m7 7v4m-4 1h8m-7-13a2 2 0 004 0V6a2 2 0 10-4 0v3"/></svg>
                        Voice Room
                    </p>
                    <span class="room-badge room-badge--online" id="roomVoiceState">Belum terhubung</span>
                </div>

                <div class="room-panel-body" style="display:flex;flex-direction:column;gap:14px;">
                    <div class="room-voice-tools" style="flex-wrap:wrap;">
                        <button type="button" class="room-voice-btn room-voice-btn--join" id="roomJoinBtn">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3"/><path stroke-linecap="round" stroke-linejoin="round" d="M19 11a7 7 0 01-14 0"/></svg>
                            Join Voice
                        </button>
                        <button type="button" class="room-voice-btn" id="roomMicBtn" disabled>
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11a7 7 0 01-14 0m7 7v4m-4 1h8m-7-13a2 2 0 004 0V6a2 2 0 10-4 0v3"/></svg>
                            Mute
                        </button>
                        <button type="button" class="room-voice-btn room-voice-btn--leave" id="roomLeaveBtn" disabled>Keluar</button>
                    </div>

                    <div>
                        <p class="room-panel-title" style="font-size:12.5px;">Peserta Online</p>
                        <div class="room-peserta" id="roomPeserta">
                            <p class="room-empty-peserta">Belum ada peserta. Join voice untuk mulai.</p>
                        </div>
                    </div>

                    <p class="room-note">
                        Semua peserta bisa bicara &amp; berdiskusi. Matikan mic saat tidak berbicara.
                        Materi di halaman ini <strong>ikut tersinkron</strong> otomatis mengikuti tutor (<code>private-room.{{ $room->slug }}</code>).
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    @vite(['resources/css/dashboard/site.css', 'resources/css/dashboard/room.css'])
@endpush

@push('scripts')
    <script>
        window.pkRoomCtx = {
            slug: @json($room->slug),
            nama: @json($room->nama),
            kategori: @json($room->kategori),
            isStaff: @json(auth()->user()->isStaff()),
            tokenUrl: @json(route('room.token')),
            stateUrl: @json(route('room.state', $room->slug)),
            channelName: 'room.' + @json($room->slug),
        };
    </script>
    @vite(['resources/js/dashboard/room.js'])
@endpush