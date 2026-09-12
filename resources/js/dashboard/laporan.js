// PintarKuy — Dashboard: Laporan (bar chart + rentang waktu + rekomendasi)
document.addEventListener('DOMContentLoaded', () => {
    const months = [
        { label: 'Jan', val: 628 },
        { label: 'Feb', val: 641 },
        { label: 'Mar', val: 652 },
        { label: 'Apr', val: 669 },
        { label: 'Mei', val: 688 },
        { label: 'Jun', val: 712 },
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

    const renderMateri = (limit) => {
        const list = materi.slice(0, limit);
        materiBox.innerHTML = list.map((m) => `
            <div class="lap-materi-row">
                <div class="lap-materi-head"><b>${m.name}</b><span>${m.pct}%</span></div>
                <div class="dash-progress"><span style="width:${m.pct}%"></span></div>
            </div>`).join('');
    };

    const renderChart = (range) => {
        const data = months.slice(-range);
        rangeLabel.textContent = range + (range === 1 ? ' Bulan' : ' Bulan Terakhir');
        statDelta.textContent = '+' + (data[data.length - 1].val - (months[months.length - data.length - 1] ? months[months.length - data.length - 1].val : 0));
        statAkurasi.textContent = Math.round(62 + data[data.length - 1].val / 26) + '%';

        chart.innerHTML = data.map((m) => `
            <div class="lap-bar-col">
                <span class="lap-bar-value">${m.val}</span>
                <div class="lap-bar-track"><div class="lap-bar" data-h="${(m.val - 600) / 112 * 100 + 20}"></div></div>
                <span class="lap-bar-label">${m.label}</span>
            </div>`).join('');

        requestAnimationFrame(() => {
            chart.querySelectorAll('.lap-bar').forEach((bar) => {
                bar.style.height = bar.dataset.h + '%';
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