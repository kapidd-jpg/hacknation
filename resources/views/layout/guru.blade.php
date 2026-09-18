@extends('layout.app')

@section('content')
<div class="dash-wrap min-h-screen bg-paper" id="guruWrap">
    <aside class="dash-sidebar" id="guruSidebar">
        <div class="dash-sidebar-inner">
            <div>
                <div class="dash-brand flex items-center h-24 px-6">
                    <a href="{{ route('home') }}" class="flex items-center gap-3.5" title="Kembali ke Beranda">
                        <img src="{{ asset('assets/images/logopintar.png') }}" alt="PintarKuy" class="h-9 w-auto shrink-0">
                        <div class="dash-brand-text">
                            <p class="text-ink font-bold text-[21px] leading-tight tracking-tight">PintarKuy</p>
                            <p class="text-brick text-[10.5px] font-bold tracking-[0.18em] uppercase">Portal Guru</p>
                        </div>
                    </a>
                </div>

                <nav class="flex flex-col gap-1 px-4 mt-2">
                    @php
                        $current = request()->route() ? request()->route()->getName() : 'guru.dashboard';
                        $isAdmin = Auth::user()->role === 'admin';
                        $menus = [
                            ['guru.dashboard',     'Dashboard',   'M3 12l9-9 9 9M5 10v10a1 1 0 001 1h4v-6h4v6h4a1 1 0 001-1V10'],
                            ['guru.kelas.index',   'Kelola Kelas', 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
                            ['guru.materi.index',  'Materi',      'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                            ['guru.soal.index',    'Soal',        'M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z'],
                            ['guru.room.index',    'Ruang Belajar', 'M19 11a7 7 0 01-14 0m7 7v4m-4 1h8m-7-13a2 2 0 004 0V6a2 2 0 10-4 0v3'],
                        ];
                        if ($isAdmin) {
                            $menus[] = ['guru.paket.index', 'Paket', 'M12 2v20m6-16H8a4 4 0 100 8h8a4 4 0 100 8H6'];
                            $menus[] = ['guru.siswa', 'Siswa', 'M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1a4 4 0 10-4-4 4 4 0 004 4z'];
                            $menus[] = ['guru.pengampu.index', 'Pengampu', 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 01-3.138-3.138z'];
                        }
                    @endphp
                    @foreach ($menus as [$route, $label, $icon])
                        @php $active = $current === $route || ($route === 'guru.room.index' && str_starts_with($current, 'guru.room.')); @endphp
                        <a href="{{ route($route) }}" class="dash-menu-link {{ $active ? 'is-active' : '' }}">
                            <span class="dash-menu-ico">
                                <svg class="size-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/></svg>
                            </span>
                            <span class="dash-menu-label">{{ $label }}</span>
                        </a>
                    @endforeach
                </nav>
            </div>

            <div class="flex flex-col gap-3 p-4">
                <div class="guru-rolecard">
                    <span class="guru-rolecard-badge">
                        @if ($isAdmin)
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        @else
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.7 50.7 0 0011.965 5.839m-11.965-5.84a50.7 50.7 0 00-.492 6.347m15.482-6.347a50.7 50.7 0 01-.492 6.346m-11.965-6.347A50.7 50.7 0 0012 3.096a50.7 50.7 0 0111.965 5.839m-11.965 0A50.7 50.7 0 0112 3.096"/></svg>
                        @endif
                    </span>
                    <div class="guru-rolecard-text">
                        <p class="guru-rolecard-title">Role: {{ $isAdmin ? 'Admin' : 'Guru' }}</p>
                        <p class="guru-rolecard-sub">{{ $isAdmin ? 'Operator: paket & akun siswa' : 'Mengelola konten bimbel' }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="dash-logout">
                    @csrf
                    <button type="submit" class="dash-logout-btn">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        <span class="dash-logout-label">Keluar</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <div class="dash-sidebar-overlay" id="sidebarOverlay"></div>

    <div class="dash-main min-h-screen" id="guruMain">
        @include('komponen.dashboard.topbar')
        <main class="px-4 sm:px-8 lg:px-10 py-6 sm:py-8 flex flex-col gap-8 w-full max-w-[1280px] mx-auto">
            @if (session('status'))
                <div class="guru-toast" role="status" id="guruToast">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif
            @yield('pageContent')
        </main>
    </div>
</div>

<script>
    (function () {
        try {
            @php
                $pkUserCtx = auth()->user() ? auth()->user()->only(['name','email','role']) : null;
            @endphp
            const u = @json($pkUserCtx);
            if (u && window.pintarKuyAuth) {
                window.pintarKuyAuth.login({ name: u.name, email: u.email, role: u.role });
            }
        } catch (_) {}
        const wrap = document.getElementById('guruWrap');
        const toggle = document.getElementById('sidebarToggle');
        const overlay = document.getElementById('sidebarOverlay');
        if (wrap && toggle) {
            const apply = (collapsed) => { wrap.classList.toggle('is-collapsed', collapsed); try { localStorage.setItem('pintarKuySidebar', collapsed ? '1' : '0'); } catch (e) {} };
            try { if (localStorage.getItem('pintarKuySidebar') === '1') apply(true); } catch (e) {}
            const isMobile = () => window.matchMedia('(max-width: 1023px)').matches;
            toggle.addEventListener('click', () => {
                if (isMobile()) wrap.classList.toggle('is-open');
                else apply(!wrap.classList.contains('is-collapsed'));
            });
            if (overlay) overlay.addEventListener('click', () => wrap.classList.remove('is-open'));
            window.addEventListener('resize', () => { if (!isMobile()) wrap.classList.remove('is-open'); });
        }
        document.querySelectorAll('.dash-reveal').forEach((el) => el.classList.add('is-visible'));
        const toast = document.getElementById('guruToast');
        if (toast) window.setTimeout(() => toast.classList.add('show'), 30);
    })();
</script>

@include('komponen.dashboard.logout-confirm')
@endsection