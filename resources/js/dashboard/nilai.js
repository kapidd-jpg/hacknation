// PintarKuy — Dashboard: Nilai (render rekap per semester dari server)
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

    const esc = (s) => String(s ?? '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');

    const emptyNote = (msg) => {
        if (tbody) tbody.innerHTML = `<tr><td colspan="7" class="guru-empty" style="padding:28px;text-align:center;">${msg}</td></tr>`;
        if (sumAvg) sumAvg.textContent = '0';
        if (sumAvgBadge) sumAvgBadge.textContent = 'Belum ada';
        if (sumMax) sumMax.textContent = '—';
        if (sumMaxSubj) sumMaxSubj.textContent = '—';
        if (sumLatihan) sumLatihan.textContent = '0';
        if (sumLatihanNote) sumLatihanNote.textContent = 'Paket';
        if (sumPred) sumPred.textContent = '—';
    };

    const render = (key) => {
        const sem = semesters[key] || { label: '—', rows: [] };
        if (label) label.textContent = sem.label || '—';

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
                <td class="nilai-avg">${r.best !== null && r.best !== undefined ? esc(r.best) : '—'}</td>
                <td><span class="nilai-badge ${gradeClass(r.grade)}">${esc(r.grade)}</span></td>
            </tr>`).join('');

        if (sumAvg) sumAvg.textContent = sem.avg !== null && sem.avg !== undefined ? sem.avg : '0';
        if (sumAvgBadge) sumAvgBadge.textContent = sem.grade === '-' || sem.grade === null || sem.grade === undefined ? 'Belum ada' : 'Kategori ' + sem.grade;
        if (sumMax) sumMax.textContent = sem.best !== null && sem.best !== undefined ? sem.best : '—';
        if (sumMaxSubj) sumMaxSubj.textContent = sem.bestSubj || '—';
        if (sumLatihan) sumLatihan.textContent = sem.latihan || 0;
        if (sumLatihanNote) sumLatihanNote.textContent = 'Paket';
        if (sumPred) sumPred.textContent = sem.grade || '—';
    };

    document.querySelectorAll('#semesterFilter button').forEach((btn) => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('#semesterFilter button').forEach((b) => b.classList.remove('active'));
            btn.classList.add('active');
            render(btn.dataset.sem);
        });
    });

    render('ganjil');

    document.querySelectorAll('.dash-reveal').forEach((el) => el.classList.add('is-visible'));
});