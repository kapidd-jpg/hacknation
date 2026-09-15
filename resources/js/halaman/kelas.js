// PintarKuy — Halaman Kelas (render data dari DB + filter kategori)
document.addEventListener('DOMContentLoaded', () => {
    const kelasData = window.pintarKuyKelas || [];

    const grid = document.getElementById('kelasGrid');
    const empty = document.getElementById('kelasEmpty');
    if (!grid || !empty) return;
    const filters = document.querySelectorAll('.kelas-filter button');

    const esc = (value) =>
        String(value == null ? '' : value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');

    const cardHTML = (item) => `
        <article class="kelas-card">
            <div class="kelas-card-top">
                <span class="kelas-ico" style="background:${esc(item.iconBg)};color:${esc(item.iconColor)}">${esc(item.ico)}</span>
                <span class="kelas-tag">${esc(item.cat)}</span>
            </div>
            <h3>${esc(item.name)}</h3>
            <p class="kelas-meta">${esc(item.meta)}</p>
            <p class="kelas-desc">${esc(item.desc)}</p>
            <div class="kelas-stats">
                <span class="kelas-stat">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.25278V19.25M12 6.25278C10.8321 5.47686 9.24649 5 7.5 5C5.75351 5 4.16789 5.47686 3 6.25278V19.25C4.16789 18.4741 5.75351 18 7.5 18C9.24649 18 10.8321 18.4741 12 19.25M12 6.25278C13.1679 5.47686 14.7535 5 16.5 5C18.2465 5 19.8321 5.47686 21 6.25278V19.25C19.8321 18.4741 18.2465 18 16.5 18C14.7535 18 13.1679 18.4741 12 19.25"/></svg>
                    ${esc(item.modul)} Modul
                </span>
                <span class="kelas-stat">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    ${esc(item.durasi)}
                </span>
                <span class="kelas-stat">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1a4 4 0 10-4-4 4 4 0 004 4z"/></svg>
                    ${esc(item.siswa)}
                </span>
            </div>
            <div class="kelas-hr"></div>
            <div class="kelas-foot">
                <p class="kelas-price"><small>${esc(item.old)}</small> ${esc(item.price)}/bln</p>
                <a href="${esc(window.pintarKuyRegisterUrl || '')}" class="kelas-cta">Daftar Kelas</a>
            </div>
        </article>`;

    const render = (cat) => {
        const list = cat === 'Semua' ? kelasData : kelasData.filter((item) => item.cat === cat);
        grid.classList.add('is-filtering');
        window.setTimeout(() => {
            grid.innerHTML = list.map(cardHTML).join('');
            empty.classList.toggle('is-visible', list.length === 0);
            grid.classList.remove('is-filtering');
        }, 120);
    };

    filters.forEach((btn) => {
        btn.addEventListener('click', () => {
            filters.forEach((b) => b.classList.remove('active'));
            btn.classList.add('active');
            render(btn.dataset.cat);
        });
    });

    render('Semua');
});