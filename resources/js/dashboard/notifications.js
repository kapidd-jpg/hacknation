// PintarKuy - Dashboard: Notifikasi (dropdown bell + aksi baca)
document.addEventListener('DOMContentLoaded', () => {
    const cfg = window.pintarKuyNotif || {};
    const wrap = document.getElementById('notifWrap');
    const toggle = document.getElementById('notifToggle');
    const panel = document.getElementById('notifPanel');
    const list = document.getElementById('notifList');
    const badge = document.getElementById('notifBadge');
    const dot = document.getElementById('notifDot');
    const readAllBtn = document.getElementById('notifReadAll');

    if (!wrap || !toggle || !panel || !list) return;

    const csrf = () => cfg.csrf || (document.querySelector('meta[name="csrf-token"]') || {}).getAttribute?.('content') || '';

    const setCount = (n) => {
        n = Number(n) || 0;
        const show = n > 0;
        if (badge) {
            badge.textContent = n > 99 ? '99+' : String(n);
            badge.hidden = !show;
        }
        if (dot) dot.hidden = !show;
    };

    const iconSvg = (type) => {
        const paths = {
            paket: 'M12 2v20m6-16H8a4 4 0 100 8h8a4 4 0 100 8H6',
            latsol: 'M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125',
            materi: 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
        };
        const d = paths[type] || 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z';
        return '<svg class="dash-notif-ico" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="' + d + '"/></svg>';
    };

    const render = (items) => {
        if (!Array.isArray(items) || items.length === 0) {
            list.innerHTML = '<div class="dash-notif-empty">Belum ada notifikasi.</div>';
            return;
        }
        list.innerHTML = items.map((n) => {
            const cls = n.unread ? ' dash-notif-item--unread' : '';
            const url = n.url ? ' data-url="' + n.url + '"' : '';
            const item = '<div class="dash-notif-item' + cls + '" data-id="' + n.id + '"' + url + '>' +
                iconSvg(n.icon || n.type) +
                '<div class="dash-notif-item-body">' +
                    '<p class="dash-notif-item-title">' + n.title + '</p>' +
                    (n.body ? '<p class="dash-notif-item-sub">' + n.body + '</p>' : '') +
                    '<span class="dash-notif-item-time">' + (n.waktu || '') + '</span>' +
                '</div>' +
                (n.unread ? '<span class="dash-notif-item-dot"></span>' : '') +
            '</div>';
            return item;
        }).join('');
    };

    const post = (url) =>
        fetch(url, {
            method: 'POST',
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf() },
        }).then((r) => r.json().catch(() => ({}))).catch(() => ({}));

    const load = () =>
        fetch(cfg.fetchUrl, { headers: { 'Accept': 'application/json' } })
            .then((r) => r.json().catch(() => ({})))
            .then((res) => {
                if (res && res.ok === true) {
                    setCount(res.unread || 0);
                    render(res.items || []);
                }
            })
            .catch(() => {});

    const open = () => {
        panel.hidden = false;
        toggle.setAttribute('aria-expanded', 'true');
        load();
    };
    const close = () => {
        panel.hidden = true;
        toggle.setAttribute('aria-expanded', 'false');
    };

    toggle.addEventListener('click', () => (panel.hidden ? open() : close()));
    document.addEventListener('click', (e) => {
        if (!wrap.contains(e.target)) close();
    });
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') close(); });

    list.addEventListener('click', (e) => {
        const item = e.target.closest('.dash-notif-item');
        if (!item) return;
        const id = item.dataset.id;
        if (item.classList.contains('dash-notif-item--unread')) {
            post(cfg.readUrl.replace('__ID__', id)).then((res) => {
                if (res && res.ok === true) setCount(res.unread || 0);
            });
            item.classList.remove('dash-notif-item--unread');
            const d = item.querySelector('.dash-notif-item-dot');
            if (d) d.remove();
        }
        const url = item.dataset.url;
        if (url) window.location.href = url;
    });

    if (readAllBtn) {
        readAllBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            post(cfg.readAllUrl).then((res) => {
                if (res && res.ok === true) {
                    setCount(0);
                    document.querySelectorAll('.dash-notif-item--unread').forEach((el) => {
                        el.classList.remove('dash-notif-item--unread');
                        const d = el.querySelector('.dash-notif-item-dot');
                        if (d) d.remove();
                    });
                }
            });
        });
    }

    load();
    window.setInterval(load, 60000);
});