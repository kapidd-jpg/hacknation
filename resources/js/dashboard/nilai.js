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
        tbody.innerHTML = `<tr><td colspan="7" class="guru-empty" style="padding:28px;text-align:center;">${msg}</td></tr>`;
        sumAvg.textContent = '0';
        sumAvgBadge.textContent = 'Belum ada';
        sumMax.textContent = '—';
        sumMaxSubj.textContent = '—';
        sumLatihan.textContent = '0';
        sumLatihanNote.textContent = 'Paket';
        sumPred.textContent = '—';
    };

    const render = (key) => {
        const sem = semesters[key] || { label: '—', rows: [] };
        label.textContent = sem.label || '—';

        const rows = Array.isArray(sem.rows) ? sem.rows : [];
        if (rows.length === 0) {
            emptyNote('Belum ada latihan soal pada semester ini.');
            return;
        }

        tbody.innerHTML = rows.map((r) => `
            <tr>
                <td>
                    <div class="nilai-subj">
                        <span class="nilai-subj-icon" style="background:${r.bg};color:${r.color}">${esc(r.ico)}</span>
                        <div><b>${esc(r.subj)}</b><small>${esc(r.cat)}</small></div>
                    </div>
                </td>
                <td>${r.latihan}</td>
                <td>${r.soal}</td>
                <td>${r.akurasi}%</td>
                <td class="nilai-avg">${r.avg}</td>
                <td class="nilai-avg">${r.best !== null && r.best !== undefined ? r.best : '—'}</td>
                <td><span class="nilai-badge ${gradeClass(r.grade)}">${r.grade}</span></td>
            </tr>`).join('');

        sumAvg.textContent = sem.avg !== null && sem.avg !== undefined ? sem.avg : '0';
        sumAvgBadge.textContent = sem.grade === '-' || sem.grade === null || sem.grade === undefined ? 'Belum ada' : 'Kategori ' + sem.grade;
        sumMax.textContent = sem.best !== null && sem.best !== undefined ? sem.best : '—';
        sumMaxSubj.textContent = sem.bestSubj || '—';
        sumLatihan.textContent = sem.latihan || 0;
        sumLatihanNote.textContent = 'Paket';
        sumPred.textContent = sem.grade || '—';
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