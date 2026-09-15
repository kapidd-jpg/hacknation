// PintarKuy — Halaman Landing (scroll halus + reveal + quiz teaser 5 soal × 20 poin)
document.addEventListener('DOMContentLoaded', () => {

    // ---------- selalu kembali ke atas saat halaman di-refresh ----------
    if ('scrollRestoration' in window.history) {
        window.history.scrollRestoration = 'manual';
    }
    window.scrollTo(0, 0);

    // ---------- smooth scroll manual (anti bentrok dengan prefers-reduced-motion) ----------
    const rootEl = document.documentElement;
    const easeInOutCubic = (t) => (t < 0.5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2);

    function smoothScrollTo(targetY, duration = 700) {
        const startY = window.pageYOffset;
        const delta = targetY - startY;
        if (Math.abs(delta) < 2) return;

        const prevBehavior = rootEl.style.scrollBehavior;
        rootEl.style.scrollBehavior = 'auto';
        const start = performance.now();

        const step = (now) => {
            const progress = Math.min((now - start) / duration, 1);
            window.scrollTo(0, startY + delta * easeInOutCubic(progress));
            if (progress < 1) {
                requestAnimationFrame(step);
            } else {
                rootEl.style.scrollBehavior = prevBehavior;
            }
        };
        requestAnimationFrame(step);
    }

    // ---------- pilihan paket dari landing ----------
    // Tamu → diarahkan daftar/login. Sudah login → langsung ke halaman checkout paket tsb.
    const ctas = document.querySelectorAll('[data-paket]');
    if (ctas.length) {
        const TIER = ['utbk', 'sma-ekstra', 'bahasa'];
        const loggedIn = !!(window.pintarKuyAuth && window.pintarKuyAuth.isLoggedIn());
        if (window.pintarKuyPaketUrl) {
            ctas.forEach((btn) => btn.addEventListener('click', (e) => {
                const paket = btn.dataset.paket;
                if (!TIER.includes(paket)) return;
                if (!loggedIn) return;
                e.preventDefault();
                window.location.href = window.pintarKuyPaketUrl + '/' + paket;
            }));
        }
    }

    // ---------- smooth scroll untuk semua link anchor ----------
    document.querySelectorAll('a[href^="#"]').forEach((a) => {
        a.addEventListener('click', (e) => {
            const href = a.getAttribute('href');
            if (!href || href === '#') return;
            const target = document.querySelector(href);
            if (!target) return;
            e.preventDefault();
            const header = document.querySelector('header');
            const offset = header ? header.offsetHeight : 0;
            const top = target.getBoundingClientRect().top + window.scrollY - offset;
            smoothScrollTo(top);
        });
    });

    // ---------- scroll reveal ----------
    const revealEls = document.querySelectorAll('.reveal');
    if ('IntersectionObserver' in window) {
        const io = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    io.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        revealEls.forEach((el) => io.observe(el));
    } else {
        revealEls.forEach((el) => el.classList.add('is-visible'));
    }

    // ---------- quiz teaser ----------
    const questions = Array.from(document.querySelectorAll('.quiz-question'));
    const checkBtn = document.getElementById('quizCheck');
    if (!questions.length || !checkBtn) return;

    const nextBtn = document.getElementById('quizNext');
    const feedback = document.getElementById('quizFeedback');
    const result = document.getElementById('quizResult');
    const scoreEl = document.getElementById('quizScore');
    const scoreMsg = document.getElementById('quizScoreMessage');

    let index = 0;
    let score = 0;

    const setFeedback = (message, ok) => {
        if (!feedback) return;
        feedback.textContent = message;
        feedback.classList.remove('hidden');
        feedback.classList.toggle('text-brand-green', ok);
        feedback.classList.toggle('text-red-600', !ok);
    };

    const raceMessage = (pts) => {
        if (pts === 100) return 'Sempurna! Kamu menjawab semua soal dengan benar. Siap lanjut ke uji penuh?';
        if (pts >= 60) return 'Keren! Kemampuan penalaranmu sudah solid. Tingkatkan lagi dengan tryout penuh interaktif.';
        return 'Sedikit lagi! Kokohkan dasar penalaranmu lewat simulasi IRT lengkap bersama tutor master.';
    };

    checkBtn.addEventListener('click', () => {
        const current = questions[index];
        const correct = current.dataset.correct;
        const chosen = current.querySelector('input[type="radio"]:checked');

        if (!chosen) {
            setFeedback('Pilih salah satu jawaban dulu, lalu periksa kembali.', false);
            return;
        }

        const chosenLabel = chosen.closest('.quiz-option');
        current.querySelectorAll('input[type="radio"]').forEach((o) => { o.disabled = true; });

        if (chosen.value === correct) {
            chosenLabel.classList.add('border-brand-green', 'bg-brand-greenlight/30');
            score += 20;
            setFeedback('Benar! +20 poin untuk jawaban ini.', true);
        } else {
            chosenLabel.classList.add('border-red-400', 'bg-red-50');
            setFeedback('Belum tepat. Jawaban yang benar adalah ' + correct + '.', false);
        }

        const correctLabel = current.querySelector('input[value="' + correct + '"]')?.closest('.quiz-option');
        if (correctLabel && correctLabel !== chosenLabel) {
            correctLabel.classList.add('border-brand-green', 'bg-brand-greenlight/30');
        }

        checkBtn.classList.add('hidden');
        if (index < questions.length - 1) {
            nextBtn.classList.remove('hidden');
        } else {
            result.classList.remove('hidden');
            scoreEl.textContent = score;
            scoreMsg.textContent = raceMessage(score);
        }
    });

    nextBtn.addEventListener('click', () => {
        index += 1;
        questions[index - 1].classList.add('hidden');
        questions[index].classList.remove('hidden');

        feedback.textContent = '';
        feedback.classList.add('hidden');

        nextBtn.classList.add('hidden');
        checkBtn.classList.remove('hidden');
    });
});