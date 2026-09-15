// PintarKuy — Dashboard: Latihan Soal (reveal + counter jawaban + konfirmasi kumpul)
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.dash-reveal').forEach((el) => {
        setTimeout(() => el.classList.add('is-visible'), 40);
    });

    const form = document.getElementById('latsolForm');
    if (form) {
        const counter = document.getElementById('latsolAnswered');
        const submitBtn = document.getElementById('latsolSubmit');
        const soalCount = form.querySelectorAll('.latsol-soal').length;

        const count = () => {
            const total = form.querySelectorAll('.latsol-soal').length;
            const answered = Array.from(form.querySelectorAll('.latsol-soal')).filter(function (card) {
                return card.querySelector('input[type="radio"]:checked');
            }).length;
            if (counter) counter.textContent = answered + '/' + total + ' terjawab';
            return { answered, total };
        };

        form.querySelectorAll('input[type="radio"]').forEach((input) => {
            input.addEventListener('change', () => {
                const c = count();
                if (submitBtn) submitBtn.disabled = c.answered === 0;
            });
        });

        form.addEventListener('submit', (e) => {
            const c = count();
            const missing = c.total - c.answered;
            if (!confirm('Kumpulkan jawaban sekarang? Nilai langsung masuk ke rekap.')) {
                e.preventDefault();
                return;
            }
            if (missing > 0) {
                if (!confirm('Masih ada ' + missing + ' soal belum dijawab. Tetap kumpulkan?')) {
                    e.preventDefault();
                }
            }
        });

        count();
    }
});