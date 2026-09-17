// PintarKuy - Room: presence ringan (heartbeat). Guru/operator lihat siapa yang sedang di room (menonton + voice).
import { esc, initialsOf } from './render.js';

const AVATAR_COLORS = ['#101A2E', '#0F766E', '#8A6510', '#4C1D95', '#9D174D', '#1D4ED8'];

const colorOf = (s) => {
    let h = 0;
    const str = String(s || 'P');
    for (let i = 0; i < str.length; i++) h = (h * 31 + str.charCodeAt(i)) >>> 0;
    return AVATAR_COLORS[h % AVATAR_COLORS.length];
};

export function createPresenceController(ctx) {
    const { url, room, voiceCtl } = ctx;
    if (!url || !room) return null;

    const csrf = () => document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const countEl = document.getElementById('roomPresenceCount');
    const listEl = document.getElementById('roomPresence');
    let timer = null;

    const rowHtml = (p) => {
        const status = p.voice
            ? '<span class="room-peserta-tag room-peserta-tag--mic-on">Voice</span>'
            : '<span class="room-peserta-tag room-peserta-tag--quiet">Menonton</span>';
        return (
            '<div class="room-peserta-item">' +
            '<span class="room-peserta-avatar" style="background:' + colorOf(p.nama) + ';">' + esc(initialsOf(p.nama)) + '</span>' +
            '<div class="room-peserta-info">' +
            '<p class="room-peserta-name">' + esc(p.nama) + (p.iniAku ? ' <span class="room-peserta-tag room-peserta-tag--you">Anda</span>' : '') + '</p>' +
            '<p class="room-peserta-meta">' + (p.role === 'guru' ? 'Guru / Operator' : 'Siswa') + ' \u2022 ' + esc(p.sejak) + '</p>' +
            '</div>' +
            '<div class="room-peserta-status">' + status + '</div></div>'
        );
    };

    const beat = async () => {
        let voice = false;
        try { voice = !!(voiceCtl && voiceCtl.connected()); } catch (_) {}
        try {
            const res = await fetch(url, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf(),
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ room, voice }),
            });
            const data = await res.json();
            if (!data || !data.ok) return;
            if (countEl) countEl.textContent = String(data.count);
            if (listEl) {
                listEl.innerHTML = data.list && data.list.length
                    ? data.list.map(rowHtml).join('')
                    : '<p class="room-empty-peserta">Belum ada siswa di room ini.</p>';
            }
        } catch (_) {}
    };

    const start = () => {
        if (timer) return;
        beat();
        timer = setInterval(beat, 15000);
    };

    const stop = () => {
        if (timer) { clearInterval(timer); timer = null; }
    };

    document.addEventListener('visibilitychange', () => (document.hidden ? stop() : start()));
    window.addEventListener('pagehide', stop);
    start();

    return { beat };
}