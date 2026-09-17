// PintarKuy - Room: controller voice (LiveKit) — join/leave/mute + daftar peserta
import { Room, RoomEvent } from 'livekit-client';
import { esc, initialsOf } from './render.js';

const AVATAR_COLORS = ['#101A2E', '#0F766E', '#8A6510', '#4C1D95', '#9D174D', '#1D4ED8'];

const colorOf = (s) => {
    let h = 0;
    const str = String(s || 'P');
    for (let i = 0; i < str.length; i++) h = (h * 31 + str.charCodeAt(i)) >>> 0;
    return AVATAR_COLORS[h % AVATAR_COLORS.length];
};

export function createVoiceController(ctx) {
    const els = {
        join: document.getElementById(ctx.ui?.joinBtn || 'roomJoinBtn'),
        mic: document.getElementById(ctx.ui?.micBtn || 'roomMicBtn'),
        leave: document.getElementById(ctx.ui?.leaveBtn || 'roomLeaveBtn'),
        list: document.getElementById(ctx.ui?.participantsEl || 'roomPeserta'),
        count: document.getElementById(ctx.ui?.countEl || 'roomPesertaCount'),
        state: document.getElementById(ctx.ui?.stateEl || 'roomVoiceState'),
    };

    if (!els.join || !els.list) return null;

    let lk = null;
    let identity = null;
    let speakers = new Set();

    const csrf = () => document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    const emptyMsg = '<p class="room-empty-peserta">Belum ada peserta. Join voice untuk mulai.</p>';

    const setState = (txt, mode) => {
        if (!els.state) return;
        els.state.textContent = txt;
        els.state.className = 'room-badge' + (mode === 'live' ? ' room-badge--online' : ' room-badge--locked');
    };

    const micOn = (p) => {
        let on = false;
        if (!p || !p.audioTrackPublications) return false;
        p.audioTrackPublications.forEach((ap) => {
            if (!ap.isMuted && ap.track && ap.track.isEnabled !== false) on = true;
        });
        return on;
    };

    const render = () => {
        if (!lk) {
            els.list.innerHTML = emptyMsg;
            if (els.count) els.count.textContent = '0';
            return;
        }

        const parts = [lk.localParticipant, ...lk.remoteParticipants.values()];
        if (els.count) els.count.textContent = String(parts.length);

        els.list.innerHTML = parts.map((p) => {
            const isYou = p === lk.localParticipant || p.identity === identity;
            const on = micOn(p);
            const speaking = speakers.has(p.identity);
            const name = p.name || p.identity || 'Peserta';

            const wave = (
                '<span class="room-peserta-wave' + (speaking ? ' is-speaking' : '') + '" title="' + (speaking ? 'Sedang bicara' : 'Diam') + '">' +
                '<i></i><i></i><i></i></span>'
            );

            let tags = '';
            if (isYou) tags += '<span class="room-peserta-tag room-peserta-tag--you">Anda</span>';
            if (speaking) tags += '<span class="room-peserta-tag room-peserta-tag--speaking">Bicara</span>';
            else if (on) tags += '<span class="room-peserta-tag room-peserta-tag--quiet">Diam</span>';
            tags += on
                ? '<span class="room-peserta-tag room-peserta-tag--mic-on">Mic ON</span>'
                : '<span class="room-peserta-tag room-peserta-tag--mic-off">Mic OFF</span>';

            return (
                '<div class="room-peserta-item">' +
                '<span class="room-peserta-avatar" style="background:' + colorOf(p.identity) + ';">' + esc(initialsOf(name)) + '</span>' +
                '<div class="room-peserta-info"><p class="room-peserta-name">' + esc(name) + '</p>' +
                '<p class="room-peserta-meta">' + (isYou ? 'Anda' : 'Peserta') + '</p></div>' +
                '<div class="room-peserta-status">' + wave + tags + '</div></div>'
            );
        }).join('');

        const count = parts.length;
        setState(count + ' online' + (count ? ' • voice aktif' : ''), 'live');
    };

    const bind = () => {
        lk.on(RoomEvent.ParticipantConnected, render);
        lk.on(RoomEvent.ParticipantDisconnected, render);
        lk.on(RoomEvent.TrackSubscribed, render);
        lk.on(RoomEvent.TrackUnsubscribed, render);
        lk.on(RoomEvent.TrackMutedChanged, render);
        lk.on(RoomEvent.LocalTrackPublished, render);
        lk.on(RoomEvent.ActiveSpeakersChanged, (list) => {
            speakers = new Set((list || []).map((p) => p.identity));
            render();
        });
        lk.on(RoomEvent.ConnectionStateChanged, () => {
            if (lk && lk.connectionState === 'disconnected') {
                els.list.innerHTML = emptyMsg;
                if (els.count) els.count.textContent = '0';
                setState('Terputus', 'err');
                els.join.disabled = false;
                els.mic.disabled = true;
                els.leave.disabled = true;
                els.mic.classList.remove('is-muted');
                if (els.mic) {
                    const svg = els.mic.querySelector('svg');
                    els.mic.innerHTML = (svg ? svg.outerHTML : '') + ' Mute';
                }
            }
        });
    };

    const join = async () => {
        if (lk) return;
        els.join.disabled = true;
        setState('Menghubungkan…', 'busy');
        try {
            const res = await fetch(ctx.tokenUrl, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf(),
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ room: ctx.slug }),
            });
            const data = await res.json();
            if (!res.ok || !data.ok || !data.token) throw new Error(data.message || 'Gagal membuat token LiveKit.');

            lk = new Room({ adaptiveStream: true });
            identity = data.identity || null;
            bind();
            await lk.connect(data.url, data.token);

            let micOk = true;
            try {
                await lk.localParticipant.setMicrophoneEnabled(true);
            } catch (_) {
                micOk = false;
            }

            els.leave.disabled = false;
            els.mic.disabled = false;
            if (micOk) {
                setState('Terhubung • mic aktif', 'live');
            } else {
                els.mic.classList.add('is-muted');
                const svg = els.mic.querySelector('svg');
                els.mic.innerHTML = (svg ? svg.outerHTML : '') + ' Unmute';
                setState('Terhubung • mic dimatikan', 'live');
                if (els.state) els.state.title = 'Mic tidak bisa diakses (izin ditolak / bukan localhost-https) — kamu tetap bisa mendengar dan melihat peserta.';
            }
            render();
        } catch (err) {
            if (lk) { try { lk.disconnect(); } catch (_) {} }
            lk = null;
            identity = null;
            els.join.disabled = false;
            setState('Gagal terhubung', 'err');
            if (els.state) els.state.title = String(err && err.message ? err.message : err);
        } finally {
            els.join.disabled = false;
        }
    };

    const toggleMute = async () => {
        if (!lk) return;
        const willMute = !(els.mic && els.mic.classList.contains('is-muted'));
        if (els.mic) {
            els.mic.classList.toggle('is-muted', willMute);
            const svg = els.mic.querySelector('svg');
            els.mic.innerHTML = (svg ? svg.outerHTML : '') + (willMute ? ' Unmute' : ' Mute');
            els.mic.disabled = true;
        }
        try { await lk.localParticipant.setMicrophoneEnabled(!willMute); } catch (_) {}
        if (els.mic) els.mic.disabled = false;
        setState(willMute ? 'Terhubung • mic diam' : 'Terhubung • mic aktif', 'live');
        render();
    };

    const leave = async () => {
        if (!lk) return;
        try { lk.disconnect(); } catch (_) {}
        lk = null;
        identity = null;
        speakers = new Set();
        els.mic.classList.remove('is-muted');
        els.mic.disabled = true;
        els.leave.disabled = true;
        els.join.disabled = false;
        const svg = els.mic.querySelector('svg');
        els.mic.innerHTML = (svg ? svg.outerHTML : '') + ' Mute';
        render();
        setState('Belum terhubung');
    };

    els.join.addEventListener('click', join);
    if (els.mic) els.mic.addEventListener('click', toggleMute);
    if (els.leave) els.leave.addEventListener('click', leave);
    if (els.join.dataset.autojoin === '1') join();

    return { join, toggleMute, leave, connected: () => !!lk, render };
}