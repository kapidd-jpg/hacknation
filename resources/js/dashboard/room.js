// PintarKuy - Dashboard Siswa: Room (sync materi live + voice)
import { listenRoom } from '../room/realtime.js';
import { createVoiceController } from '../room/voice.js';
import { renderMateri } from '../room/render.js';

document.addEventListener('DOMContentLoaded', () => {
    const ctx = window.pkRoomCtx;
    if (!ctx || !ctx.slug) return;

    const playerEl = document.getElementById('roomPlayer');
    const infoEl = document.getElementById('roomMateriInfo');
    const syncBadge = document.getElementById('roomSyncBadge');

    const eventToMateri = (e) => (e && e.materi_id
        ? { id: e.materi_id, judul: e.judul, tipe: e.tipe, video_url: e.video_url, konten: e.konten }
        : null);

    const renderFromEvent = (e) => {
        if (playerEl && infoEl) {
            renderMateri({
                playerEl,
                infoEl,
                kelas: null,
                materi: eventToMateri(e),
                halaman: e ? e.halaman : null,
                pengirim: e ? e.pengirim : null,
            });
        }
        if (syncBadge && e) {
            syncBadge.innerHTML = '<span class="r-dot"></span> Sinkron LIVE';
        }
    };

    const renderFromState = (data) => {
        if (!data || !data.ok) return;
        if (playerEl && infoEl) {
            renderMateri({
                playerEl,
                infoEl,
                kelas: data.kelas,
                materi: data.materi,
                halaman: data.halaman,
                pengirim: data.pengirim,
            });
        }
        if (syncBadge && data.updated_at) {
            const t = new Date(data.updated_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
            syncBadge.innerHTML = '<span class="r-dot"></span> Sinkron • ' + t;
        }
    };

    let pollTimer = null;

    const poll = () => fetch(ctx.stateUrl, {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
    }).then((r) => r.json()).then(renderFromState).catch(() => {});

    const rt = listenRoom(ctx.channelName, renderFromEvent);
    if (!rt.realtime) {
        pollTimer = setInterval(poll, 6000);
        poll();
    }
    if (pollTimer) window.addEventListener('pagehide', () => clearInterval(pollTimer));

    createVoiceController({
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
});