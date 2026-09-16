{{-- Konfirmasi logout - dipakai layout dashboard & guru. Diwarnai dengan .dash-logout-overlay / .dash-logout-modal --}}
@once
    <div class="dash-logout-overlay" id="logoutOverlay" role="dialog" aria-modal="true" aria-labelledby="logoutModalTitle">
        <div class="dash-logout-modal">
            <span class="dash-logout-icon">
                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
            </span>
            <h3 class="dash-logout-title" id="logoutModalTitle">Anda yakin ingin keluar?</h3>
            <p class="dash-logout-desc">Sesi kamu akan diakhiri di perangkat ini. Data belajar tetap aman tersimpan.</p>
            <div class="dash-logout-actions">
                <button type="button" class="dash-logout-btn dash-logout-cancel" data-logout-cancel>Batal</button>
                <button type="button" class="dash-logout-btn dash-logout-confirm" data-logout-confirm>Ya, Keluar</button>
            </div>
        </div>
    </div>

    <script>
        (function () {
            function openLogoutModal() {
                const ov = document.getElementById('logoutOverlay');
                if (ov && !ov.classList.contains('is-open')) ov.classList.add('is-open');
            }
            function closeLogoutModal() {
                const ov = document.getElementById('logoutOverlay');
                if (ov) ov.classList.remove('is-open');
            }

            document.querySelectorAll('form.dash-logout').forEach(function (form) {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    openLogoutModal();
                    // pastikan konfirmasi men-submit form yang barusan ditekan
                    form.dataset.confirmTarget = '1';
                });
            });

            const overlay = document.getElementById('logoutOverlay');
            if (overlay) {
                const cancel = overlay.querySelector('[data-logout-cancel]');
                const confirmBtn = overlay.querySelector('[data-logout-confirm]');
                if (cancel) cancel.addEventListener('click', closeLogoutModal);
                if (confirmBtn) confirmBtn.addEventListener('click', function () {
                    if (window.pintarKuyAuth && window.pintarKuyAuth.logout) window.pintarKuyAuth.logout();
                    const form = document.querySelector('form.dash-logout[data-confirm-target="1"]') || document.querySelector('form.dash-logout');
                    closeLogoutModal();
                    if (form) form.submit();
                });
                overlay.addEventListener('click', function (e) { if (e.target === overlay) closeLogoutModal(); });
                document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeLogoutModal(); });
            }
        })();
    </script>
@endonce