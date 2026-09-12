// PintarKuy — Dashboard: Nilai (render tabel per semester + summary)
document.addEventListener('DOMContentLoaded', () => {
    const semesters = {
        ganjil: {
            label: 'Semester Ganjil 2024/2025',
            rows: [
                { subj: 'Matematika', cat: 'Wajib', ico: 'Mt', bg: 'rgba(126,252,154,0.35)', color: '#007433', tugas: 88, uts: 84, uas: 86, avg: 86.0, grade: 'B' },
                { subj: 'Fisika', cat: 'Saintek', ico: 'Fi', bg: 'rgba(237,233,254,1)', color: '#6d28d9', tugas: 90, uts: 81, uas: 84, avg: 85.0, grade: 'B' },
                { subj: 'Bahasa Inggris', cat: 'Literasi', ico: 'En', bg: 'rgba(222,225,255,1)', color: '#111c4e', tugas: 92, uts: 88, uas: 90, avg: 90.0, grade: 'A' },
                { subj: 'Kimia', cat: 'Saintek', ico: 'Ki', bg: 'rgba(126,252,154,0.35)', color: '#007433', tugas: 84, uts: 79, uas: 83, avg: 82.0, grade: 'B' },
                { subj: 'TPS Penalaran', cat: 'UTBK', ico: 'TP', bg: 'rgba(237,233,254,1)', color: '#6d28d9', tugas: 86, uts: 83, uas: 85, avg: 84.7, grade: 'B' },
            ],
        },
        genap: {
            label: 'Semester Genap 2024/2025',
            rows: [
                { subj: 'Matematika', cat: 'Wajib', ico: 'Mt', bg: 'rgba(126,252,154,0.35)', color: '#007433', tugas: 95, uts: 90, uas: 92, avg: 92.3, grade: 'A' },
                { subj: 'Fisika', cat: 'Saintek', ico: 'Fi', bg: 'rgba(237,233,254,1)', color: '#6d28d9', tugas: 91, uts: 86, uas: 88, avg: 88.3, grade: 'A' },
                { subj: 'Bahasa Inggris', cat: 'Literasi', ico: 'En', bg: 'rgba(222,225,255,1)', color: '#111c4e', tugas: 93, uts: 91, uas: 94, avg: 92.7, grade: 'A' },
                { subj: 'Kimia', cat: 'Saintek', ico: 'Ki', bg: 'rgba(126,252,154,0.35)', color: '#007433', tugas: 87, uts: 82, uas: 85, avg: 84.7, grade: 'B' },
                { subj: 'TPS Penalaran', cat: 'UTBK', ico: 'TP', bg: 'rgba(237,233,254,1)', color: '#6d28d9', tugas: 90, uts: 87, uas: 91, avg: 89.3, grade: 'A' },
                { subj: 'Pemrograman', cat: 'Ekstra', ico: 'Py', bg: 'rgba(254,243,199,1)', color: '#92400e', tugas: 97, uts: 93, uas: 95, avg: 95.0, grade: 'A' },
            ],
        },
    };

    const tbody = document.getElementById('nilaiRows');
    const label = document.getElementById('semesterLabel');
    document.getElementById('sumAvg');
    const sumAvg = document.getElementById('sumAvg');
    const sumAvgBadge = document.getElementById('sumAvgBadge');
    const sumMax = document.getElementById('sumMax');
    const sumMaxSubj = document.getElementById('sumMaxSubj');
    const sumPred = document.getElementById('sumPred');

    const gradeClass = (g) => ({ A: 'nilai-badge--A', B: 'nilai-badge--B', C: 'nilai-badge--C' }[g] || 'nilai-badge--B');

    const render = (key) => {
        const sem = semesters[key];
        label.textContent = sem.label;

        tbody.innerHTML = sem.rows.map((r) => `
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

        const avgs = sem.rows.map((r) => r.avg);
        const total = avgs.reduce((a, b) => a + b, 0) / avgs.length;
        const best = Math.max(...avgs);
        const bestRow = sem.rows.find((r) => r.avg === best);

        sumAvg.textContent = total.toFixed(1);
        const letter = total >= 88 ? 'A' : total >= 75 ? 'B' : 'C';
        sumPred.textContent = letter;
        sumAvgBadge.textContent = letter === 'A' ? 'Kategori A' : 'Kategori B';
        sumMax.textContent = best.toFixed(1);
        sumMaxSubj.textContent = bestRow.subj;
    };

    document.querySelectorAll('#semesterFilter button').forEach((btn) => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('#semesterFilter button').forEach((b) => b.classList.remove('active'));
            btn.classList.add('active');
            render(btn.dataset.sem);
        });
    });

    render('genap');

    document.querySelectorAll('.dash-reveal').forEach((el) => el.classList.add('is-visible'));
});