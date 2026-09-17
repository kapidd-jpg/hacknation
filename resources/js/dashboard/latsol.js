// PintarKuy - Dashboard: Latihan Soal (reveal + counter jawaban + konfirmasi kumpul)
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.dash-reveal').forEach((el) => {
        setTimeout(() => el.classList.add('is-visible'), 40);
    });

    const form = document.getElementById('latsolForm');
    if (form) {
        const counter = document.getElementById('latsolAnswered');
        const submitBtn = document.getElementById('latsolSubmit');

// Catat waktu mulai pengerjaan (dipakai backend untuk waktu_mulai attempt).
        const waktuMulai = document.getElementById('latsolWaktuMulai');
        if (waktuMulai) waktuMulai.value = new Date().toISOString();

        const overlay = document.getElementById('latsolSubmitOverlay');
        const title = document.getElementById('latsolConfirmTitle');
        const desc = document.getElementById('latsolConfirmDesc');
        const ico = document.getElementById('latsolConfirmIco');
        const okBtn = overlay ? overlay.querySelector('[data-latsol-confirm-ok]') : null;
        const cancelBtn = overlay ? overlay.querySelector('[data-latsol-confirm-cancel]') : null;

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

        const closeConfirm = () => {
            if (overlay) overlay.classList.remove('is-open');
        };

        const askConfirm = (t, d, warn) => {
            return new Promise((resolve) => {
                if (!overlay || !okBtn || !cancelBtn) {
                    resolve(true);
                    return;
                }
                title.textContent = t;
                desc.textContent = d;
                if (ico) ico.classList.toggle('dash-confirm-icon--warn', !!warn);
                overlay.classList.add('is-open');

                const onEsc = (e) => {
                    if (e.key === 'Escape') {
                        document.removeEventListener('keydown', onEsc);
                        closeConfirm();
                        resolve(false);
                    }
                };
                document.addEventListener('keydown', onEsc);
                overlay.onclick = (e) => {
                    if (e.target === overlay) {
                        document.removeEventListener('keydown', onEsc);
                        closeConfirm();
                        resolve(false);
                    }
                };
                okBtn.onclick = () => {
                    document.removeEventListener('keydown', onEsc);
                    closeConfirm();
                    resolve(true);
                };
                cancelBtn.onclick = () => {
                    document.removeEventListener('keydown', onEsc);
                    closeConfirm();
                    resolve(false);
                };
            });
        };

        let submitting = false;

        const doSubmit = () => {
            if (submitting) return;
            submitting = true;
            form.submit();
        };

        form.addEventListener('submit', (e) => {
            e.preventDefault();
            if (submitting) return;

            const c = count();
            const missing = c.total - c.answered;

            askConfirm('Kumpulkan jawaban sekarang?', 'Nilai langsung masuk ke rekap.', false).then((ok) => {
                if (!ok) return;
                if (missing > 0) {
                    askConfirm('Masih ada ' + missing + ' soal belum dijawab', 'Tetap kumpulkan jawaban sekarang?', true).then((ok2) => {
                        if (ok2) doSubmit();
                    });
                    return;
                }
                doSubmit();
            });
        });

        count();
    }
});