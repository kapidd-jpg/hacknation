// PintarKuy — Dashboard: Pengaturan (tabs + toggles + toast)
document.addEventListener('DOMContentLoaded', () => {
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

    // toggle switches
    document.querySelectorAll('.toggle').forEach((t) => {
        t.addEventListener('click', () => {
            t.classList.toggle('on');
            t.setAttribute('aria-pressed', t.classList.contains('on') ? 'true' : 'false');
        });
    });

    // toast
    const toast = document.getElementById('settingToast');
    const showToast = (msg) => {
        if (!toast) return;
        toast.querySelector('.setting-toast-msg').textContent = msg;
        toast.classList.add('show');
        window.setTimeout(() => toast.classList.remove('show'), 2600);
    };

    document.querySelectorAll('.setting-save, [data-msg]').forEach((btn) => {
        if (!btn.hasAttribute('data-msg')) return;
        btn.addEventListener('click', () => showToast(btn.dataset.msg));
    });

    document.querySelectorAll('.dash-reveal').forEach((el) => el.classList.add('is-visible'));
});