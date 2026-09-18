// PintarKuy - front-end scripts
const applyUserToDom = () => {
    const user = window.pintarKuyAuth ? window.pintarKuyAuth.user() : null;
    if (!user) return;

    const initialsOf = (name) => {
        const parts = String(name || '').trim().replace(/\s+/g, ' ').split(' ');
        const first = (parts[0] || '').charAt(0).toUpperCase();
        const second = parts.length > 1 ? parts[1].charAt(0).toUpperCase() : '';
        return (first + second) || 'U';
    };

    if (user.name) {
        document.querySelectorAll('[data-user-name]').forEach((el) => { el.textContent = user.name; });
    }

    document.querySelectorAll('[data-user-photo]').forEach((el) => {
        const serverSrc = el.getAttribute('src');
        const hasServerSrc = Boolean(serverSrc && serverSrc.trim());

        // Nilai yang dirender server (DB) adalah sumber kebenaran. Jangan timpa
        // dengan foto localStorage yang bisa basi ("phantom"). Foto dari localStorage
        // hanya dipakai untuk elemen yang belum diisi server (mis. section SPA).
        if (hasServerSrc) return;

        if (user.photo) {
            el.classList.remove('hidden');
            el.setAttribute('src', user.photo);
        } else {
            el.classList.add('hidden');
        }
    });

    document.querySelectorAll('[data-user-initials]').forEach((el) => {
        if (user.photo) {
            el.classList.add('hidden');
        } else {
            el.classList.remove('hidden');
            el.textContent = initialsOf(user.name);
        }
    });
};

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.dash-reveal').forEach((el) => el.classList.add('is-visible'));
    applyUserToDom();
});

// halaman di-restore browser (Back/Forward Cache): terapkan ulang data terbaru
// supaya chip nama/foto topbar & header tidak menampilkan versi lama.
window.addEventListener('pageshow', (event) => {
    if (event.persisted) {
        applyUserToDom();
    }
});