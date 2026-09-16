// PintarKuy - Dashboard: Laporan (bar chart + rentang waktu + rekomendasi)
document.addEventListener('DOMContentLoaded', () => {
    const data = window.pintarKuyLaporan || {};
    const months = Array.isArray(data.months) ? data.months : [];
    const materi = Array.isArray(data.materi) ? data.materi : [];

    const chart = document.getElementById('lapChart');
    const materiBox = document.getElementById('lapMateri');
    const rangeLabel = document.getElementById('rangeLabel');
    const statSkor = document.getElementById('statSkor');
    const statDelta = document.getElementById('statDelta');
    const statDeltaSub = document.getElementById('statDeltaSub');
    const statAkurasi = document.getElementById('statAkurasi');
    const statAkurasiSub = document.getElementById('statAkurasiSub');

    // tinggi bar dalam % - dinormalisasi dari rentang skor aktual
    const hOf = (slice) => {
        const vals = slice.map((m) => m.val);
        const min = Math.min(...vals);
        const max = Math.max(...vals);
        const span = max - min || 1;
        return (val) => 30 + ((val - min) / span) * 70;
    };

    const deltaClass = (diff) => (diff >= 0 ? 'lap-delta--up' : 'lap-delta--down');
    const deltaText = (diff) => (diff >= 0 ? '▲ +' + diff : '▼ ' + diff);

    const renderMateri = () => {
        if (!materi.length) {
            materiBox.innerHTML = '<div class="lap-empty">Belum ada rekomendasi. Daftar kelas dulu untuk mendapat saran materi.</div>';
            return;
        }
        materiBox.innerHTML = materi.map((m) => `
            <div class="lap-materi-row">
                <div class="lap-materi-head"><b>${m.name}</b><span>${m.pct}%</span></div>
                <div class="dash-progress"><span style="width:${m.pct}%"></span></div>
            </div>`).join('');
    };

    const renderChart = (range) => {
        rangeLabel.textContent = range + (range === 1 ? ' Bulan' : ' Bulan Terakhir');

        if (!months.length) {
            chart.innerHTML = '<div class="lap-empty">Belum ada data tryout. Ayo ikuti tryout pertamamu!</div>';
            if (statDelta) statDelta.textContent = '-';
            if (statSkor) statSkor.textContent = '-';
            if (statAkurasi) statAkurasi.textContent = '-';
            return;
        }

        if (statSkor) statSkor.textContent = months[months.length - 1].val;

        const slice = months.slice(-range);
        const startVal = slice[0].val;
        const endVal = slice[slice.length - 1].val;
        const diff = endVal - startVal;

        if (statDelta) {
            statDelta.textContent = (diff >= 0 ? '+' : '') + diff;
            statDelta.className = 'lap-stat-delta ' + deltaClass(diff);
        }
        if (statDeltaSub) statDeltaSub.textContent = 'Sejak ' + range + ' bulan lalu';

        const avg = Math.round(slice.reduce((s, m) => s + (m.akurasi || 0), 0) / slice.length);
        if (statAkurasi) statAkurasi.textContent = avg + '%';
        if (statAkurasiSub) statAkurasiSub.textContent = 'Rata-rata ' + range + ' bulan';

        const _h = hOf(slice);
        chart.innerHTML = slice.map((m, i) => `
            <div class="lap-bar-col ${i === slice.length - 1 ? 'is-current' : ''}">
                <span class="lap-bar-value">${m.val}</span>
                <div class="lap-bar-area"><div class="lap-bar" data-h="${_h(m.val)}"></div></div>
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
    renderMateri();

    document.querySelectorAll('.dash-reveal').forEach((el) => el.classList.add('is-visible'));
});