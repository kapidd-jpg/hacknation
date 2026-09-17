// PintarKuy - Room: realtime (Echo/Pusher) + polling fallback bila broadcaster belum dikonfigurasi
import Pusher from 'pusher-js';
import Echo from 'laravel-echo';

let echo = null;

export function initRealtime() {
    if (echo) return echo;
    if (window.Echo) { echo = window.Echo; return echo; }

    const key = import.meta.env.VITE_PUSHER_APP_KEY;
    if (!key) return null;

    try {
        window.Pusher = Pusher;
        window.Echo = echo = new Echo({
            broadcaster: 'pusher',
            key,
            cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER || 'mt1',
            wsHost: import.meta.env.VITE_PUSHER_HOST || undefined,
            wsPort: import.meta.env.VITE_PUSHER_PORT && import.meta.env.VITE_PUSHER_PORT !== '443' ? import.meta.env.VITE_PUSHER_PORT : undefined,
            wssPort: import.meta.env.VITE_PUSHER_PORT || 443,
            forceTLS: (import.meta.env.VITE_PUSHER_SCHEME || 'https') === 'https',
            enabledTransports: ['ws', 'wss'],
        });
        return echo;
    } catch (_) {
        return null;
    }
}

export function listenRoom(channelName, handler) {
    const ch = initRealtime();
    if (!ch || !handler) {
        return { realtime: false, stop: null };
    }
    try {
        ch.private(channelName).listen('.materi.changed', (e) => handler(e));
        return {
            realtime: true,
            stop: () => { try { ch.leaveChannel(channelName); } catch (_) {} },
        };
    } catch (_) {
        return { realtime: false, stop: null };
    }
}