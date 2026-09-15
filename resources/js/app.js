// PintarKuy — front-end scripts
document.addEventListener('DOMContentLoaded', () => {
    const user = window.pintarKuyAuth.user();
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
});