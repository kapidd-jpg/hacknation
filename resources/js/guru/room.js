// PintarKuy - Guru: Kontrol Room (pilih kelas & materi, presentasi live, halaman, voice)
import { listenRoom } from '../room/realtime.js';
import { createVoiceController } from '../room/voice.js';
import { renderMateri, tipeLabel, esc } from '../room/render.js';

document.addEventListener('DOMContentLoaded', () => {
    const ctx = window.pkRoomCtx;
    if (!ctx || !ctx.slug) return;

    const kelasSelect = document.getElementById('roomKelas');
    const nav = document.getElementById('roomMateriNav');
    const playerEl = document.getElementById('roomPlayer');
    const infoEl = document.getElementById('roomMateriInfo');
    const halamanValEl = document.getElementById('roomHalamanVal');
    const halamanVal = () => halamanValEl ? parseInt(halamanValEl.textContent, 10) : 1;
    const prevBtn = document.getElementById('roomHalamanPrev');
    const nextBtn = document.getElementById('roomHalamanNext');
    const presentBtn = document.getElementById('roomPresentBtn');
    const syncBadge = document.getElementById('roomSyncBadge');

    if (!kelasSelect || !nav) return;

    const csrf = () => document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    let materis = [];
    let selected = null;
    let halaman = 1;
    let presented = false;

    const guruNama = () => {
        try { return window.pintarKuyAuth.user().name || 'Tutor'; } catch (_) { return 'Tutor'; }
    };

    const setBadge = (txt, live) => {
        if (!syncBadge) return;
        syncBadge.innerHTML = '<span class="r-dot"></span> ' + esc(txt);
        syncBadge.style.color = live ? '' : 'var(--ink-muted)';
    };

    const updateActive = () => {
        nav.querySelectorAll('.room-materi-nav-item').forEach((b) => {
            const m = materis[parseInt(b.dataset.index, 10)];
            b.classList.toggle('is-active', !!(selected && m && m.id === selected.id));
        });
    };

    const btnHtml = (m, i) =>
        '<button type="button" class="room-materi-nav-item" data-index="' + i + '"' +
        ' data-materi-id="' + m.id + '" data-kelas-id="' + kelasSelect.value + '">' +
        '<span class="room-materi-nav-num">' + String(m.urutan || i + 1).padStart(2, '0') + '</span>' +
        '<span class="room-materi-nav-info">' +
        '<span class="room-materi-nav-name">' + esc(m.judul) + '</span>' +
        '<span class="room-materi-nav-meta">' + esc(tipeLabel(m.tipe)) + ' • ' + esc(m.durasi || '15 Menit') + '</span>' +
        '</span></button>';

    const renderNav = () => {
        if (!materis.length) {
            nav.innerHTML = '<p class="room-empty-peserta">Belum ada materi untuk kelas ini.</p>';
            return;
        }
        nav.innerHTML = materis.map(btnHtml).join('');
        nav.querySelectorAll('.room-materi-nav-item').forEach((b) => {
            b.addEventListener('click', () => select(parseInt(b.dataset.index, 10)));
        });
        updateActive();
    };

    const renderPreview = () => {
        if (playerEl && infoEl) {
            renderMateri({
                playerEl,
                infoEl,
                kelas: selected ? kelasSelect.selectedOptions[0]?.textContent : null,
                materi: selected || null,
                halaman: selected ? halaman : null,
                pengirim: guruNama(),
            });
        }
        if (halamanValEl) halamanValEl.textContent = String(halaman);
    };

    const updateCtrls = () => {
        if (prevBtn) prevBtn.disabled = !selected || halaman <= 1;
        if (nextBtn) nextBtn.disabled = !selected;
        if (presentBtn) presentBtn.disabled = !selected;
    };

    const push = async () => {
        const res = await fetch(ctx.syncUrl, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf(),
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({
                room: ctx.slug,
                kelas_id: parseInt(kelasSelect.value, 10),
                materi_id: selected.id,
                halaman,
            }),
        });
        const data = await res.json().catch(() => ({}));
        return res.ok && data.ok;
    };

    const select = (idx) => {
        const m = materis[idx];
        if (!m) return;
        selected = m;
        halaman = 1;
        presented = false;
        setBadge('Siap presentasi', false);
        renderPreview();
        updateCtrls();
        updateActive();
    };

    const present = async () => {
        if (!selected) return;
        if (presentBtn) presentBtn.disabled = true;
        try {
            const ok = await push();
            if (ok) {
                presented = true;
                setBadge('Sinkron LIVE', true);
            } else {
                alert('Gagal menyinkronkan materi ke room.');
            }
        } catch (_) {
            alert('Gagal terhubung ke server.');
        } finally {
            if (presentBtn) presentBtn.disabled = false;
        }
    };

    const goHalaman = async (delta) => {
        if (!selected) return;
        const next = Math.max(1, halaman + delta);
        if (next === halaman) return;
        halaman = next;
        renderPreview();
        updateCtrls();
        if (presented) {
            try { await push(); } catch (_) {}
        }
    };

    const loadMateris = async (kelasId, keepSelection) => {
        if (!kelasId) {
            materis = [];
            selected = null;
            halaman = 1;
            presented = false;
            nav.innerHTML = '<p class="room-empty-peserta">Kamu belum mengampu kelas di section ini.</p>';
            updateCtrls();
            return;
        }
        nav.innerHTML = '<p class="room-empty-peserta">Memuat materi…</p>';
        try {
            const url = ctx.materiListUrl.replace('KELAS', kelasId);
            const res = await fetch(url, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrf() },
            });
            const data = await res.json();
            if (!res.ok || !data.ok) throw new Error(data.message || 'Gagal memuat materi.');
            materis = data.materi || [];
            if (!keepSelection) {
                selected = null;
                halaman = 1;
                presented = false;
            }
            renderNav();
            updateCtrls();
        } catch (_) {
            materis = [];
            nav.innerHTML = '<p class="room-empty-peserta">Gagal memuat materi untuk kelas ini.</p>';
            updateCtrls();
        }
    };

    const reconcileState = async () => {
        try {
            const res = await fetch(ctx.stateUrl, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            });
            const data = await res.json();
            if (!data.ok || !data.materi) return;
            if (String(data.kelas?.id) !== String(kelasSelect.value)) return;
            const idx = materis.findIndex((m) => String(m.id) === String(data.materi.id));
            if (idx === -1) return;
            selected = materis[idx];
            halaman = parseInt(data.halaman, 10) || 1;
            presented = true;
            setBadge('Sinkron LIVE', true);
            renderPreview();
            updateCtrls();
            updateActive();
        } catch (_) {}
    };

    kelasSelect.addEventListener('change', () => loadMateris(kelasSelect.value, false));
    if (presentBtn) presentBtn.addEventListener('click', present);
    if (prevBtn) prevBtn.addEventListener('click', () => goHalaman(-1));
    if (nextBtn) nextBtn.addEventListener('click', () => goHalaman(1));

    loadMateris(kelasSelect.value || ctx.aktifKelasId || '', false).then(() => {
        reconcileState();
    });
listenRoom(ctx.channelName, (e) => {
        if (!e || !halamanValEl) return;
        if (selected && e.materi_id && String(e.materi_id) === String(selected.id)) {
            if (e.halaman && e.halaman !== halaman) {
                halaman = e.halaman;
                renderPreview();
                updateCtrls();
            }
            setBadge('Sinkron LIVE', true);
        }
    });

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