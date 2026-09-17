// PintarKuy - Dashboard Siswa: Room (sync materi live + voice + auto-follow playback video tutor)
import { listenRoom } from '../room/realtime.js';
import { createVoiceController } from '../room/voice.js';
import { renderMateri } from '../room/render.js';
import { createPresenceController } from '../room/presence.js';
import { mountYtPlayer, YT_STATE, youtubeIdOf } from '../room/yt.js';

document.addEventListener('DOMContentLoaded', () => {
    const ctx = window.pkRoomCtx;
    if (!ctx || !ctx.slug) return;

    const playerEl = document.getElementById('roomPlayer');
    const infoEl = document.getElementById('roomMateriInfo');
    const syncBadge = document.getElementById('roomSyncBadge');

    const eventToMateri = (e) => (e && e.materi_id
        ? { id: e.materi_id, judul: e.judul, tipe: e.tipe, video_url: e.video_url, konten: e.konten }
        : null);

    let lastKey = null;
    let ytCtl = null;
    let ytVideoId = null;
    let lastPlay = false;
    let lastWaktu = 0;
    let lastUpdatedAt = null;

    const vidOf = (m) => (m && m.tipe !== 'teks' && m.video_url ? youtubeIdOf(m.video_url) : null);

    const syncYt = (videoId) => {
        if (videoId === ytVideoId && ytCtl) return;
        if (ytCtl) { ytCtl.destroy(); ytCtl = null; }
        ytVideoId = videoId || null;
        if (!videoId) return;

        const host = document.getElementById('pkYtHost');
        if (!host) return;

        ytCtl = mountYtPlayer(host, videoId, (type) => {
            if (type === 'ready') applyPlayback(lastPlay, lastWaktu);
        }, { playerVars: ctx.isStaff ? {} : { controls: 0, disablekb: 1 } });
    };

    const applyPlayback = (play, waktu) => {
        if (!ytCtl || !ytCtl.ready || !ytVideoId) return;
        const t = Number(waktu) || 0;
        try {
            if (Math.abs(ytCtl.time() - t) > 2.5) ytCtl.seek(t);
            const st = ytCtl.state();
            if (play && st !== YT_STATE.PLAYING && st !== YT_STATE.BUFFERING) ytCtl.play();
            else if (!play && st === YT_STATE.PLAYING) ytCtl.pause();
        } catch (_) {}
    };

    const applyServer = (data) => {
        if (!data || !data.ok || !data.materi) return;

        // Penjaga monotonisitas: abaikan snapshot poll yang lebih lama dari
        // update terakhir (event realtime). Event kirim updated_at null → langsung terpakai.
        if (data.updated_at) {
            const ts = new Date(data.updated_at).getTime();
            if (lastUpdatedAt !== null && Number.isFinite(ts) && ts < lastUpdatedAt) return;
            lastUpdatedAt = ts;
        }

        const m = data.materi;
        const vid = vidOf(m);
        const key = String(m.id) + '|' + (m.tipe || '') + '|' + (m.video_url || '');

        if (key !== lastKey) {
            lastKey = key;
            if (playerEl && infoEl) {
                renderMateri({
                    playerEl,
                    infoEl,
                    kelas: data.kelas,
                    materi: m,
                    halaman: data.halaman,
                    pengirim: data.pengirim,
                });
            }
            syncYt(vid);
        }

        if (data.play !== undefined) {
            lastPlay = !!data.play;
            lastWaktu = Number(data.waktu) || 0;
            applyPlayback(lastPlay, lastWaktu);
        }

        if (syncBadge && data.updated_at) {
            const t = new Date(data.updated_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
            syncBadge.innerHTML = '<span class="r-dot"></span> Sinkron \u2022 ' + t;
        } else if (syncBadge) {
            syncBadge.innerHTML = '<span class="r-dot"></span> Sinkron LIVE';
        }
    };

    const renderFromEvent = (e) => {
        if (!e) return;
        applyServer({
            ok: true,
            kelas: null,
            materi: eventToMateri(e),
            halaman: e.halaman,
            pengirim: e.pengirim,
            play: e.play,
            waktu: e.waktu,
            updated_at: null,
        });
    };

    const renderFromState = (data) => {
        if (!data || !data.ok) return;
        applyServer(data);
    };

    let pollTimer = null;

    const poll = () => fetch(ctx.stateUrl, {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
    }).then((r) => r.json()).then(renderFromState).catch(() => {});

    const rt = listenRoom(ctx.channelName, renderFromEvent);
    const startPolling = () => {
        if (pollTimer) return;
        poll();
        pollTimer = setInterval(poll, rt.realtime ? 10000 : 2500);
    };
    startPolling();

    const stopRoom = () => {
        if (pollTimer) { clearInterval(pollTimer); pollTimer = null; }
        if (rt.stop) { try { rt.stop(); } catch (_) {} }
    };

    window.addEventListener('pagehide', stopRoom);

    // Jangan polling saat tab tersembunyi (hemat baterai), lanjutkan lagi saat terlihat.
    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            if (pollTimer) { clearInterval(pollTimer); pollTimer = null; }
        } else {
            startPolling();
        }
    });

    window.addEventListener('pointerdown', () => applyPlayback(lastPlay, lastWaktu), { once: true });

    const voiceCtl = createVoiceController({
        slug: ctx.slug,
        tokenUrl: ctx.tokenUrl,
        ui: {
            joinBtn: 'roomJoinBtn',
            micBtn: 'roomMicBtn',
            leaveBtn: 'roomLeaveBtn',
            participantsEl: 'roomPeserta',
            countEl: 'roomPesertaCount',
            stateEl: 'roomVoiceState',
        },
    });

    createPresenceController({
        url: ctx.presenceUrl,
        room: ctx.slug,
        voiceCtl,
    });
});