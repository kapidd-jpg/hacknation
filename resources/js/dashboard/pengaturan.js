// PintarKuy - Dashboard: Pengaturan (tabs + toggles + profil tersimpan)
document.addEventListener('DOMContentLoaded', () => {
    const auth = window.pintarKuyAuth;

    const initialsOf = (name) => {
        const parts = String(name || '').trim().replace(/\s+/g, ' ').split(' ');
        const first = (parts[0] || '').charAt(0).toUpperCase();
        const second = parts.length > 1 ? parts[1].charAt(0).toUpperCase() : '';
        return (first + second) || 'U';
    };

    // toast
    const toast = document.getElementById('settingToast');
    const showToast = (msg) => {
        if (!toast) return;
        toast.querySelector('.setting-toast-msg').textContent = msg;
        toast.classList.add('show');
        window.setTimeout(() => toast.classList.remove('show'), 2600);
    };

    // tabs
    const tabs = document.querySelectorAll('#settingTabs button');
    const panes = document.querySelectorAll('.setting-pane');
    tabs.forEach((btn) => {
        btn.addEventListener('click', () => {
            tabs.forEach((b) => b.classList.remove('active'));
            btn.classList.add('active');
            panes.forEach((p) => p.classList.remove('active'));
            document.getElementById('pane-' + btn.dataset.tab).classList.add('active');
        });
    });

    // field profil
    const nama = document.getElementById('sNama');
    const bio = document.getElementById('sBio');

    // muat data tersimpan
    const user = auth.user() || {};
    if (nama && user.name) nama.value = user.name;
    if (bio && user.bio) bio.value = user.bio;

    // toggle notifikasi - balikin state & simpan ke localStorage
    const prefs = window.pintarKuyPrefs || {};
    const persistPrefs = () => {
        const p = { ...(window.pintarKuyPrefs || {}) };
        document.querySelectorAll('.toggle').forEach((t) => {
            const key = t.parentElement.querySelector('b') ? t.parentElement.querySelector('b').textContent.trim() : '';
            if (key) p[key] = t.classList.contains('on');
        });
        window.pintarKuyPrefs = p;
        try { localStorage.setItem('pintarKuyPrefs', JSON.stringify(p)); } catch (e) {}
    };
    document.querySelectorAll('.toggle').forEach((t) => {
        const key = t.parentElement.querySelector('b') ? t.parentElement.querySelector('b').textContent.trim() : '';
        if (prefs[key] !== undefined) {
            t.classList.toggle('on', !!prefs[key]);
            t.setAttribute('aria-pressed', prefs[key] ? 'true' : 'false');
        }
        t.addEventListener('click', () => {
            t.classList.toggle('on');
            t.setAttribute('aria-pressed', t.classList.contains('on') ? 'true' : 'false');
            persistPrefs();
        });
    });

    // sinkron chip nama/inisial (topbar & header) seketika
    const syncChips = (name) => {
        document.querySelectorAll('[data-user-name]').forEach((el) => {
            if (el && name) el.textContent = name;
        });
        document.querySelectorAll('.site-userchip-name').forEach((el) => {
            if (el && name) el.textContent = String(name).trim().split(' ')[0] || '';
        });
        document.querySelectorAll('[data-user-initials]').forEach((el) => {
            if (el && name) el.textContent = initialsOf(name);
        });
    };

    // simpan perubahan
    document.querySelectorAll('.setting-save').forEach((btn) => {
        btn.addEventListener('click', () => {
            const pane = btn.closest('.setting-pane');
            if (pane && pane.id === 'pane-profil') {
                const profil = {
                    name: nama ? nama.value.trim() : '',
                    bio: bio ? bio.value.trim() : '',
                };
                const userBefore = auth.user() || {};
                syncChips(profil.name);

                const url = window.pintarKuyPengaturanUrl || '';
                if (url) {
                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': window.pintarKuyCsrf || (document.querySelector('meta[name="csrf-token"]') || {}).getAttribute?.('content') || '',
                        },
                        body: JSON.stringify(profil),
                    })
                        .then((r) => r.json().catch(() => ({})))
                        .then((res) => {
                            if (res && res.ok === true) {
                                // Simpan ke localStorage HANYA setelah server sukses,
                                // supaya chip nama tidak menampilkan versi "phantom".
                                auth.login(profil);
                                syncChips(profil.name);
                                showToast(res.message || 'Profil berhasil disimpan.');
                            } else {
                                syncChips(userBefore.name);
                                showToast((res && res.message) || 'Gagal menyimpan profil. Periksa kembali isianmu.');
                            }
                        })
                        .catch(() => {
                            syncChips(userBefore.name);
                            showToast('Gagal menyimpan profil. Periksa koneksimu.');
                        });
                } else {
                    auth.login(profil);
                    showToast(btn.dataset.msg || 'Profil berhasil disimpan.');
                }
                return;
            }

            if (pane && pane.id === 'pane-keamanan') {
                const passLama = document.getElementById('sPassLama');
                const passBaru = document.getElementById('sPassBaru');
                const passKonf = document.getElementById('sPassKonf');
                const lama = passLama ? passLama.value : '';
                const baru = passBaru ? passBaru.value : '';
                const konf = passKonf ? passKonf.value : '';

                if (!lama) {
                    showToast('Password lama wajib diisi.');
                    return;
                }
                if (!baru || baru.length < 8) {
                    showToast('Password baru minimal 8 karakter.');
                    return;
                }
                if (baru !== konf) {
                    showToast('Konfirmasi password tidak cocok.');
                    return;
                }

                const keamananUrl = window.pintarKuyKeamananUrl || '';
                fetch(keamananUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': window.pintarKuyCsrf || '',
                    },
                    body: JSON.stringify({
                        password_lama: lama,
                        password_baru: baru,
                        password_baru_confirmation: konf,
                    }),
                })
                    .then((r) => r.json().catch(() => ({})))
                    .then((res) => {
                        if (res && res.ok === true) {
                            if (passLama) passLama.value = '';
                            if (passBaru) passBaru.value = '';
                            if (passKonf) passKonf.value = '';
                            showToast(res.message || 'Password berhasil diubah.');
                        } else {
                            showToast((res && res.message) || 'Gagal mengubah password.');
                        }
                    })
                    .catch(() => showToast('Gagal mengubah password. Periksa koneksimu.'));
                return;
            }

            showToast(btn.dataset.msg || 'Perubahan berhasil disimpan.');
        });
    });

    document.querySelectorAll('.dash-reveal').forEach((el) => el.classList.add('is-visible'));
});