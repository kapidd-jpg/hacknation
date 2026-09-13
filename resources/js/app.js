// PintarKuy — front-end scripts
document.addEventListener('DOMContentLoaded', () => {
    const user = window.pintarKuyAuth.user();
    if (user) {
        if (user.name) {
            document.querySelectorAll('[data-user-name]').forEach((el) => { el.textContent = user.name; });
        }
        if (user.photo) {
            document.querySelectorAll('[data-user-photo]').forEach((el) => { el.setAttribute('src', user.photo); });
        }
    }
});