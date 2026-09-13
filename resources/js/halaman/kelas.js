// PintarKuy — Halaman Kelas (render data + filter kategori)
document.addEventListener('DOMContentLoaded', () => {
    const kelasData = [
        {
            cat: 'UTBK-SNBT', ico: 'PU', name: 'TPS Penalaran Umum',
            meta: 'Kelas 12 SMA · Persiapan UTBK',
            desc: 'Asah logika, analisis, serta penalaran kuantitatif dengan latihan pola soal SNBT terbaru.',
            modul: 32, durasi: '12 Minggu', siswa: 284, simbol: 'P',
            price: 'Rp 599K', old: 'Rp 799K',
            iconBg: 'rgba(126, 252, 154, 0.35)', iconColor: '#007433',
        },
        {
            cat: 'UTBK-SNBT', ico: 'BI', name: 'Literasi Bahasa Indonesia',
            meta: 'Kelas 12 SMA · Persiapan UTBK',
            desc: 'Pahami teks bacaan panjang dengan cepat lewat teknik scanning, skimming, dan inferensi.',
            modul: 28, durasi: '10 Minggu', siswa: 231, simbol: 'L',
            price: 'Rp 499K', old: 'Rp 699K',
            iconBg: 'rgba(222, 225, 255, 1)', iconColor: '#111c4e',
        },
        {
            cat: 'UTBK-SNBT', ico: 'MS', name: 'Matematika Saintek',
            meta: 'Kelas 12 SMA · Saintek',
            desc: 'Integral, trigonometri, dan statistika dengan trik cepat 20 detik ala tutor master.',
            modul: 30, durasi: '12 Minggu', siswa: 318, simbol: '∑',
            price: 'Rp 649K', old: 'Rp 849K',
            iconBg: 'rgba(254, 243, 199, 1)', iconColor: '#92400e',
        },
        {
            cat: 'SMA', ico: 'FM', name: 'Fisika Mekanika',
            meta: 'Kelas 11 SMA · Wajib & Peminatan',
            desc: 'Kinematika, dinamika, dan energi dengan pendekatan visual yang gampang dicerna.',
            modul: 24, durasi: '10 Minggu', siswa: 197, simbol: 'F',
            price: 'Rp 449K', old: 'Rp 599K',
            iconBg: 'rgba(237, 233, 254, 1)', iconColor: '#6d28d9',
        },
        {
            cat: 'SMA', ico: 'KN', name: 'Kimia Dasar & Stoikiometri',
            meta: 'Kelas 10-11 SMA · Wajib',
            desc: 'Perhitungan kimia dan konsep mol dibuat simpel dengan metode latihan berulang.',
            modul: 22, durasi: '9 Minggu', siswa: 164, simbol: 'K',
            price: 'Rp 399K', old: 'Rp 549K',
            iconBg: 'rgba(126, 252, 154, 0.35)', iconColor: '#007433',
        },
        {
            cat: 'SMA', ico: 'BG', name: 'Biologi Sel & Genetika',
            meta: 'Kelas 12 SMA · Peminatan Saintek',
            desc: 'Materi sel, hereditas, dan bioteknologi dengan peta konsep interaktif.',
            modul: 26, durasi: '10 Minggu', siswa: 152, simbol: 'D',
            price: 'Rp 429K', old: 'Rp 579K',
            iconBg: 'rgba(222, 225, 255, 1)', iconColor: '#111c4e',
        },
        {
            cat: 'Bahasa', ico: 'TR', name: 'TOEFL & English Daily',
            meta: 'Semua Jenjang · Tes & Percakapan',
            desc: 'Tingkatkan skor TOEFL dan percakapan harian melalui live class interaktif 2x pekan.',
            modul: 20, durasi: '8 Minggu', siswa: 208, simbol: 'AB',
            price: 'Rp 529K', old: 'Rp 699K',
            iconBg: 'rgba(254, 243, 199, 1)', iconColor: '#92400e',
        },
        {
            cat: 'Ekstra', ico: 'PY', name: 'Coding Python Dasar',
            meta: 'Ekstrakurikuler · Maks. 25 Siswa',
            desc: 'Logika pemrograman dan sains data menggunakan Python untuk pemula total.',
            modul: 18, durasi: '8 Minggu', siswa: 121, simbol: '</>',
            price: 'Rp 399K', old: 'Rp 499K',
            iconBg: 'rgba(237, 233, 254, 1)', iconColor: '#6d28d9',
        },
        {
            cat: 'Ekstra', ico: 'WD', name: 'Web Design & UI/UX',
            meta: 'Ekstrakurikuler · Studio Kreatif',
            desc: 'Bangun portofolio desain web pertamamu dari wireframe sampai prototype interaktif.',
            modul: 16, durasi: '7 Minggu', siswa: 98, simbol: '#',
            price: 'Rp 349K', old: 'Rp 449K',
            iconBg: 'rgba(222, 225, 255, 1)', iconColor: '#111c4e',
        },
    ];

    const grid = document.getElementById('kelasGrid');
    const empty = document.getElementById('kelasEmpty');
    const filters = document.querySelectorAll('.kelas-filter button');

    const cardHTML = (item) => `
        <article class="kelas-card">
            <div class="kelas-card-top">
                <span class="kelas-ico" style="background:${item.iconBg};color:${item.iconColor}">${item.ico}</span>
                <span class="kelas-tag">${item.cat}</span>
            </div>
            <h3>${item.name}</h3>
            <p class="kelas-meta">${item.meta}</p>
            <p class="kelas-desc">${item.desc}</p>
            <div class="kelas-stats">
                <span class="kelas-stat">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.25278V19.25M12 6.25278C10.8321 5.47686 9.24649 5 7.5 5C5.75351 5 4.16789 5.47686 3 6.25278V19.25C4.16789 18.4741 5.75351 18 7.5 18C9.24649 18 10.8321 18.4741 12 19.25M12 6.25278C13.1679 5.47686 14.7535 5 16.5 5C18.2465 5 19.8321 5.47686 21 6.25278V19.25C19.8321 18.4741 18.2465 18 16.5 18C14.7535 18 13.1679 18.4741 12 19.25"/></svg>
                    ${item.modul} Modul
                </span>
                <span class="kelas-stat">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    ${item.durasi}
                </span>
                <span class="kelas-stat">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1a4 4 0 10-4-4 4 4 0 004 4z"/></svg>
                    ${item.siswa}
                </span>
            </div>
            <div class="kelas-hr"></div>
            <div class="kelas-foot">
                <p class="kelas-price"><small>${item.old}</small> ${item.price}/bln</p>
                <a href="${pintarKuyRegisterUrl}" class="kelas-cta">Daftar Kelas</a>
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