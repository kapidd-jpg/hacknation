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
        micTest: document.getElementById(ctx.ui?.micTestBtn || 'roomMicTestBtn'),
        micTestOut: document.getElementById(ctx.ui?.micTestOut || 'roomMicTestOut'),
        listen: document.getElementById(ctx.ui?.listenBtn || 'roomListenBtn'),
        listenOut: document.getElementById(ctx.ui?.listenOut || 'roomListenOut'),
    };

    if (!els.join || !els.list) return null;

    const SOUND_THRESHOLD = 0.012;

    let lk = null;
    let identity = null;
    let speakers = new Set();
    let testingMic = false;
    let testingListen = false;

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

    const roomMicActive = () => !!(lk && els.mic && !els.mic.disabled && !els.mic.classList.contains('is-muted'));

    const makeAnalyser = (nativeTrack) => {
        const Ctx = window.AudioContext || window.webkitAudioContext;
        const ctx = new Ctx();
        if (ctx.state === 'suspended') ctx.resume().catch(() => {});
        const src = ctx.createMediaStreamSource(new MediaStream([nativeTrack]));
        const an = ctx.createAnalyser();
        an.fftSize = 2048;
        an.smoothingTimeConstant = 0.3;
        an.minDecibels = -80;
        an.maxDecibels = -30;
        src.connect(an);
        const u8 = new Uint8Array(an.frequencyBinCount);
        return {
            volume() {
                an.getByteFrequencyData(u8);
                let s = 0;
                for (let i = 0; i < u8.length; i++) s += (u8[i] / 255) * (u8[i] / 255);
                return Math.sqrt(s / u8.length);
            },
            close() {
                try { src.disconnect(); an.disconnect(); ctx.close(); } catch (_) {}
            },
        };
    };

    const testOutEl = (id) => {
        const out = document.getElementById(id);
        const meter = () => {
            let wrap = out.querySelector('.room-mictest-bar');
            if (!wrap) {
                out.innerHTML = '<span></span><span class="room-mictest-bar"><i></i></span>';
                wrap = out.querySelector('.room-mictest-bar');
            }
            return wrap.querySelector('i');
        };
        const label = (txt, mode) => {
            out.textContent = txt;
            out.className = 'room-mictest is-show' + (mode === 'ok' ? ' is-ok' : mode === 'err' ? ' is-err' : '');
        };
        return { meter, label, out };
    };

    const startMicTest = async () => {
        if (testingMic || !els.micTest || !els.micTestOut) return;
        testingMic = true;
        const btn = els.micTest;
        const { meter, label, out } = testOutEl(els.micTestOut.id);
        btn.classList.add('is-busy');
        btn.disabled = true;

        label('Meminta akses mikrofon…');
        try {
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                throw new Error('Browser tidak mendukung getUserMedia.');
            }
            const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
            const native = stream.getAudioTracks()[0];
            const an = makeAnalyser(native);
            label('Lagi merekam level… bicara / bersuara sekarang.');
            let ticks = 30;
            let peak = 0;
            let voiced = 0;
            const iv = setInterval(() => {
                const v = an.volume();
                peak = Math.max(peak, v);
                if (v > SOUND_THRESHOLD) voiced++;
                const barEl = meter();
                if (barEl) barEl.style.width = Math.min(100, Math.round(v * 180)) + '%';
                const spanEl = out.querySelector('span');
                if (spanEl) spanEl.textContent = 'Level: ' + v.toFixed(3) + (v > SOUND_THRESHOLD ? ' (terdengar)' : ' (senyap)');
                if (--ticks <= 0) {
                    clearInterval(iv);
                    an.close();
                    try { stream.getTracks().forEach((t) => t.stop()); } catch (_) {}
                    const peakTxt = 'level puncak ' + peak.toFixed(3);
                    if (peak <= 0.004) {
                        label('Mic tidak mengirim suara (' + peakTxt + '). Cek izin mikrofon, volume input, dan pastikan tombol mute perangkat tidak aktif.', 'err');
                    } else if (voiced < 3) {
                        label('Mic terbuka tapi nyaris senyap (' + peakTxt + '). Coba bicara lebih keras atau naikkan volume input — kalau tetap nol, perangkat mic kemungkinan bisu.', '');
                    } else {
                        label('OK - mic terdeteksi dan bersuara (' + peakTxt + '). ' +
                            (roomMicActive() ? 'Mic room sedang ON, kamu sudah bisa bicara.'
                                : 'Untuk bicara di room: join voice lalu nyalakan mic.'), 'ok');
                    }
                    btn.classList.remove('is-busy');
                    btn.disabled = false;
                    testingMic = false;
                }
            }, 100);
        } catch (err) {
            let msg = 'Gagal mengakses mikrofon: ' + (err && err.message ? err.message : err);
            const all = String(err && err.message ? err.message : err);
            if (/NotAllowedError|Permission|permission|denied/i.test(all)) {
                msg = 'Mic diblokir browser. Klik ikon gembok di address bar, izinkan "Mikrofon", lalu coba lagi. Halaman juga harus HTTPS atau localhost.';
            } else if (/NotFoundError|NotFound|busy|in use|NotReadable/i.test(all)) {
                msg = 'Mic tidak terdeteksi atau sedang dipakai aplikasi lain. Periksa perangkat input suara di sistemmu.';
            }
            label(msg, 'err');
            btn.classList.remove('is-busy');
            btn.disabled = false;
            testingMic = false;
        }
    };

    const startListenTest = () => {
        if (!lk || testingListen || !els.listen || !els.listenOut) return;
        testingListen = true;
        const btn = els.listen;
        const { meter, label, out } = testOutEl(els.listenOut.id);
        btn.classList.add('is-busy');
        btn.disabled = true;

        const analysers = new Map();
        const heard = new Set();
        label('Siap menerima suara peserta lain. Minta peserta lain bicara sekarang…');
        let ticks = 50;
        let peak = 0;
        let anySources = false;
        const collect = () => {
            const sources = [];
            lk?.remoteParticipants.forEach((p) => {
                p.audioTrackPublications.forEach((pub) => {
                    const native = pub.track?.mediaStreamTrack;
                    if (!native) return;
                    let an = analysers.get(pub.trackSid);
                    if (!an) {
                        an = makeAnalyser(native);
                        analysers.set(pub.trackSid, an);
                    }
                    sources.push({ an, name: p.name || p.identity || 'Peserta' });
                });
            });
            return sources;
        };

        const iv = setInterval(() => {
            const sources = collect();
            if (sources.length > 0) anySources = true;
            let maxV = 0;
            let maxName = '';
            sources.forEach((s) => {
                const v = s.an.volume();
                if (v > maxV) { maxV = v; maxName = s.name; }
                if (v > SOUND_THRESHOLD) heard.add(s.name);
            });
            peak = Math.max(peak, maxV);
            const barEl = meter();
            if (barEl) barEl.style.width = Math.min(100, Math.round(maxV * 180)) + '%';
            const spanEl = out.querySelector('span');
            if (spanEl) spanEl.textContent = sources.length
                ? 'Dengar dari: ' + maxName + ' • level ' + maxV.toFixed(3)
                : 'Belum ada mic peserta lain yang aktif…';
            if (--ticks <= 0) {
                clearInterval(iv);
                analysers.forEach((a) => a.close());
                analysers.clear();
                const names = [...heard];
                if (!anySources) {
                    label('Tidak ada mic peserta lain yang aktif selama tes. Ajak peserta lain join & nyalakan mic dulu.', 'err');
                } else if (names.length === 0) {
                    label('Mic peserta lain masuk tapi suara belum terdengar (level ' + peak.toFixed(3) + '). Coba minta peserta lain bicara lebih keras.', 'err');
                } else {
                    label('OK - kamu mendengar suara dari: ' + names.join(', ') + ' (level ' + peak.toFixed(3) + '). Voice dua arah bekerja.', 'ok');
                }
                btn.classList.remove('is-busy');
                btn.disabled = false;
                testingListen = false;
            }
        }, 200);
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
                if (els.mic) {
                    els.mic.disabled = true;
                    els.mic.classList.remove('is-muted');
                    const svg = els.mic.querySelector('svg');
                    els.mic.innerHTML = (svg ? svg.outerHTML : '') + ' Mute';
                }
                if (els.leave) els.leave.disabled = true;
                if (els.listen) els.listen.disabled = true;
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
            if (els.listen) els.listen.disabled = false;
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
        if (!lk || !els.mic) return;
        const willMute = !els.mic.classList.contains('is-muted');
        try {
            await lk.localParticipant.setMicrophoneEnabled(!willMute);
            els.mic.classList.toggle('is-muted', willMute);
            const svg = els.mic.querySelector('svg');
            els.mic.innerHTML = (svg ? svg.outerHTML : '') + (willMute ? ' Unmute' : ' Mute');
            els.mic.title = '';
            setState(willMute ? 'Terhubung • mic diam' : 'Terhubung • mic aktif', 'live');
            render();
        } catch (_) {
            // Mic ditolak/diblokir (hak izin, non-secure context, dll): jangan
            // biarkan tombol diam — tampilkan alasan supaya user tahu cara perbaiki.
            els.mic.title = 'Mic tidak bisa diakses. Gunakan https:// atau http://localhost, lalu izinkan akses mikrofon di browser.';
            if (els.state) {
                els.state.title = 'Mic tidak bisa diakses — cek izin mikrofon & buka lewat HTTPS/localhost.';
                setState('Terhubung • mic dimatikan (blokir)', 'live');
            }
            render();
        }
    };

    const leave = async () => {
        if (!lk) return;
        try { lk.disconnect(); } catch (_) {}
        lk = null;
        identity = null;
        speakers = new Set();
        if (els.mic) {
            els.mic.classList.remove('is-muted');
            els.mic.disabled = true;
            const svg = els.mic.querySelector('svg');
            els.mic.innerHTML = (svg ? svg.outerHTML : '') + ' Mute';
        }
        if (els.leave) els.leave.disabled = true;
        if (els.listen) els.listen.disabled = true;
        els.join.disabled = false;
        render();
        setState('Belum terhubung');
    };

    els.join.addEventListener('click', join);
    if (els.mic) els.mic.addEventListener('click', toggleMute);
    if (els.leave) els.leave.addEventListener('click', leave);
    if (els.micTest) els.micTest.addEventListener('click', startMicTest);
    if (els.listen) els.listen.addEventListener('click', startListenTest);
    if (els.join.dataset.autojoin === '1') join();

    return { join, toggleMute, leave, connected: () => !!lk, render, micTest: startMicTest, listenTest: startListenTest };
}