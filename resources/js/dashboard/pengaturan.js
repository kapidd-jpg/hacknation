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
    const email = document.getElementById('sEmail');
    const sekolah = document.getElementById('sSekolah');
    const kelasJurusan = document.getElementById('sKelas');
    const bio = document.getElementById('sBio');
    const fotoEl = document.querySelector('#pane-profil .setting-avatar img');
    const fotoBtn = document.getElementById('sFotoBtn');
    const fileInput = document.createElement('input');
    fileInput.type = 'file';
    fileInput.accept = 'image/png,image/jpeg,image/webp';
    fileInput.hidden = true;
    document.body.appendChild(fileInput);
    let pickedFoto = '';

    // muat data tersimpan
    const user = auth.user() || {};
    if (nama && user.name) nama.value = user.name;
    if (email && user.email) email.value = user.email;
    if (sekolah && user.sekolah) sekolah.value = user.sekolah;
    if (kelasJurusan && user.kelas_jurusan) kelasJurusan.value = user.kelas_jurusan;
    if (bio && user.bio) bio.value = user.bio;

    // toggle switches - balikin state tersimpan
    const prefs = window.pintarKuyPrefs || {};
    document.querySelectorAll('.toggle').forEach((t) => {
        if (t.id === 'sTwoFa') return;
        const key = t.parentElement.querySelector('b') ? t.parentElement.querySelector('b').textContent.trim() : '';
        if (prefs[key] !== undefined) {
            t.classList.toggle('on', !!prefs[key]);
            t.setAttribute('aria-pressed', prefs[key] ? 'true' : 'false');
        }
        t.addEventListener('click', () => {
            t.classList.toggle('on');
            t.setAttribute('aria-pressed', t.classList.contains('on') ? 'true' : 'false');
        });
    });

    // toggle 2FA - simpan ke server (bukan sekadar kosmetik)
    const sTwoFa = document.getElementById('sTwoFa');
    const keamananUrl = window.pintarKuyKeamananUrl || '';
    const postKeamanan = (payload) => fetch(keamananUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': window.pintarKuyCsrf || '',
        },
        body: JSON.stringify(payload),
    }).then((r) => r.json().catch(() => ({})));

    if (sTwoFa && keamananUrl) {
        sTwoFa.addEventListener('click', () => {
            const want = !sTwoFa.classList.contains('on');
            const prev = sTwoFa.classList.contains('on');
            sTwoFa.classList.toggle('on', want);
            sTwoFa.setAttribute('aria-pressed', want ? 'true' : 'false');
            postKeamanan({ two_factor: want })
                .then((res) => {
                    if (res && res.ok === true) {
                        showToast(res.message || 'Pengaturan 2FA diperbarui.');
                    } else {
                        sTwoFa.classList.toggle('on', prev);
                        sTwoFa.setAttribute('aria-pressed', prev ? 'true' : 'false');
                        showToast((res && res.message) || 'Gagal memperbarui 2FA.');
                    }
                })
                .catch(() => {
                    sTwoFa.classList.toggle('on', prev);
                    sTwoFa.setAttribute('aria-pressed', prev ? 'true' : 'false');
                    showToast('Gagal memperbarui 2FA. Periksa koneksimu.');
                });
        });
    }

    // ganti foto
    if (fotoBtn) fotoBtn.addEventListener('click', () => fileInput.click());
    fileInput.addEventListener('change', () => {
        const f = fileInput.files && fileInput.files[0];
        if (!f) return;
        if (f.size > 2 * 1024 * 1024) {
            showToast('Ukuran foto maksimal 2 MB.');
            fileInput.value = '';
            return;
        }
        const reader = new FileReader();
        reader.onload = () => {
            const img = new Image();
            img.onload = () => {
                const MAX = 512;
                const scale = Math.min(MAX / img.width, MAX / img.height, 1);
                const canvas = document.createElement('canvas');
                canvas.width = Math.max(1, Math.round(img.width * scale));
                canvas.height = Math.max(1, Math.round(img.height * scale));
                const ctx = canvas.getContext('2d');
                ctx.fillStyle = '#fff';
                ctx.fillRect(0, 0, canvas.width, canvas.height);
                ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                pickedFoto = canvas.toDataURL('image/jpeg', 0.85);
                if (fotoEl) {
                    fotoEl.classList.remove('hidden');
                    fotoEl.setAttribute('src', pickedFoto);
                }
                const initialsEl = document.getElementById('sFotoInitials');
                if (initialsEl) initialsEl.classList.add('hidden');
                showToast('Foto terpilih. Klik "Simpan Perubahan" untuk menyimpan.');
            };
            img.onerror = () => {
                showToast('File foto tidak valid. Pilih file gambar lain.');
                fileInput.value = '';
            };
            img.src = String(reader.result);
        };
        reader.readAsDataURL(f);
    });

    // simpan perubahan
    document.querySelectorAll('.setting-save').forEach((btn) => {
        btn.addEventListener('click', () => {
            const pane = btn.closest('.setting-pane');
            if (pane && pane.id === 'pane-profil') {
                const profil = {
                    name: nama ? nama.value.trim() : '',
                    email: email ? email.value.trim() : '',
                    sekolah: sekolah ? sekolah.value.trim() : '',
                    kelas_jurusan: kelasJurusan ? kelasJurusan.value.trim() : '',
                    bio: bio ? bio.value.trim() : '',
                };
                const photoValue = pickedFoto || (fotoEl ? fotoEl.getAttribute('src') || '' : '');
                auth.login({ ...profil, photo: photoValue });
                document.querySelectorAll('[data-user-name]').forEach((el) => {
                    const u = auth.user();
                    if (el && u && u.name) el.textContent = u.name;
                });
                document.querySelectorAll('[data-user-initials]').forEach((el) => {
                    const u = auth.user();
                    if (el && u && !u.photo && u.name) el.textContent = initialsOf(u.name);
                });

                const url = window.pintarKuyPengaturanUrl || '';
                if (url) {
                    const payload = { ...profil };
                    if (pickedFoto) payload.foto = pickedFoto;
                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': window.pintarKuyCsrf || '',
                        },
                        body: JSON.stringify(payload),
                    })
                        .then((r) => r.json().catch(() => ({})))
                        .then((res) => {
                            if (res && res.ok === true) {
                                document.querySelectorAll('[data-user-photo]').forEach((el) => {
                                    if (!el) return;
                                    if (photoValue) {
                                        el.classList.remove('hidden');
                                        el.setAttribute('src', photoValue);
                                    } else {
                                        el.classList.add('hidden');
                                    }
                                });
                                document.querySelectorAll('[data-user-initials]').forEach((el) => {
                                    if (!el) return;
                                    if (photoValue) el.classList.add('hidden');
                                    else el.classList.remove('hidden');
                                });
                                showToast(res.message || 'Profil berhasil disimpan.');
                            } else {
                                showToast((res && res.message) || 'Gagal menyimpan profil. Periksa kembali isianmu.');
                            }
                        })
                        .catch(() => showToast('Gagal menyimpan profil. Periksa koneksimu.'));
                } else {
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

                postKeamanan({
                    password_lama: lama,
                    password_baru: baru,
                    password_baru_confirmation: konf,
                })
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

    // preferensi: simpan default ke localStorage agar nunggangi server
    const persistPrefs = () => {
        const p = { ...(window.pintarKuyPrefs || {}) };
        document.querySelectorAll('.toggle').forEach((t) => {
            const key = t.parentElement.querySelector('b') ? t.parentElement.querySelector('b').textContent.trim() : '';
            if (key) p[key] = t.classList.contains('on');
        });
        window.pintarKuyPrefs = p;
        try { localStorage.setItem('pintarKuyPrefs', JSON.stringify(p)); } catch (e) {}
    };
    document.querySelectorAll('.toggle').forEach((t) => t.addEventListener('click', persistPrefs));

    document.querySelectorAll('.dash-reveal').forEach((el) => el.classList.add('is-visible'));
});