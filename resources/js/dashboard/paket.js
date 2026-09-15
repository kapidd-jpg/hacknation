document.addEventListener('DOMContentLoaded', () => {
    if ('IntersectionObserver' in window) {
        const io = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    io.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        document.querySelectorAll('.dash-reveal').forEach((el) => io.observe(el));
    } else {
        document.querySelectorAll('.dash-reveal').forEach((el) => el.classList.add('is-visible'));
    }
});