(function () {
    const kelasEl = document.getElementById('kelas_id');
    const materiEl = document.getElementById('materi_id');
    if (!kelasEl || !materiEl) return;

    const urlTemplate = materiEl.dataset.materiUrl || '';

    async function loadMateri(kelasId, keepValue) {
        if (!kelasId || !urlTemplate) {
            materiEl.innerHTML = '<option value="">- Tanpa materi -</option>';
            return;
        }
        try {
            const token = document.querySelector('meta[name="csrf-token"]');
            const res = await fetch(urlTemplate.replace('KELAS', kelasId), {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': token ? token.getAttribute('content') : '' },
            });
            const items = await res.json();
            materiEl.innerHTML = '<option value="">- Tanpa materi -</option>' + items.map(function (m) {
                return '<option value="' + m.id + '">' + m.judul.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;') + '</option>';
            }).join('');

            const sel = keepValue ? String(keepValue) : '';
            if (sel) {
                const opt = materiEl.querySelector('option[value="' + sel + '"]');
                if (opt) opt.selected = true;
            }
        } catch (_) {
            materiEl.innerHTML = '<option value="">- Tanpa materi -</option>';
        }
    }

    const c = String(kelasEl.value);
    loadMateri(c, materiEl.dataset.materiValue);
    kelasEl.addEventListener('change', function () {
        loadMateri(this.value);
    });
})();