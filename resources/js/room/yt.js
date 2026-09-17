// PintarKuy - Room: pemutar YouTube (IFrame API) untuk sinkron play/pause/seek guru -> siswa
export const YT_STATE = { ENDED: 0, PLAYING: 1, PAUSED: 2, BUFFERING: 3, CUED: 5 };

let apiPromise = null;

function ensureApi() {
    if (apiPromise) return apiPromise;

    apiPromise = new Promise((resolve) => {
        if (window.YT && window.YT.Player) return resolve();

        if (!document.getElementById('pk_yt_sdk')) {
            const t = document.createElement('script');
            t.id = 'pk_yt_sdk';
            t.src = 'https://www.youtube.com/iframe_api';
            document.head.appendChild(t);
        }

        const prev = window.onYouTubeIframeAPIReady;
        window.onYouTubeIframeAPIReady = () => {
            try { if (typeof prev === 'function') prev(); } catch (_) {}
            resolve();
        };

        setTimeout(() => {
            // SDK belum siap — reset promise supaya pemanggilan berikutnya retry.
            apiPromise = null;
            resolve();
        }, 10000);
    });

    return apiPromise;
}

export function mountYtPlayer(host, videoId, onEvent, opts = {}) {
    if (!host) return null;

    let pendingId = null;
    const initialId = videoId;

    const ctl = {
        id: videoId,
        ready: false,
        player: null,
        state: () => (ctl.player && ctl.ready ? ctl.player.getPlayerState() : -1),
        time: () => (ctl.player && ctl.ready ? (ctl.player.getCurrentTime() || 0) : 0),
        play: () => { if (ctl.player && ctl.ready) { try { ctl.player.playVideo(); } catch (_) {} } },
        pause: () => { if (ctl.player && ctl.ready) { try { ctl.player.pauseVideo(); } catch (_) {} } },
        seek: (t) => { if (ctl.player && ctl.ready) { try { ctl.player.seekTo(t, true); } catch (_) {} } },
        // Ganti video TANPA destroy/recreate pada instance yang sama. Menghindari
        // iframe hitam yang muncul bila player di-destroy lalu dibuat ulang saat
        // guru/siswa berganti-ganti modul video. Bila player belum ready, id baru
        // dimuat saat onReady.
        load: (newId) => {
            ctl.id = newId;
            if (!ctl.player) return;
            if (ctl.ready) {
                try { ctl.player.loadVideoById(newId); } catch (_) {}
            } else {
                pendingId = newId;
            }
        },
        destroy: () => {
            if (ctl.player) { try { ctl.player.destroy(); } catch (_) {} }
            ctl.player = null;
            ctl.ready = false;
            pendingId = null;
        },
    };

    ensureApi().then(() => {
        if (!window.YT || !window.YT.Player) return;
        if (!host.isConnected) return;

        const idc = 'pkyt' + Math.random().toString(36).slice(2, 8);
        host.id = idc;

        ctl.player = new window.YT.Player(idc, {
            videoId: pendingId || initialId,
            playerVars: Object.assign({ rel: 0, playsinline: 1, modestbranding: 1 }, opts.playerVars || {}),
            events: {
                onReady: () => {
                    ctl.ready = true;
                    if (pendingId && pendingId !== initialId) {
                        try { ctl.player.loadVideoById(pendingId); } catch (_) {}
                    }
                    pendingId = null;
                    try { onEvent && onEvent('ready', ctl); } catch (_) {}
                },
                onStateChange: (e) => {
                    try { onEvent && onEvent('state', ctl, e.data); } catch (_) {}
                },
                onError: () => {
                    try { onEvent && onEvent('error', ctl); } catch (_) {}
                },
            },
        });
        window.__pkYtPlayer = ctl.player;
    });

    return ctl;
}

export function youtubeIdOf(url) {
    const m = String(url || '').match(/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/|live\/)|youtu\.be\/)([\w-]{6,})/);
    return m ? m[1] : null;
}