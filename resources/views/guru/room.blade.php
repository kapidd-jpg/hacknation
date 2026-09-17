@extends('layout.guru')

@section('title', $room->nama . ' - PintarKuy')

@php
    $aktifKelas = $kelasList->firstWhere('id', $state?->kelas_id) ?? $kelasList->first();
    $materiAktif = $aktifKelas ? $aktifKelas->materi()->orderBy('urutan')->get(['id', 'judul', 'tipe', 'durasi', 'urutan', 'video_url', 'konten']) : collect();
    $stMateri = $state?->materi;
    $stKelas = $state?->kelas;

    $youtubeId = fn ($url) => preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/|live\/)|youtu\.be\/)([\w-]{6,})/', (string) $url, $m) ? $m[1] : null;
    $tipeLabel = ['video' => 'Video Pembelajaran', 'teks' => 'Ringkasan Teks', 'video_teks' => 'Video + Ringkasan'];
@endphp

@section('pageContent')
    <div class="flex flex-col gap-6 dash-reveal">
        <div class="dash-head">
            <div>
                <a href="{{ route('guru.room.index') }}" class="materi-back">← Semua Ruang Belajar</a>
                <h1>{{ $room->nama }}</h1>
                <p class="dash-head-sub">Section {{ $room->kategori }} • Atur materi yang tampil di layar siswa &amp; mulai voice room.</p>
            </div>
            <div class="dash-head-actions">
                <span class="room-badge room-badge--live"><span class="r-dot"></span> LIVE SYNC</span>
                <span class="room-badge room-badge--online"><span class="r-dot"></span> <span id="roomPesertaCount">0</span> voice online</span>
                <span class="room-badge room-badge--online"><span class="r-dot"></span> <span id="roomPresenceCount">0</span> di room</span>
            </div>
        </div>

        <div class="room-layout room-layout--guru">
            {{-- ============ NAVIGATOR MATERI ============ --}}
            <div class="room-panel">
                <div class="room-panel-head">
                    <p class="room-panel-title">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        Materi
                    </p>
                </div>

                <div class="room-panel-body" style="display:flex;flex-direction:column;gap:12px;">
                    <div class="setting-field">
                        <label for="roomKelas" style="font-size:12px;margin-bottom:6px;">Kelas / Mapel</label>
                        <select id="roomKelas" class="guru-select room-kelas-select">
                            @foreach ($kelasList as $k)
                                <option value="{{ $k->id }}" @selected($aktifKelas?->id === $k->id)>{{ $k->name }}</option>
                            @endforeach
                        </select>
                        @if ($kelasList->isEmpty())
                            <p class="room-note" style="margin-top:4px;">Kamu belum mengampu kelas di section {{ $room->kategori }}. Hubungi admin untuk di-assign.</p>
                        @endif
                    </div>

                    <p class="room-panel-title" style="font-size:12.5px;">Modul Kelas <span style="color:var(--ink-muted);font-weight:500;">— klik untuk menampilkan ke siswa</span></p>
                    <div class="room-materi-nav" id="roomMateriNav">
                        @forelse ($materiAktif as $m)
                            <button type="button"
                                class="room-materi-nav-item {{ $stMateri?->id === $m->id ? 'is-active' : '' }}"
                                data-materi-id="{{ $m->id }}"
                                data-judul="{{ $m->judul }}"
                                data-tipe="{{ $m->tipe ?: 'video' }}"
                                data-video="{{ $m->video_url }}"
                                data-konten="{{ $m->konten }}"
                                data-kelas-id="{{ $aktifKelas?->id }}"
                            >
                                <span class="room-materi-nav-num">{{ str_pad($m->urutan ?: $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                <span class="room-materi-nav-info">
                                    <span class="room-materi-nav-name">{{ $m->judul }}</span>
                                    <span class="room-materi-nav-meta">{{ $tipeLabel[$m->tipe] ?? $m->tipe }} • {{ $m->durasi ?: '15 Menit' }}</span>
                                </span>
                            </button>
                        @empty
                            <p class="room-empty-peserta">Belum ada materi untuk kelas ini.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- ============ PRAVIEW LAYAR SISWA ============ --}}
            <div class="room-panel">
                <div class="room-panel-head">
                    <p class="room-panel-title">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        Live — yang dilihat siswa
                    </p>
                    <span class="room-sinkron-status" id="roomSyncBadge"><span class="r-dot"></span> Sinkron</span>
                </div>

                <div class="room-panel-body" style="display:flex;flex-direction:column;gap:12px;">
                    <div class="room-player" id="roomPlayer">
                        @if ($stMateri && $stMateri->tipe !== 'teks' && $stMateri->video_url)
                            @php $yt = $youtubeId($stMateri->video_url); @endphp
                            @if ($yt)
                                <iframe class="room-player-frame" src="https://www.youtube.com/embed/{{ $yt }}" title="Video" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            @else
                                <div class="room-player-empty" id="roomPlayerEmpty">
                                    <strong>Video tidak dapat ditayangkan</strong>
                                    <span id="roomPlayerEmptySub">URL video harus YouTube (youtube.com/watch?v=... atau youtu.be/...).</span>
                                </div>
                            @endif
                        @else
                            <div class="room-player-empty" id="roomPlayerEmpty">
                                <strong>Belum ada materi dipilih</strong>
                                <span id="roomPlayerEmptySub">Pilih modul di panel kiri untuk mulai menayangkan.</span>
                            </div>
                        @endif
                    </div>

                    <div id="roomMateriInfo">
                        @if ($stMateri)
                            <p class="room-materi-kicker">{{ $tipeLabel[$stMateri->tipe] ?? $stMateri->tipe }} • Dibawakan oleh <span class="lb-code">{{ $state?->pengirim ?: 'Tutor' }}</span></p>
                            <h2 class="room-materi-title">{{ $stMateri->judul }}</h2>
                            <p class="room-materi-kelas">{{ $stKelas?->name ?? '' }}</p>
                            <p class="room-konten">@if ($stMateri->konten) {{ $stMateri->konten }} @else <em class="room-konten-empty">Video sedang ditayangkan. Gunakan kontrol halaman di bawah untuk memandu.</em> @endif</p>
                        @else
                            <p class="room-materi-kicker">Preview</p>
                            <h2 class="room-materi-title">Belum ada materi</h2>
                            <p class="room-konten"><em class="room-konten-empty">Pilih modul di panel navigator kiri.</em></p>
                        @endif
                    </div>

                    <div class="flex items-center justify-between border-t border-ink/10 pt-3">
                        <div class="room-halaman-tools">
                            <button type="button" class="room-halaman-btn" id="roomHalamanPrev" disabled>
                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                            </button>
                            <span class="room-halaman-value">Halaman <strong id="roomHalamanVal">{{ $state?->halaman ?? 1 }}</strong></span>
                            <button type="button" class="room-halaman-btn" id="roomHalamanNext" disabled>
                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </button>
                        </div>
                        <button type="button" class="dash-btn dash-btn--primary" id="roomPresentBtn" disabled>Presentasikan →</button>
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
                        Siswa yang membuka halaman room ini tercatat otomatis. Status <code>Voice</code> berarti mereka sudah join voice room.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    @vite(['resources/css/dashboard/site.css', 'resources/css/dashboard/guru.css', 'resources/css/dashboard/room.css'])
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
            syncUrl: @json(route('room.materi')),
            presenceUrl: @json(route('room.presence')),
            kontrolUrl: @json(route('room.kontrol')),
            materiListUrl: @json(route('guru.room.materi.list', [$room->slug, 'KELAS'])),
            channelName: 'room.' + @json($room->slug),
            aktifKelasId: @json($aktifKelas?->id),
        };
    </script>
    @vite(['resources/js/guru/room.js'])
@endpush