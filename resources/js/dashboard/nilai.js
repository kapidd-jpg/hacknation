// PintarKuy — Dashboard: Nilai (render tabel per semester + summary)
document.addEventListener('DOMContentLoaded', () => {
    const semesterDefaults = {
        ganjil: { label: 'Semester Ganjil 2026/2027', rows: [] },
        genap: { label: 'Semester Genap 2025/2026', rows: [] },
    };
    const semesters = window.pintarKuyNilai || semesterDefaults;

    const tbody = document.getElementById('nilaiRows');
    const label = document.getElementById('semesterLabel');
    const sumAvg = document.getElementById('sumAvg');
    const sumAvgBadge = document.getElementById('sumAvgBadge');
    const sumMax = document.getElementById('sumMax');
    const sumMaxSubj = document.getElementById('sumMaxSubj');
    const sumPred = document.getElementById('sumPred');

    const gradeClass = (g) => ({ A: 'nilai-badge--A', B: 'nilai-badge--B', C: 'nilai-badge--C' }[g] || 'nilai-badge--B');

    const render = (key) => {
        const sem = semesters[key] || { label: 'Semester', rows: [] };
        const rows = Array.isArray(sem.rows) ? sem.rows : [];
        label.textContent = sem.label;

        tbody.innerHTML = rows.map((r) => `
            <tr>
                <td>
                    <div class="nilai-subj">
                        <span class="nilai-subj-icon" style="background:${r.bg};color:${r.color}">${r.ico}</span>
                        <div><b>${r.subj}</b><small>${r.cat}</small></div>
                    </div>
                </td>
                <td>${r.tugas}</td>
                <td>${r.uts}</td>
                <td>${r.uas}</td>
                <td class="nilai-avg">${r.avg}</td>
                <td><span class="nilai-badge ${gradeClass(r.grade)}">${r.grade}</span></td>
            </tr>`).join('');

        if (rows.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="guru-empty">Belum ada nilai pada semester ini.</td></tr>';
            sumAvg.textContent = '—';
            sumAvgBadge.textContent = '—';
            sumMax.textContent = '—';
            sumMaxSubj.textContent = '—';
            sumPred.textContent = '—';
            return;
        }

        const avgs = rows.map((r) => r.avg);
        const total = avgs.reduce((a, b) => a + b, 0) / avgs.length;
        const best = Math.max(...avgs);
        const bestRow = rows.find((r) => r.avg === best);

        sumAvg.textContent = total.toFixed(1);
        const letter = total >= 88 ? 'A' : total >= 75 ? 'B' : 'C';
        sumPred.textContent = letter;
        sumAvgBadge.textContent = letter === 'A' ? 'Kategori A' : 'Kategori B';
        sumMax.textContent = best.toFixed(1);
        sumMaxSubj.textContent = bestRow?.subj ?? '—';
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