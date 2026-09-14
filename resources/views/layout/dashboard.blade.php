@extends('layout.app')

@section('content')
@php
    $pkSideUser = auth()->user();
    $pkSideNama = $pkSideUser?->pakets->pluck('nama')->all() ?? [];
    $pkSideAkses = $pkSideUser?->aksesKategori() ?? [];
@endphp
<div class="dash-wrap min-h-screen bg-navy-50" id="dashWrap">
    <aside class="dash-sidebar" id="dashSidebar">
        <div class="dash-sidebar-inner">
            <div>
                <div class="dash-brand flex items-center gap-4 h-24 px-6">
<a href="{{ route('home') }}" class="flex items-center gap-4" title="Kembali ke Beranda">
                            <img src="{{ asset('assets/images/logopintar.png') }}" alt="PintarKuy" class="h-9 w-auto">
                            <div class="dash-brand-text">
                                <p class="text-white font-extrabold text-[22px] leading-tight tracking-tight">PintarKuy</p>
                                <p class="text-navy-400 text-xs font-bold tracking-[0.14em] uppercase">Bimbel Adaptif</p>
                            </div>
                        </a>
                </div>

                <nav class="flex flex-col gap-1 px-4 mt-2">
                    @php
                        $current = request()->route() ? request()->route()->getName() : 'dashboard';
                        $menus = [
                            ['dashboard',          'Dashboard',   'M3 12l9-9 9 9M5 10v10a1 1 0 001 1h4v-6h4v6h4a1 1 0 001-1V10'],
                            ['dashboard.kelas',    'Kelas Saya',  'M12 6.25278V19.25M12 6.25278C10.8321 5.47686 9.24649 5 7.5 5C5.75351 5 4.16789 5.47686 3 6.25278V19.25C4.16789 18.4741 5.75351 18 7.5 18C9.24649 18 10.8321 18.4741 12 19.25M12 6.25278C13.1679 5.47686 14.7535 5 16.5 5C18.2465 5 19.8321 5.47686 21 6.25278V19.25C19.8321 18.4741 18.2465 18 16.5 18C14.7535 18 13.1679 18.4741 12 19.25'],
                            ['dashboard.katalog',  'Katalog',     'M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z'],
                            ['dashboard.latsol',   'Latihan Soal', 'M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125'],
                            ['dashboard.nilai',    'Nilai',       'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm10 0V9a2 2 0 00-2-2h-2a2 2 0 00-2 2v10a2 2 0 002 2h2a2 2 0 002-2z'],
                            ['dashboard.laporan',  'Laporan',     'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                            ['dashboard.pengaturan', 'Pengaturan', 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z'],
                        ];
                    @endphp
                    @foreach ($menus as [$route, $label, $icon])
                        @php $active = $current === $route; @endphp
                        <a href="{{ route($route) }}" class="dash-menu-link flex items-center gap-3 px-4 py-3 rounded-xl transition
                            {{ $active ? 'bg-white text-navy-950 shadow-lg' : 'text-navy-300 hover:bg-white/5 font-semibold text-sm' }}">
                            <span class="flex items-center justify-center size-7 rounded-lg shrink-0 {{ $active ? 'bg-navy-50' : 'bg-white/10' }}">
                                <svg class="size-4 {{ $active ? 'text-navy-800' : 'text-navy-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/></svg>
                            </span>
                            <span class="dash-menu-label {{ $active ? 'text-base' : 'text-sm font-semibold' }}">{{ $label }}</span>
                        </a>
                    @endforeach
                </nav>
            </div>

            <div class="flex flex-col gap-3 p-4">
                <div class="dash-paket bg-black/25 rounded-xl p-3.5 flex flex-col gap-2.5">
                    <div class="flex items-center gap-2.5">
                        <span class="flex items-center justify-center size-8 rounded-lg bg-brand-green/30 shrink-0">
                            <svg class="size-4 text-brand-greenlight" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </span>
                        <div class="dash-paket-text">
                            <p class="text-white text-xs font-semibold" id="dashPaketLabel">{{ count($pkSideNama) ? implode(' + ', $pkSideNama) : 'Belum Ada Paket' }}</p>
                            <p class="text-navy-400 text-[11px] font-bold" id="dashPaketSub">{{ count($pkSideNama) ? 'Akses: ' . implode(' · ', $pkSideAkses) : 'Pilih paket untuk mulai belajar' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between gap-3 mt-0.5">
                        <a href="{{ route('paket.index') }}" class="dash-paket-upgrade text-brand-greenlight text-[11px] font-bold flex items-center gap-1 hover:underline" id="dashPaketKelola">
                            {{ count($pkSideNama) ? 'Kelola Paket' : 'Pilih Paket' }}
                            <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m0 0l-6-6m6 6l-6 6"/></svg>
                        </a>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="dash-logout flex items-center gap-3 px-4 py-2.5 rounded-xl text-navy-300 text-xs font-semibold hover:bg-white/5 transition">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 w-full text-left">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        <span class="dash-logout-label">Keluar</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <div class="dash-sidebar-overlay" id="sidebarOverlay"></div>

    <div class="dash-main min-h-screen" id="dashMain">
        @include('komponen.dashboard.topbar')
        <main class="px-4 sm:px-8 py-6 sm:py-8 flex flex-col gap-8">
            @yield('pageContent')
        </main>
    </div>
</div>

<script>
    (function () {
        let u = null;
        // sinkron akun (dari DB) ke localStorage supaya JS lama tetap kompatibel
        try {
            @php
                $pkUser = auth()->user();
                $pkUserCtx = $pkUser ? [
                    'name' => $pkUser->name,
                    'email' => $pkUser->email,
                    'foto' => $pkUser->foto,
                    'sekolah' => $pkUser->sekolah,
                    'kelas_jurusan' => $pkUser->kelas_jurusan,
                    'bio' => $pkUser->bio,
                    'role' => $pkUser->role,
                    'paketKeys' => $pkUser->paketKeys(),
                    'paketNama' => $pkUser->pakets->pluck('nama')->all(),
                    'aksesKategori' => $pkUser->aksesKategori(),
                ] : null;
                $pkUserFoto = auth()->user()?->foto ? route('user.foto') : null;
            @endphp
            const u = @json($pkUserCtx);
            if (u && window.pintarKuyAuth) {
                window.pintarKuyAuth.login({
                    name: u.name,
                    email: u.email,
                    photo: @json($pkUserFoto),
                    sekolah: u.sekolah,
                    kelas_jurusan: u.kelas_jurusan,
                    bio: u.bio,
                    role: u.role,
                });
                localStorage.setItem('pintarKuyPaket', (u.paketKeys || []).join(',') || '');
            }
        } catch (_) {}

        const wrap = document.getElementById('dashWrap');
        const toggle = document.getElementById('sidebarToggle');
        const overlay = document.getElementById('sidebarOverlay');
        if (!wrap || !toggle) return;
        const apply = (collapsed) => {
            wrap.classList.toggle('is-collapsed', collapsed);
            try { localStorage.setItem('pintarKuySidebar', collapsed ? '1' : '0'); } catch (e) {}
        };
        try { if (localStorage.getItem('pintarKuySidebar') === '1') apply(true); } catch (e) {}
        const isMobile = () => window.matchMedia('(max-width: 1023px)').matches;
        toggle.addEventListener('click', () => {
            if (isMobile()) wrap.classList.toggle('is-open');
            else apply(!wrap.classList.contains('is-collapsed'));
        });
        if (overlay) overlay.addEventListener('click', () => wrap.classList.remove('is-open'));
        window.addEventListener('resize', () => { if (!isMobile()) wrap.classList.remove('is-open'); });

        const PAKETS = {
            'utbk':       { label: 'Paket UTBK',        kategori: ['UTBK-SNBT'] },
            'sma-ekstra': { label: 'Paket SMA + Ekstra', kategori: ['SMA', 'Ekstra'] },
            'bahasa':     { label: 'Paket Bahasa',       kategori: ['Bahasa'] },
        };
        const lbl = document.getElementById('dashPaketLabel');
        const sub = document.getElementById('dashPaketSub');
        const kelola = document.getElementById('dashPaketKelola');
        const hasPaket = !!(u && u.paketKeys && u.paketKeys.length);
        try {
            if (hasPaket) {
                const kat = (u.aksesKategori || []).join(' · ');
                if (lbl) lbl.textContent = (u.paketNama || []).join(' + ');
                if (sub) sub.textContent = kat ? 'Akses: ' + kat : 'Akses sesuai paket';
                if (kelola) kelola.textContent = 'Kelola Paket';
            } else {
                if (lbl) lbl.textContent = 'Belum Ada Paket';
                if (sub) sub.textContent = 'Pilih paket untuk mulai belajar';
                if (kelola) kelola.textContent = 'Pilih Paket';
            }
        } catch (e) {}
    })();
</script>

@include('komponen.dashboard.logout-confirm')
@endsection