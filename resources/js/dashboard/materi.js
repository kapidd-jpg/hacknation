// PintarKuy — Dashboard: Detail Materi (reveal, progress animasi, silabus accordion)
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

    // ---- Silabus accordion ----
    const silabus = document.getElementById('materiSilabus');
    if (silabus) {
        silabus.querySelectorAll('.bab-head').forEach((head) => {
            head.addEventListener('click', () => {
                const item = head.closest('.bab-item');
                const willOpen = !item.classList.contains('is-open');
                silabus.querySelectorAll('.bab-item.is-open').forEach((i) => i.classList.remove('is-open'));
                if (willOpen) item.classList.add('is-open');
                head.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
            });
        });
    }

    // ---- Tombol "Lanjutkan Video Pembelajaran" ----
    const cta = document.getElementById('lanjutkanCta');
    const current = document.querySelector('.modul-row.is-current');
    if (cta && current) {
        cta.addEventListener('click', (e) => {
            e.preventDefault();
            const item = current.closest('.bab-item');
            if (item) item.classList.add('is-open');
            current.scrollIntoView({ behavior: 'smooth', block: 'center' });
            current.classList.add('pulse');
            window.setTimeout(() => current.classList.remove('pulse'), 3300);
        });
    }
});