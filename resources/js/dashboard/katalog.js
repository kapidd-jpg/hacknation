// PintarKuy — Dashboard: Katalog (render + filter kategori + search)
document.addEventListener('DOMContentLoaded', () => {
    const katalogData = [
        { cat: 'UTBK-SNBT', ico: 'PU', name: 'TPS Penalaran Umum', meta: 'Kelas 12 · Persiapan UTBK', desc: 'Logika, analisis, dan penalaran kuantitatif berpola SNBT.', modul: 32, durasi: '12 Minggu', siswa: 284, price: 'Rp 599K', old: 'Rp 799K', bg: 'rgba(126,252,154,0.35)', color: '#007433' },
        { cat: 'UTBK-SNBT', ico: 'BI', name: 'Literasi Bahasa Indonesia', meta: 'Kelas 12 · Persiapan UTBK', desc: 'Teknik membaca cepat dan inferensi untuk teks panjang.', modul: 28, durasi: '10 Minggu', siswa: 231, price: 'Rp 499K', old: 'Rp 699K', bg: 'rgba(222,225,255,1)', color: '#111c4e' },
        { cat: 'UTBK-SNBT', ico: 'MS', name: 'Matematika Saintek', meta: 'Kelas 12 · Saintek', desc: 'Integral, trigonometri, statistika dengan trik cepat 20 detik.', modul: 30, durasi: '12 Minggu', siswa: 318, price: 'Rp 649K', old: 'Rp 849K', bg: 'rgba(254,243,199,1)', color: '#92400e' },
        { cat: 'SMA', ico: 'FM', name: 'Fisika Mekanika', meta: 'Kelas 11 · Wajib & Peminatan', desc: 'Kinematika, dinamika, dan energi dengan pendekatan visual.', modul: 24, durasi: '10 Minggu', siswa: 197, price: 'Rp 449K', old: 'Rp 599K', bg: 'rgba(237,233,254,1)', color: '#6d28d9' },
        { cat: 'SMA', ico: 'KN', name: 'Kimia Dasar & Stoikiometri', meta: 'Kelas 10-11 · Wajib', desc: 'Perhitungan kimia dan konsep mol yang dibuat simpel.', modul: 22, durasi: '9 Minggu', siswa: 164, price: 'Rp 399K', old: 'Rp 549K', bg: 'rgba(126,252,154,0.35)', color: '#007433' },
        { cat: 'SMA', ico: 'BG', name: 'Biologi Sel & Genetika', meta: 'Kelas 12 · Peminatan Saintek', desc: 'Sel, hereditas, dan bioteknologi dengan peta konsep.', modul: 26, durasi: '10 Minggu', siswa: 152, price: 'Rp 429K', old: 'Rp 579K', bg: 'rgba(222,225,255,1)', color: '#111c4e' },
        { cat: 'Bahasa', ico: 'TR', name: 'TOEFL & English Daily', meta: 'Semua jenjang', desc: 'Naikkan skor TOEFL dan percakapan harian.', modul: 20, durasi: '8 Minggu', siswa: 208, price: 'Rp 529K', old: 'Rp 699K', bg: 'rgba(254,243,199,1)', color: '#92400e' },
        { cat: 'Bahasa', ico: 'IV', name: 'Bahasa Inggris Literasi', meta: 'Kelas 12 · UTBK', desc: 'Reading comprehension dan grammar level HOTS SNBT.', modul: 24, durasi: '10 Minggu', siswa: 175, price: 'Rp 469K', old: 'Rp 629K', bg: 'rgba(222,225,255,1)', color: '#111c4e' },
        { cat: 'Ekstra', ico: 'PY', name: 'Coding Python Dasar', meta: 'Ekstra · Maks 25 siswa', desc: 'Logika pemrograman dan sains data untuk pemula.', modul: 18, durasi: '8 Minggu', siswa: 121, price: 'Rp 399K', old: 'Rp 499K', bg: 'rgba(237,233,254,1)', color: '#6d28d9' },
        { cat: 'Ekstra', ico: 'WD', name: 'Web Design & UI/UX', meta: 'Ekstra · Studio Kreatif', desc: 'Dari wireframe sampai prototype interaktif.', modul: 16, durasi: '7 Minggu', siswa: 98, price: 'Rp 349K', old: 'Rp 449K', bg: 'rgba(222,225,255,1)', color: '#111c4e' },
    ];
    const added = new Set();

    const grid = document.getElementById('katalogGrid');
    const empty = document.getElementById('katalogEmpty');
    const search = document.getElementById('katalogSearch');
    const filters = document.querySelectorAll('#katalogFilter button');

    const cardHTML = (item) => `
        <article class="katalog-card">
            <div class="katalog-top">
                <span class="katalog-icon" style="background:${item.bg};color:${item.color}">${item.ico}</span>
                <span class="katalog-tag">${item.cat}</span>
            </div>
            <h3>${item.name}</h3>
            <p class="katalog-meta">${item.meta}</p>
            <p class="katalog-desc">${item.desc}</p>
            <div class="katalog-stats">
                <span class="katalog-stat">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.25278V19.25M12 6.25278C10.8321 5.47686 9.24649 5 7.5 5C5.75351 5 4.16789 5.47686 3 6.25278V19.25C4.16789 18.4741 5.75351 18 7.5 18C9.24649 18 10.8321 18.4741 12 19.25M12 6.25278C13.1679 5.47686 14.7535 5 16.5 5C18.2465 5 19.8321 5.47686 21 6.25278V19.25C19.8321 18.4741 18.2465 18 16.5 18C14.7535 18 13.1679 18.4741 12 19.25"/></svg>
                    ${item.modul} Modul
                </span>
                <span class="katalog-stat">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1a4 4 0 10-4-4 4 4 0 004 4z"/></svg>
                    ${item.siswa}
                </span>
            </div>
            <div class="katalog-hr"></div>
            <div class="katalog-foot">
                <p class="katalog-price"><small>${item.old}</small> ${item.price}/bln</p>
                <button type="button" class="katalog-add" data-name="${item.name}">+ Daftar</button>
            </div>
        </article>`;

    const render = () => {
        const cat = document.querySelector('#katalogFilter button.active').dataset.cat;
        const q = search.value.trim().toLowerCase();
        const list = katalogData.filter((item) =>
            (cat === 'Semua' || item.cat === cat) &&
            (q === '' || (item.name + ' ' + item.desc + ' ' + item.meta).toLowerCase().includes(q))
        );
        grid.innerHTML = list.map(cardHTML).join('');
        empty.classList.toggle('is-visible', list.length === 0);

        grid.querySelectorAll('.katalog-add').forEach((btn) => {
            const name = btn.dataset.name;
            if (added.has(name)) {
                btn.textContent = '✓ Terdaftar';
                btn.classList.add('added');
                btn.disabled = true;
            }
            btn.addEventListener('click', () => {
                added.add(name);
                btn.textContent = '✓ Terdaftar';
                btn.classList.add('added');
                btn.disabled = true;
                window.setTimeout(() => {
                    window.location.href = pintarKuyKelasUrl;
                }, 900);
            });
        });
    };

    filters.forEach((btn) => btn.addEventListener('click', () => {
        filters.forEach((b) => b.classList.remove('active'));
        btn.classList.add('active');
        render();
    }));
    search.addEventListener('input', render);

    render();

    const revealEls = document.querySelectorAll('.dash-reveal');
    revealEls.forEach((el) => el.classList.add('is-visible'));
});