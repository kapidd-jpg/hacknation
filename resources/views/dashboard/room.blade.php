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
                <span class="room-badge room-badge--online"><span class="r-dot"></span> <span id="roomPesertaCount">0</span> voice online</span>
                <span class="room-badge room-badge--online"><span class="r-dot"></span> <span id="roomPresenceCount">0</span> di room</span>
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
                            @if ($yt)
                                <iframe class="room-player-frame" src="https://www.youtube.com/embed/{{ $yt }}{{ auth()->user()->isStaff() ? '' : '?controls=0&disablekb=1' }}" title="Video" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            @else
                                <div class="room-player-empty" id="roomPlayerEmpty">
                                    <strong>Video tidak dapat ditayangkan</strong>
                                    <span id="roomPlayerEmptySub">URL video harus YouTube (youtube.com/watch?v=... atau youtu.be/...).</span>
                                </div>
                            @endif
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
                        <button type="button" class="room-voice-btn room-voice-btn--test" id="roomMicTestBtn" title="Cek mikrofon &amp; izin akses tanpa mengganggu peserta">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12a7 7 0 1014 0M12 5v7m0 0l-2.5-2.5M12 12l2.5-2.5"/></svg>
                            Tes Mic
                        </button>
                        <button type="button" class="room-voice-btn room-voice-btn--listen" id="roomListenBtn" disabled title="Dengarkan suara peserta lain untuk cek audio dua arah">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10v4h4l5 5V5L7 10H3zm13.5 2a4.5 4.5 0 000-9M15 12a4.5 4.5 0 004.5 4.5M15 6.5a4.5 4.5 0 010 9"/></svg>
                            Tes Dengarkan
                        </button>
                    </div>
                    <p class="room-mictest" id="roomMicTestOut"></p>
                    <p class="room-mictest" id="roomListenOut"></p>

                    <div>
                        <p class="room-panel-title" style="font-size:12.5px;">Peserta Online</p>
                        <div class="room-peserta" id="roomPeserta">
                            <p class="room-empty-peserta">Belum ada peserta. Join voice untuk mulai.</p>
                        </div>
                    </div>

                    {{-- ============ PESERTA ROOM (menonton + voice) ============ --}}
                    <div class="room-panel">
                        <div class="room-panel-head">
                            <p class="room-panel-title">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                Peserta Room
                            </p>
                            <span class="room-sinkron-status"><span class="r-dot"></span> Menonton + Voice</span>
                        </div>

                        <div class="room-panel-body" style="display:flex;flex-direction:column;gap:12px;">
                            <div class="room-peserta" id="roomPresence">
                                <p class="room-empty-peserta">Mendeteksi peserta…</p>
                            </div>
                            <p class="room-note">
                                Guru &amp; siswa yang membuka halaman room ini tercatat otomatis. Status <code>Voice</code> berarti sudah join voice room.
                            </p>
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
            presenceUrl: @json(route('room.presence')),
            channelName: 'room.' + @json($room->slug),
        };
    </script>
    @vite(['resources/js/dashboard/room.js'])
@endpush