// PintarKuy - Dashboard: Nilai (render rekap per semester dari server)
document.addEventListener('DOMContentLoaded', () => {
    const semesters = window.pintarKuyNilai || { ganjil: { rows: [] }, genap: { rows: [] } };

    const tbody = document.getElementById('nilaiRows');
    const label = document.getElementById('semesterLabel');
    const sumAvg = document.getElementById('sumAvg');
    const sumAvgBadge = document.getElementById('sumAvgBadge');
    const sumMax = document.getElementById('sumMax');
    const sumMaxSubj = document.getElementById('sumMaxSubj');
    const sumLatihan = document.getElementById('sumLatihan');
    const sumLatihanNote = document.getElementById('sumLatihanNote');
    const sumPred = document.getElementById('sumPred');

    const gradeClass = (g) => ({ A: 'nilai-badge--A', B: 'nilai-badge--B', C: 'nilai-badge--C' }[g] || 'nilai-badge--B');

    const esc = (s) => String(s ?? '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');

    const emptyNote = (msg) => {
        if (tbody) tbody.innerHTML = `<tr><td colspan="7" class="guru-empty" style="padding:28px;text-align:center;">${msg}</td></tr>`;
        if (sumAvg) sumAvg.textContent = '0';
        if (sumAvgBadge) sumAvgBadge.textContent = 'Belum ada';
        if (sumMax) sumMax.textContent = '-';
        if (sumMaxSubj) sumMaxSubj.textContent = '-';
        if (sumLatihan) sumLatihan.textContent = '0';
        if (sumLatihanNote) sumLatihanNote.textContent = 'Paket';
        if (sumPred) sumPred.textContent = '-';
    };

    const render = (key) => {
        const sem = semesters[key] || { label: '-', rows: [] };
        if (label) label.textContent = sem.label || '-';

        const rows = Array.isArray(sem.rows) ? sem.rows : [];
        if (rows.length === 0) {
            emptyNote('Belum ada latihan soal pada semester ini.');
            return;
        }

        if (tbody) tbody.innerHTML = rows.map((r) => `
            <tr>
                <td>
                    <div class="nilai-subj">
                        <span class="nilai-subj-icon" style="background:${esc(r.bg)};color:${esc(r.color)}">${esc(r.ico)}</span>
                        <div><b>${esc(r.subj)}</b><small>${esc(r.cat)}</small></div>
                    </div>
                </td>
                <td>${esc(r.latihan)}</td>
                <td>${esc(r.soal)}</td>
                <td>${esc(r.akurasi)}%</td>
                <td class="nilai-avg">${esc(r.avg)}</td>
                <td class="nilai-avg">${r.best !== null && r.best !== undefined ? esc(r.best) : '-'}</td>
                <td><span class="nilai-badge ${gradeClass(r.grade)}">${esc(r.grade)}</span></td>
            </tr>`).join('');

        if (sumAvg) sumAvg.textContent = sem.avg !== null && sem.avg !== undefined ? sem.avg : '0';
        if (sumAvgBadge) sumAvgBadge.textContent = sem.grade === '-' || sem.grade === null || sem.grade === undefined ? 'Belum ada' : 'Kategori ' + sem.grade;
        if (sumMax) sumMax.textContent = sem.best !== null && sem.best !== undefined ? sem.best : '-';
        if (sumMaxSubj) sumMaxSubj.textContent = sem.bestSubj || '-';
        if (sumLatihan) sumLatihan.textContent = sem.latihan || 0;
        if (sumLatihanNote) sumLatihanNote.textContent = 'Paket';
        if (sumPred) sumPred.textContent = sem.grade || '-';
    };

    document.querySelectorAll('#semesterFilter button').forEach((btn) => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('#semesterFilter button').forEach((b) => b.classList.remove('active'));
            btn.classList.add('active');
            render(btn.dataset.sem);
        });
    });

    render('ganjil');

    // ---- Detail per set: nilai terkini (attempt terbaru) + tren histori ----
    const sets = Array.isArray(window.pintarKuySets) ? window.pintarKuySets : [];
    const setsBox = document.getElementById('nilaiSets');

    const sparkline = (histori) => {
        if (!Array.isArray(histori) || !histori.length) return '';
        const W = 220, H = 40, pad = 4, top = 4;
        const n = histori.length;
        const x = (i) => (n === 1 ? W / 2 : pad + (i / (n - 1)) * (W - pad * 2));
        const y = (skor) => H - top - (skor / 100) * (H - top * 2 - 4);
        const pts = histori.map((h, i) => x(i) + ',' + y(h.skor));
        const last = pts[pts.length - 1];
        const dots = histori.map((h, i) => {
            const cx = x(i), cy = y(h.skor);
            const rx = i === n - 1 ? 3.4 : 2.4;
            return `<circle cx="${cx.toFixed(1)}" cy="${cy.toFixed(1)}" r="${rx}" fill="${i === n - 1 ? '#0F766E' : '#10B981'}" stroke="#fff" stroke-width="1"/>`;
        }).join('');
        return `<svg viewBox="0 0 ${W} ${H}" preserveAspectRatio="none" style="width:100%;height:46px;display:block;">
            <polyline points="${pts.join(' ')}" fill="none" stroke="#10B981" stroke-width="2" stroke-linejoin="round" stroke-linecap="round" stroke-dasharray="${last ? '0' : ''}"/>
            ${dots}</svg>`;
    };

    if (setsBox) {
        if (!sets.length) {
            setsBox.innerHTML = '<div class="guru-empty" style="padding:24px;text-align:center;">Belum ada set latihan yang dikerjakan. Kerjakan di menu <a href="/dashboard/latsol" style="text-decoration:underline;font-weight:700;color:var(--green-text);">Latihan Soal</a>.</div>';
        } else {
            setsBox.innerHTML = sets.map((s) => {
                const t = s.terkini;
                const hist = Array.isArray(s.histori) ? s.histori : [];
                const head = t ? `
                    <span class="dash-pill dash-pill--green">Benar ${esc(t.benar)}</span>
                    <span class="dash-pill" style="background:rgba(255,120,120,.15);color:#ff7a7a;">Salah ${esc(t.salah)}</span>
                    <span class="dash-pill" style="background:rgba(154,169,196,.15);color:#64748b;">Kosong ${esc(t.kosong)}</span>
                    <span class="dash-pill">Akurasi ${esc(t.akurasi)}%</span>` : '';
                return `
                    <div class="nilai-set">
                        <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;justify-content:space-between;">
                            <div style="display:flex;align-items:center;gap:10px;min-width:0;">
                                <span class="nilai-subj-icon" style="background:${esc(s.bg)};color:${esc(s.color)}">${esc(s.ico)}</span>
                                <div style="min-width:0;">
                                    <b style="color:var(--navy-900);display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${esc(s.label)}</b>
                                    <small style="color:var(--ink-soft);">${esc(s.subj)} • ${esc(s.cat)} • ${esc(s.jumlah_attempt)} attempt</small>
                                </div>
                            </div>
                            <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;font-size:12.5px;">
                                ${t ? `<b style="color:var(--green-text);font-size:20px;letter-spacing:-.02em;">${esc(t.skor)}</b><span style="color:var(--ink-soft);">/100 • ${esc(t.waktu)}</span>` : '<span class="dash-pill">Belum ada attempt</span>'}
                                ${t ? head : ''}
                            </div>
                        </div>
                        ${hist.length > 1 ? sparkline(hist) : ''}
                    </div>`;
            }).join('');
        }
    }

    document.querySelectorAll('.dash-reveal').forEach((el) => el.classList.add('is-visible'));
});