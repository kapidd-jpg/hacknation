// PintarKuy — Dashboard: Detail Materi (reveal, progress animasi, silabus accordion, modal konten hybrid)
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

    // ---- Modal materi hybrid ----
    const modal = document.getElementById('materiModal');
    const modalClose = document.getElementById('materiModalClose');
    if (modal && modalClose) {
        const title = document.getElementById('materiModalTitle');
        const kicker = document.getElementById('materiModalKicker');
        const videoBox = document.getElementById('materiModalVideo');
        const durasi = document.getElementById('materiModalDurasi');
        const konten = document.getElementById('materiModalKonten');

        const youtubeEmbed = (url) => {
            if (!url) return '';
            const m = url.match(/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([\w-]{6,})/);
            return m ? 'https://www.youtube.com/embed/' + m[1] : url;
        };

        const esc = (s) => String(s ?? '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');

        const open = (row) => {
            const tipe = row.dataset.tipe || 'video';
            const video = row.dataset.video || '';
            const teks = row.dataset.konten || '';

            title.textContent = row.dataset.nama || 'Materi';
            kicker.textContent = tipe === 'teks' ? 'Ringkasan Teks' : tipe === 'video_teks' ? 'Video + Ringkasan' : 'Video Pembelajaran';
            durasi.textContent = 'Durasi: ' + (row.dataset.durasi || '—');

            if (tipe !== 'teks' && video) {
                videoBox.innerHTML = '<iframe src="' + esc(youtubeEmbed(video)) + '" title="Video" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
            } else if (tipe !== 'teks') {
                videoBox.innerHTML = '<div class="materi-modal-video-empty"><span style="font-size:34px;">🎬</span><span>Video sedang disiapkan tutor.</span></div>';
            } else {
                videoBox.innerHTML = '<div class="materi-modal-video-empty"><span style="font-size:34px;">📄</span><span>Ringkasan teks di bawah.</span></div>';
            }

            konten.innerHTML = teks ? esc(teks) : '<em style="color:var(--ink-muted);">Belum ada ringkasan untuk modul ini.</em>';

            modal.classList.add('is-open');
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        };

        const close = () => {
            modal.classList.remove('is-open');
            modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
            videoBox.innerHTML = '';
        };

        silabus.querySelectorAll('.modul-row.is-clickable').forEach((row) => {
            row.addEventListener('click', () => open(row));
        });

        modalClose.addEventListener('click', close);
        modal.addEventListener('click', (e) => {
            if (e.target === modal) close();
        });
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && modal.classList.contains('is-open')) close();
        });

        // ---- Tombol "Lanjutkan Materi" ----
        const cta = document.getElementById('lanjutkanCta');
        const current = document.querySelector('.modul-row.is-current');
        if (cta && current) {
            cta.addEventListener('click', (e) => {
                e.preventDefault();
                const item = current.closest('.bab-item');
                if (item && !item.classList.contains('is-open')) item.classList.add('is-open');
                current.classList.add('pulse');
                window.setTimeout(() => current.classList.remove('pulse'), 3300);
                open(current);
            });
        }
    }
});