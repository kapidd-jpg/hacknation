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

    if (user.name) {
        document.querySelectorAll('[data-user-initials]').forEach((el) => { el.textContent = initialsOf(user.name); });
    }
};

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.dash-reveal').forEach((el) => el.classList.add('is-visible'));
    applyUserToDom();
});

// halaman di-restore browser (Back/Forward Cache): terapkan ulang data terbaru
// supaya chip nama topbar & header tidak menampilkan versi lama.
window.addEventListener('pageshow', (event) => {
    if (event.persisted) {
        applyUserToDom();
    }
});