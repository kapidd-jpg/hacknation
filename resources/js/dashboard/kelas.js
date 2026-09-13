// PintarKuy — Dashboard: Kelas Saya (reveal + progress bar animasi)
document.addEventListener('DOMContentLoaded', () => {
    const revealEls = document.querySelectorAll('.dash-reveal');

    const show = (el) => {
        el.classList.add('is-visible');
        el.querySelectorAll('.dash-progress > span').forEach((bar) => {
            window.setTimeout(() => { bar.style.width = bar.dataset.w + '%'; }, 120);
        });
    };

    if ('IntersectionObserver' in window) {
        const io = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    show(entry.target);
                    io.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12 });
        revealEls.forEach((el) => io.observe(el));
    } else {
        revealEls.forEach(show);
    }

    // ---- Toast kelas baru terdaftar (dari Katalog via localStorage) ----
    const baruDaftar = JSON.parse(localStorage.getItem('pintarKuyBaru') || 'null');
    if (baruDaftar && baruDaftar.name) {
        localStorage.removeItem('pintarKuyBaru');
        const toast = document.createElement('div');
        toast.className = 'kelas-toast';
        toast.innerHTML = `
            <span class="kelas-toast-ico" style="background:${baruDaftar.bg};color:${baruDaftar.color}">${baruDaftar.ico}</span>
            <div class="kelas-toast-body">
                <p class="kelas-toast-title">✓ ${baruDaftar.name} berhasil didaftarkan</p>
                <p class="kelas-toast-sub">Kelas barumu sudah masuk daftar. Mulai dari halaman materi.</p>
            </div>
            <a href="${window.pintarKuyMateriUrl}?kelas=${baruDaftar.slug || 'matematika'}" class="kelas-toast-link">Buka Materi</a>`;
        document.body.appendChild(toast);
        window.setTimeout(() => toast.classList.add('is-visible'), 30);
        window.setTimeout(() => {
            toast.classList.remove('is-visible');
            window.setTimeout(() => toast.remove(), 600);
        }, 5200);
    }

    // ---- Tambahkan kelas yang baru didaftarkan dari Katalog (localStorage) ----
    const grid = document.getElementById('kelasGrid');
    if (grid) {
        const existingNames = new Set([...grid.querySelectorAll('.kelas-item h3')].map((h) => h.textContent.trim()));
        const enrolled = JSON.parse(localStorage.getItem('pintarKuyTerdaftar') || '[]');
        [...enrolled].reverse().forEach((item) => {
            if (!item || !item.name || existingNames.has(item.name)) return;
            const card = document.createElement('div');
            card.className = 'kelas-item';
            card.innerHTML = `
                <div class="kelas-item-top">
                    <span class="kelas-icon" style="background:${item.bg};color:${item.color}">${item.ico}</span>
                    <div class="flex-1 min-w-0">
                        <h3>${item.name}</h3>
                        <p class="kelas-meta">${item.meta}</p>
                        <div class="kelas-tags">
                            <span class="dash-pill">${item.cat}</span>
                            <span class="dash-pill dash-pill--green">Baru Terdaftar</span>
                        </div>
                    </div>
                </div>
                <p class="kelas-desc">${item.modul} Modul • Tutor Master PTN • Rekaman kelas tersedia 24/7.</p>
                <div>
                    <div class="kelas-progress-head">
                        <span>0% Selesai</span>
                        <span>0/${item.modul} Modul</span>
                    </div>
                    <div class="dash-progress"><span style="width:0%" data-w="0"></span></div>
                </div>
                <div class="kelas-note">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Kelas baru terdaftar — mulai dari modul pertama.
                </div>
                <div class="kelas-item-foot">
                    <a href="${window.pintarKuyMateriUrl}?kelas=${item.slug}" class="dash-btn dash-btn--primary">Lanjutkan Belajar</a>
                    <a href="${window.pintarKuyMateriUrl}?kelas=${item.slug}" class="dash-btn dash-btn--ghost">Lihat Materi</a>
                </div>`;
            grid.prepend(card);
        });
    }
});