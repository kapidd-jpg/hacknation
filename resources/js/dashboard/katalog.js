// PintarKuy — Dashboard: Katalog (render + filter + daftar via server → DB)
document.addEventListener('DOMContentLoaded', () => {
    const PAKETS = {
        'starter':  { label: 'Starter',       quota: 1 },
        'utbk-pro': { label: 'UTBK Pro',      quota: 3 },
        'golden':   { label: 'Golden Campus', quota: null },
    };
    const ORDER = ['starter', 'utbk-pro', 'golden'];

    const esc = (s) => {
        const d = document.createElement('div');
        d.appendChild(document.createTextNode(String(s ?? '')));
        return d.innerHTML;
    };

    const data = window.pintarKuyKatalog || [];
    let terdaftarIds = Array.isArray(window.pintarKuyTerdaftarIds)
        ? window.pintarKuyTerdaftarIds.map(String)
        : [];
    const daftarUrl = window.pintarKuyDaftarUrl || '/dashboard/katalog/daftar';
    const upgradeUrl = window.pintarKuyUpgradeUrl || '/dashboard/paket/upgrade';
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    const getPaket = () => {
        const key = window.pintarKuyPaket || 'utbk-pro';
        return { key, ...(PAKETS[key] || PAKETS['utbk-pro']) };
    };
    const quotaText = (paket) => (paket.quota === null ? 'Semua' : paket.quota);
    const quotaFull = (paket) => paket.quota !== null && terdaftarIds.length >= paket.quota;

    const grid = document.getElementById('katalogGrid');
    const empty = document.getElementById('katalogEmpty');
    const search = document.getElementById('katalogSearch');
    const filters = document.querySelectorAll('#katalogFilter button');
    const headActions = document.getElementById('katalogPaketBadge');

    const overlay = document.createElement('div');
    overlay.className = 'katalog-overlay';
    overlay.innerHTML = '<div class="katalog-dialog" role="dialog" aria-modal="true"></div>';
    document.body.appendChild(overlay);
    const dialog = overlay.querySelector('.katalog-dialog');
    const closeModal = () => overlay.classList.remove('is-open');
    overlay.addEventListener('click', (e) => { if (e.target === overlay) closeModal(); });

    const toast = (message, ok = true) => {
        const t = document.createElement('div');
        t.className = 'katalog-toast';
        t.innerHTML = `
            <span class="katalog-toast-ico">${ok ? '✓' : '!'}</span>
            <div>
                <p class="katalog-toast-title">${esc(ok ? 'Berhasil' : 'Perhatian')}</p>
                <p class="katalog-toast-sub">${esc(message)}</p>
            </div>`;
        document.body.appendChild(t);
        requestAnimationFrame(() => t.classList.add('is-visible'));
        window.setTimeout(() => {
            t.classList.remove('is-visible');
            window.setTimeout(() => t.remove(), 500);
        }, 3600);
    };

    const cardHTML = (item) => `
        <article class="katalog-card">
            <div class="katalog-top">
                <span class="katalog-icon" style="background:${esc(item.bg)};color:${esc(item.color)}">${esc(item.ico)}</span>
                <span class="katalog-tag">${esc(item.cat)}</span>
            </div>
            <h3>${esc(item.name)}</h3>
            <p class="katalog-meta">${esc(item.meta)}</p>
            <p class="katalog-desc">${esc(item.desc)}</p>
            <div class="katalog-stats">
                <span class="katalog-stat"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.25278V19.25M12 6.25278C10.8321 5.47686 9.24649 5 7.5 5C5.75351 5 4.16789 5.47686 3 6.25278V19.25C4.16789 18.4741 5.75351 18 7.5 18C9.24649 18 10.8321 18.4741 12 19.25M12 6.25278C13.1679 5.47686 14.7535 5 16.5 5C18.2465 5 19.8321 5.47686 21 6.25278V19.25C19.8321 18.4741 18.2465 18 16.5 18C14.7535 18 13.1679 18.4741 12 19.25"/></svg>${esc(item.modul)} Modul</span>
                <span class="katalog-stat"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1a4 4 0 10-4-4 4 4 0 004 4z"/></svg>${esc(item.siswa)}</span>
            </div>
            <div class="katalog-hr"></div>
            <div class="katalog-foot">
                <p class="katalog-price"><small>${esc(item.old)}</small> ${esc(item.price)}/bln</p>
                <button type="button" class="katalog-add" data-id="${esc(item.id)}" data-name="${esc(item.name)}">+ Daftar</button>
            </div>
        </article>`;

    const enroll = (item) => {
        fetch(daftarUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({ kelas_id: item.id }),
        })
            .then((r) => r.json())
            .then((res) => {
                closeModal();
                if (res.ok) {
                    terdaftarIds = terdaftarIds.concat(String(item.id));
                    render();
                    renderBadge();
                    toast(res.message, true);
                    window.setTimeout(() => { window.location.href = pintarKuyKelasUrl; }, 1200);
                } else {
                    toast(res.message, false);
                }
            })
            .catch(() => {
                closeModal();
                toast('Terjadi kesalahan. Coba lagi.', false);
            });
    };

    const openConfirm = (item) => {
        dialog.innerHTML = `
            <div class="katalog-modal-head">
                <span class="katalog-icon" style="background:${esc(item.bg)};color:${esc(item.color)}">${esc(item.ico)}</span>
                <div>
                    <p class="katalog-modal-title">Daftar Kelas Baru</p>
                    <p class="katalog-modal-sub">Konfirmasi kelas pilihanmu berikut ini.</p>
                </div>
                <button type="button" class="katalog-modal-x" aria-label="Tutup">×</button>
            </div>
            <div class="katalog-modal-body">
                <h4>${esc(item.name)}</h4>
                <p class="katalog-meta">${esc(item.meta)}</p>
                <p class="katalog-desc">${esc(item.desc)}</p>
                <div class="katalog-stats">
                    <span class="katalog-stat">${esc(item.modul)} Modul</span>
                    <span class="katalog-stat">${esc(item.durasi)}</span>
                    <span class="katalog-stat">${esc(item.siswa)} Siswa</span>
                </div>
                <div class="katalog-hr"></div>
                <p class="katalog-price katalog-modal-price"><small>${esc(item.old)}</small> ${esc(item.price)}/bln</p>
            </div>
            <div class="katalog-modal-foot">
                <button type="button" class="katalog-btn katalog-btn--ghost" data-act="cancel">Batal</button>
                <button type="button" class="katalog-btn katalog-btn--primary" data-act="confirm">Konfirmasi Daftar</button>
            </div>`;
        overlay.classList.add('is-open');
        dialog.querySelector('.katalog-modal-x').addEventListener('click', closeModal);
        dialog.querySelector('[data-act="cancel"]').addEventListener('click', closeModal);
        dialog.querySelector('[data-act="confirm"]').addEventListener('click', () => enroll(item));
    };

    const openQuota = (item) => {
        const paket = getPaket();
        const pct = paket.quota === null ? 100 : Math.min(100, Math.round((terdaftarIds.length / paket.quota) * 100));
        const nextKey = ORDER[ORDER.indexOf(paket.key) + 1];
        const note = item
            ? `Tidak bisa menambahkan <b>${esc(item.name)}</b> karena kuota kelas paket kamu sudah penuh. Upgrade paket untuk membuka lebih banyak kelas.`
            : 'Upgrade paket sekarang untuk membuka lebih banyak kelas sekaligus fitur eksklusif (tryout, mentor, live class).';

        dialog.innerHTML = `
            <div class="katalog-modal-head">
                <span class="katalog-modal-ico katalog-modal-ico--warn">!</span>
                <div>
                    <p class="katalog-modal-title">Kuota Kelas Mencapai Batas</p>
                    <p class="katalog-modal-sub">Paket ${esc(paket.label)} membatasi maksimal ${quotaText(paket)} program.</p>
                </div>
                <button type="button" class="katalog-modal-x" aria-label="Tutup">×</button>
            </div>
            <div class="katalog-modal-body">
                <div class="katalog-quota-head">
                    <span class="katalog-quota-count">${esc(terdaftarIds.length + '/' + quotaText(paket) + ' Kelas Terpakai')}</span>
                    <span class="dash-pill">Paket ${esc(paket.label)}</span>
                </div>
                <div class="katalog-quota-bar"><span style="width:${pct}%"></span></div>
                <p class="katalog-quota-note">${note}</p>
            </div>
            <div class="katalog-modal-foot">
                <button type="button" class="katalog-btn katalog-btn--ghost" data-act="cancel">Nanti Saja</button>
                ${nextKey ? '<button type="button" class="katalog-btn katalog-btn--primary" data-act="upgrade">Upgrade</button>' : ''}
                <a href="${esc(window.pintarKuyPaketUrl)}" class="katalog-btn katalog-btn--outline" data-see-paket>Lihat Semua Paket</a>
            </div>`;
        overlay.classList.add('is-open');
        dialog.querySelector('.katalog-modal-x').addEventListener('click', closeModal);
        dialog.querySelector('[data-act="cancel"]').addEventListener('click', closeModal);
        const seeLink = dialog.querySelector('[data-see-paket]');
        if (seeLink) seeLink.addEventListener('click', () => {
            try { sessionStorage.setItem('pintarKuyUpgrade', '1'); } catch (e) {}
        });
        const up = dialog.querySelector('[data-act="upgrade"]');
        if (up) up.addEventListener('click', () => {
            if (!nextKey) return;
            up.disabled = true;
            up.textContent = 'Mengupgrade...';
            fetch(upgradeUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ paket: nextKey }),
            })
                .then((r) => r.json())
                .then((res) => {
                    closeModal();
                    if (res.ok) {
                        toast(res.message || 'Paket berhasil diupgrade!', true);
                        window.setTimeout(() => { window.location.reload(); }, 800);
                    } else {
                        toast(res.message || 'Gagal mengupgrade paket.', false);
                        up.disabled = false;
                        up.textContent = 'Upgrade';
                    }
                })
                .catch(() => {
                    closeModal();
                    toast('Terjadi kesalahan. Coba lagi.', false);
                    up.disabled = false;
                    up.textContent = 'Upgrade';
                });
        });
    };

    const renderBadge = () => {
        if (!headActions) return;
        const paket = getPaket();
        const isTop = paket.key === 'golden';
        headActions.innerHTML = `
            <span class="dash-pill dash-pill--green">Diskon 20% Periode Semester Baru</span>
            <span class="dash-pill">Paket ${esc(paket.label)} · ${esc(terdaftarIds.length + '/' + quotaText(paket))} Kelas</span>
            ${isTop
                ? '<button type="button" class="katalog-upgrade" id="katalogGanti">Ganti Paket</button>'
                : '<button type="button" class="katalog-upgrade" id="katalogUpgrade">Upgrade Paket</button>'}`;
        const gantiBtn = document.getElementById('katalogGanti');
        if (gantiBtn) gantiBtn.addEventListener('click', () => {
            try { sessionStorage.setItem('pintarKuyGanti', '1'); } catch (e) {}
            window.location.href = window.pintarKuyPaketUrl;
        });
        const upBtn = document.getElementById('katalogUpgrade');
        if (upBtn) upBtn.addEventListener('click', () => openQuota(null));
    };

    const render = () => {
        const cat = document.querySelector('#katalogFilter button.active').dataset.cat;
        const q = search.value.trim().toLowerCase();
        const list = data.filter((item) =>
            (cat === 'Semua' || item.cat === cat) &&
            (q === '' || (item.name + ' ' + item.desc + ' ' + item.meta).toLowerCase().includes(q))
        );
        grid.innerHTML = list.map(cardHTML).join('');
        empty.classList.toggle('is-visible', list.length === 0);

        const paket = getPaket();
        grid.querySelectorAll('.katalog-add').forEach((btn) => {
            const item = data.find((i) => String(i.id) === btn.dataset.id);
            if (terdaftarIds.includes(String(item.id))) {
                btn.textContent = '✓ Terdaftar';
                btn.classList.add('added');
                btn.disabled = true;
            } else if (quotaFull(paket)) {
                btn.textContent = 'Kuota Penuh';
                btn.classList.add('full');
                btn.addEventListener('click', () => openQuota(item));
            } else {
                btn.addEventListener('click', () => openConfirm(item));
            }
        });
    };

    filters.forEach((btn) => btn.addEventListener('click', () => {
        filters.forEach((b) => b.classList.remove('active'));
        btn.classList.add('active');
        render();
    }));
    search.addEventListener('input', render);

    renderBadge();
    render();

    document.querySelectorAll('.dash-reveal').forEach((el) => el.classList.add('is-visible'));
});
