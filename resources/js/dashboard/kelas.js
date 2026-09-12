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
});