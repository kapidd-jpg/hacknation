// PintarKuy - Dashboard: Katalog (render + filter + daftar via server → DB)
// Akses dibatasi per kategori paket: kelas di luar kategori yang dimiliki tampil terkunci.
document.addEventListener('DOMContentLoaded', () => {
    const PAKETS = {
        'utbk':       { label: 'Paket UTBK',        kategori: ['UTBK-SNBT'] },
        'sma-ekstra': { label: 'Paket SMA + Ekstra', kategori: ['SMA', 'Ekstra'] },
        'bahasa':     { label: 'Paket Bahasa',       kategori: ['Bahasa'] },
    };

    const esc = (s) => {
        const d = document.createElement('div');
        d.appendChild(document.createTextNode(String(s ?? '')));
        return d.innerHTML;
    };

    const data = window.pintarKuyKatalog || [];
    let terdaftarIds = Array.isArray(window.pintarKuyTerdaftarIds)
        ? window.pintarKuyTerdaftarIds.map(String)
        : [];
    let enrolling = false;
    const aksesKategori = Array.isArray(window.pintarKuyAksesKategori)
        ? window.pintarKuyAksesKategori
        : [];
    const hasAnyPaket = Boolean(window.pintarKuyHasAnyPaket);
    const catPaket = window.pintarKuyCatPaket || {};
    const paketKeys = Array.isArray(window.pintarKuyPaketKeys) ? window.pintarKuyPaketKeys : [];
    const daftarUrl = window.pintarKuyDaftarUrl || '/dashboard/katalog/daftar';
    const paketUrl = window.pintarKuyPaketUrl || '/pilih-paket';
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    const ownedLabels = paketKeys.map((k) => PAKETS[k]?.label).filter(Boolean);

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

    const freeLabel = (cat) => {
        const key = catPaket[cat];
        const lab = (key && PAKETS[key]) ? PAKETS[key].label : 'Paket';
        return lab;
    };

    const isOpen = (item) => aksesKategori.includes(item.cat);

    const lockPriceHTML = (item, cls = '') => (
        hasAnyPaket
            ? `<p class="katalog-price ${cls}">${esc(item.old)}/bln</p>`
            : `<p class="katalog-price ${cls}"><small>${esc(item.old)}</small> ${esc(item.price)}/bln</p>`
    );

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
                ${isOpen(item)
                    ? `<span class="katalog-free">Gratis · Termasuk ${esc(freeLabel(item.cat))}</span>`
                    : lockPriceHTML(item)}
                <button type="button" class="katalog-add" data-id="${esc(item.id)}" data-name="${esc(item.name)}">+ Daftar</button>
            </div>
        </article>`;

    const enroll = (item) => {
        if (enrolling) return;
        enrolling = true;
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
                    window.setTimeout(() => { window.location.href = window.pintarKuyKelasUrl; }, 1200);
                } else {
                    toast(res.message, false);
                }
            })
            .catch(() => {
                closeModal();
                toast('Terjadi kesalahan. Coba lagi.', false);
            })
            .finally(() => { enrolling = false; });
    };

    const openConfirm = (item) => {
        dialog.innerHTML = `
            <div class="katalog-modal-head">
                <span class="katalog-icon" style="background:${esc(item.bg)};color:${esc(item.color)}">${esc(item.ico)}</span>
                <div>
                    <p class="katalog-modal-title">Daftar Kelas Baru</p>
                    <p class="katalog-modal-sub">Konfirmasi kelas pilihanmu berikut ini. Gratis untuk anggota paket aktifmu.</p>
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
                ${isOpen(item)
                    ? `<span class="katalog-free katalog-modal-price">Gratis · Termasuk ${esc(freeLabel(item.cat))}</span>`
                    : lockPriceHTML(item, 'katalog-modal-price')}
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

    const pkPaketUrl = (key) => {
        const base = paketUrl.replace(/\/+$/, '');
        return base + '/' + encodeURIComponent(key);
    };

    const openLocked = (item) => {
        const needKey = catPaket[item.cat] || '';
        const needLabel = (needKey && PAKETS[needKey]) ? PAKETS[needKey].label : 'Paket yang sesuai';
        const buyUrl = needKey ? pkPaketUrl(needKey) : paketUrl;

        dialog.innerHTML = `
            <div class="katalog-modal-head">
                <span class="katalog-modal-ico katalog-modal-ico--warn">!</span>
                <div>
                    <p class="katalog-modal-title">Kelas Ini Perlu Paket</p>
                    <p class="katalog-modal-sub">${esc(item.name)} termasuk kategori ${esc(item.cat)}.</p>
                </div>
                <button type="button" class="katalog-modal-x" aria-label="Tutup">×</button>
            </div>
            <div class="katalog-modal-body">
                <p class="katalog-quota-note">Untuk mendaftar kelas ini, kamu butuh <b>${esc(needLabel)}</b>. Setelah paket aktif, daftar kelas di kategorinya gratis tanpa biaya tambahan, tanpa batas jumlah.</p>
                <div class="katalog-hr"></div>
                <div class="katalog-stats">
                    <span class="katalog-stat">${esc(item.modul)} Modul</span>
                    <span class="katalog-stat">${esc(item.durasi)}</span>
                    <span class="katalog-stat">${esc(item.siswa)} Siswa</span>
                </div>
            </div>
            <div class="katalog-modal-foot">
                <button type="button" class="katalog-btn katalog-btn--ghost" data-act="cancel">Nanti Saja</button>
                <a href="${esc(buyUrl)}" class="katalog-btn katalog-btn--primary">Beli ${esc(needLabel)}</a>
            </div>`;
        overlay.classList.add('is-open');
        dialog.querySelector('.katalog-modal-x').addEventListener('click', closeModal);
        dialog.querySelector('[data-act="cancel"]').addEventListener('click', closeModal);
    };

    const renderBadge = () => {
        if (!headActions) return;
        const paketText = ownedLabels.length ? ownedLabels.join(' + ') : 'Belum punya paket';
        headActions.innerHTML = `
            <span class="dash-pill dash-pill--green">Gratis daftar sesuai paket</span>
            <span class="dash-pill">${esc(terdaftarIds.length + '/' + data.filter((i) => aksesKategori.includes(i.cat)).length)} Kelas terbuka · ${esc(paketText)}</span>`;
    };

    const render = () => {
        const activeBtn = document.querySelector('#katalogFilter button.active');
        if (!activeBtn) return;
        const cat = activeBtn.dataset.cat;
        const q = search.value.trim().toLowerCase();
        const list = data.filter((item) =>
            (cat === 'Semua' || item.cat === cat) &&
            (q === '' || (item.name + ' ' + item.desc + ' ' + item.meta).toLowerCase().includes(q))
        );
        grid.innerHTML = list.map(cardHTML).join('');
        empty.classList.toggle('is-visible', list.length === 0);

        grid.querySelectorAll('.katalog-add').forEach((btn) => {
            const item = data.find((i) => String(i.id) === btn.dataset.id);
            if (!item) return;
            if (terdaftarIds.includes(String(item.id))) {
                btn.textContent = '✓ Terdaftar';
                btn.classList.add('added');
                btn.disabled = true;
            } else if (!aksesKategori.includes(item.cat)) {
                btn.textContent = 'Butuh Paket';
                btn.classList.add('full');
                btn.addEventListener('click', () => openLocked(item));
            } else {
                btn.textContent = '+ Daftar';
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