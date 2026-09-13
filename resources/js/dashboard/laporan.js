// PintarKuy — Dashboard: Laporan (bar chart + rentang waktu + rekomendasi)
document.addEventListener('DOMContentLoaded', () => {
    const months = [
        { label: 'Mar', val: 638 },
        { label: 'Apr', val: 652 },
        { label: 'Mei', val: 668 },
        { label: 'Jun', val: 685 },
        { label: 'Jul', val: 698 },
        { label: 'Agu', val: 712 },
    ];

    const materi = [
        { name: 'Kalkulus Integral', pct: 92 },
        { name: 'Logika & Analisis', pct: 86 },
        { name: 'Grammar & Reading', pct: 78 },
        { name: 'Stoikiometri', pct: 64 },
        { name: 'Genetika', pct: 55 },
    ];

    const chart = document.getElementById('lapChart');
    const materiBox = document.getElementById('lapMateri');
    const rangeLabel = document.getElementById('rangeLabel');
    const statDelta = document.getElementById('statDelta');
    const statAkurasi = document.getElementById('statAkurasi');

    // tinggi bar dalam % (dari 20 s/d 100), kampe relatif ke rentang skor 600-712
    const hOf = (val) => Math.max(22, Math.min(100, ((val - 600) / 112) * 100 + 20));

    const renderMateri = (limit) => {
        const list = materi.slice(0, limit);
        materiBox.innerHTML = list.map((m) => `
            <div class="lap-materi-row">
                <div class="lap-materi-head"><b>${m.name}</b><span>${m.pct}%</span></div>
                <div class="dash-progress"><span style="width:${m.pct}%"></span></div>
            </div>`).join('');
    };

    const renderChart = (range) => {
        const startIdx = months.length - range;
        const data = months.slice(startIdx);
        rangeLabel.textContent = range + (range === 1 ? ' Bulan' : ' Bulan Terakhir');

        const startVal = data[0].val;
        const endVal = data[data.length - 1].val;
        statDelta.textContent = (endVal >= startVal ? '+' : '') + (endVal - startVal);
        statAkurasi.textContent = Math.round(62 + endVal / 26) + '%';

        chart.innerHTML = data.map((m, i) => `
            <div class="lap-bar-col ${i === data.length - 1 ? 'is-current' : ''}">
                <span class="lap-bar-value">${m.val}</span>
                <div class="lap-bar-area"><div class="lap-bar" data-h="${hOf(m.val)}"></div></div>
                <span class="lap-bar-label">${m.label}</span>
            </div>`).join('');

        requestAnimationFrame(() => {
            chart.querySelectorAll('.lap-bar-col').forEach((col) => {
                const bar = col.querySelector('.lap-bar');
                const area = col.querySelector('.lap-bar-area');
                const px = (parseFloat(bar.dataset.h) / 100) * area.getBoundingClientRect().height;
                bar.style.height = px + 'px';
            });
        });
    };

    document.querySelectorAll('#rangeFilter button').forEach((btn) => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('#rangeFilter button').forEach((b) => b.classList.remove('active'));
            btn.classList.add('active');
            renderChart(parseInt(btn.dataset.range, 10));
        });
    });

    renderChart(6);
    renderMateri(materi.length);

    document.querySelectorAll('.dash-reveal').forEach((el) => el.classList.add('is-visible'));
});