// PintarKuy - Dashboard: Detail Materi (reveal, progress, silabus accordion, modal konten hybrid, tandai selesai)
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

    if (!silabus) return;

    // ---- Estado progres modul ----
    const orderedRows = Array.from(silabus.querySelectorAll('button.modul-row'));
    if (!orderedRows.length) return;

    const svgIcon = {
        done: '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>',
        locked: '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>',
        teks: '<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h10"/></svg>',
        play: '<svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5.14v13.72a1 1 0 001.5.86l11-6.86a1 1 0 000-1.72l-11-6.86A1 1 0 008 5.14z"/></svg>',
    };
    const badgeText = { done: 'Selesai', current: 'Sedang Dipelajari', next: 'Berikutnya', locked: 'Terkunci' };

    const doneMap = {};
    orderedRows.forEach((row) => {
        doneMap[row.dataset.materi] = row.dataset.status === 'done';
    });

    // ---- Hero & CTA ----
    const cta = document.getElementById('lanjutkanCta');
    const heroMain = document.querySelector('.materi-hero-main');
    const heroHint = document.querySelector('.materi-hero-hint');
    const heroLabel = document.querySelector('.materi-hero-label');
    const heroBar = document.querySelector('.materi-hero-bar > span');
    const heroCount = document.querySelector('.materi-hero-count');
    const sideName = document.querySelector('.materi-hero-side-name');
    const sideMeta = document.querySelector('.materi-hero-side-meta');

    let currentRow = orderedRows.find((r) => r.dataset.status === 'current') || null;

    const renderRow = (row, status) => {
        row.classList.remove('is-current', 'is-next', 'is-locked', 'is-clickable');
        if (status !== 'locked') row.classList.add('is-clickable');
        row.disabled = status === 'locked';
        row.dataset.status = status;

        const ico = row.querySelector('.modul-ico');
        if (ico) {
            ico.className = 'modul-ico modul-ico--' + status;
            ico.innerHTML = status === 'done' ? svgIcon.done
                : status === 'locked' ? svgIcon.locked
                : (row.dataset.tipe === 'teks' ? svgIcon.teks : svgIcon.play);
        }

        const badge = row.querySelector('.materi-status');
        if (badge) {
            badge.className = 'materi-status materi-status--' + status;
            badge.textContent = badgeText[status];
        }
    };

    const updateCta = () => {
        const name = currentRow ? currentRow.querySelector('.modul-name').textContent : null;
        if (!currentRow) {
            if (cta) cta.style.display = 'none';
            if (heroHint) heroHint.textContent = 'Semua modul telah ditandai selesai. Kerjakan latihan soalnya!';
            return;
        }
        if (cta) {
            cta.style.display = '';
            if (heroHint) heroHint.textContent = 'Lanjut: ' + name;
        } else if (heroMain && heroHint) {
            const ncta = document.createElement('button');
            ncta.type = 'button';
            ncta.className = 'materi-cta dash-btn dash-btn--primary';
            ncta.id = 'lanjutkanCta';
            ncta.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5.14v13.72a1 1 0 001.5.86l11-6.86a1 1 0 000-1.72l-11-6.86A1 1 0 008 5.14z"/></svg> Lanjutkan Materi';
            heroMain.insertBefore(ncta, heroHint);
            heroHint.textContent = 'Lanjut: ' + name;
        }
    };

    const updateSide = () => {
        const nxt = orderedRows.find((r) => r.dataset.status === 'next') || null;
        if (sideName && sideMeta) {
            if (nxt) {
                sideName.textContent = nxt.querySelector('.modul-name').textContent;
                sideMeta.textContent = nxt.querySelector('.modul-dur').textContent;
            } else {
                sideName.textContent = 'Kerjakan Latihan';
                sideMeta.textContent = 'Lihat paket latihan di bawah';
            }
        }
    };

    const recompute = () => {
        let doneCount = 0;
        let curSeen = false;
        let nxtSeen = false;
        let newCurrent = null;
        orderedRows.forEach((row) => {
            let status;
            if (doneMap[row.dataset.materi]) {
                status = 'done';
                doneCount += 1;
            } else if (!curSeen) {
                status = 'current';
                curSeen = true;
                newCurrent = row;
            } else if (!nxtSeen) {
                status = 'next';
                nxtSeen = true;
            } else {
                status = 'locked';
            }
            renderRow(row, status);
        });

        currentRow = newCurrent;

        const total = orderedRows.length;
        const pct = total ? Math.round((doneCount / total) * 100) : 0;
        if (heroLabel) heroLabel.textContent = pct + '% Selesai';
        if (heroBar) { heroBar.dataset.w = pct; heroBar.style.width = pct + '%'; }
        if (heroCount) heroCount.textContent = doneCount + '/' + total + ' Modul';

        updateCta();
        updateSide();
    };

    // ---- Modal materi hybrid ----
    const modal = document.getElementById('materiModal');
    const modalClose = document.getElementById('materiModalClose');
    const doneBtn = document.getElementById('materiModalDone');

    if (modal && modalClose) {
        const title = document.getElementById('materiModalTitle');
        const kicker = document.getElementById('materiModalKicker');
        const videoBox = document.getElementById('materiModalVideo');
        const durasi = document.getElementById('materiModalDurasi');
        const konten = document.getElementById('materiModalKonten');

        const getYoutubeId = (url) => {
            if (!url) return '';
            const m = url.match(/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/|live\/)|youtu\.be\/)([\w-]{6,})/);
            return m ? m[1] : '';
        };

        const youtubeEmbed = (url) => {
            if (!url) return '';
            const id = getYoutubeId(url);
            return id ? 'https://www.youtube-nocookie.com/embed/' + id : url;
        };

        const esc = (s) => String(s ?? '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');

        let modalMateriId = null;

        const updateDoneBtn = () => {
            if (!doneBtn) return;
            const done = modalMateriId ? !!doneMap[modalMateriId] : false;
            doneBtn.textContent = done ? 'Batalkan Selesai' : 'Tandai Selesai ✓';
            doneBtn.classList.toggle('is-done', done);
        };

        const open = (row) => {
            const tipe = row.dataset.tipe || 'video';
            const video = row.dataset.video || '';
            const teks = row.dataset.konten || '';

            modalMateriId = row.dataset.materi || null;

            title.textContent = row.dataset.nama || 'Materi';
            kicker.textContent = tipe === 'teks' ? 'Ringkasan Teks' : tipe === 'video_teks' ? 'Video + Ringkasan' : 'Video Pembelajaran';
            durasi.textContent = 'Durasi: ' + (row.dataset.durasi || '-');

            if (tipe !== 'teks' && video) {
                const id = getYoutubeId(video);
                const embedSrc = youtubeEmbed(video);
                const poster = id ? 'https://img.youtube.com/vi/' + id + '/hqdefault.jpg' : '';
                videoBox.innerHTML = [
                    '<iframe src="' + esc(embedSrc) + '" title="Video" loading="lazy" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
                    '<div class="materi-video-fallback">',
                    '<a class="materi-video-thumb" href="#" role="button" aria-label="Putar video"',
                    poster ? ' style="background-image:url(\'' + esc(poster) + '\')"' : '',
                    '><span class="materi-video-play"><svg width="30" height="30" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5.14v13.72a1 1 0 001.5.86l11-6.86a1 1 0 000-1.72l-11-6.86A1 1 0 008 5.14z"/></svg></span></a>',
                    '<a class="materi-video-open" href="' + esc(video) + '" target="_blank" rel="noopener">Putar di YouTube ↗</a>',
                    '</div>'
                ].join('');
                const fb = videoBox.querySelector('.materi-video-fallback');
                const thumb = videoBox.querySelector('.materi-video-thumb');
                if (fb && thumb) thumb.addEventListener('click', (e) => { e.preventDefault(); fb.classList.add('is-hidden'); });
            } else if (tipe !== 'teks') {
                videoBox.innerHTML = '<div class="materi-modal-video-empty"><span>Video sedang disiapkan tutor.</span></div>';
            } else {
                videoBox.innerHTML = '<div class="materi-modal-video-empty"><span>Ringkasan teks di bawah.</span></div>';
            }

            konten.innerHTML = teks ? esc(teks) : '<em style="color:var(--ink-muted);">Belum ada ringkasan untuk modul ini.</em>';

            updateDoneBtn();

            modal.classList.add('is-open');
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        };

        const close = () => {
            modal.classList.remove('is-open');
            modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
            videoBox.innerHTML = '';
            modalMateriId = null;
        };

        const bindCta = () => {
            const btn = document.getElementById('lanjutkanCta');
            if (btn) btn.addEventListener('click', (e) => {
                e.preventDefault();
                if (!currentRow) return;
                const item = currentRow.closest('.bab-item');
                if (item && !item.classList.contains('is-open')) item.classList.add('is-open');
                currentRow.classList.add('pulse');
                window.setTimeout(() => currentRow.classList.remove('pulse'), 3300);
                open(currentRow);
            });
        };

        silabus.querySelectorAll('.modul-row.is-clickable').forEach((row) => {
            row.addEventListener('click', () => open(row));
        });

        if (doneBtn) {
            doneBtn.addEventListener('click', () => {
                if (!modalMateriId) return;
                doneBtn.disabled = true;
                const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                fetch('/dashboard/progres-modul', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                    },
                    body: JSON.stringify({ materi_id: modalMateriId }),
                })
                    .then((r) => r.json().catch(() => ({})))
                    .then((res) => {
                        if (res && res.ok === true) {
                            doneMap[modalMateriId] = !!res.completed;
                            recompute();
                            updateDoneBtn();
                        } else {
                            alert((res && res.message) || 'Gagal memperbarui progres.');
                        }
                    })
                    .catch(() => alert('Gagal terhubung ke server.'))
                    .finally(() => { doneBtn.disabled = false; });
            });
        }

        modalClose.addEventListener('click', close);
        modal.addEventListener('click', (e) => {
            if (e.target === modal) close();
        });
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && modal.classList.contains('is-open')) close();
        });

        bindCta();
    }
});