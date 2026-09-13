@extends('layout.app')

@section('content')
<div class="dash-wrap min-h-screen bg-navy-50" id="guruWrap">
    <aside class="dash-sidebar" id="guruSidebar">
        <div class="dash-sidebar-inner">
            <div>
                <div class="dash-brand flex items-center gap-4 h-24 px-6">
<a href="{{ route('home') }}" class="flex items-center gap-4" title="Kembali ke Beranda">
                            <img src="{{ asset('assets/images/logopintar.png') }}" alt="PintarKuy" class="h-9 w-auto">
                            <div class="dash-brand-text">
                                <p class="text-white font-extrabold text-[22px] leading-tight tracking-tight">PintarKuy</p>
                                <p class="text-navy-400 text-xs font-bold tracking-[0.14em] uppercase">Portal Guru</p>
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
                        ];
                        if ($isAdmin) {
                            $menus[] = ['guru.paket.index', 'Paket', 'M12 2v20m6-16H8a4 4 0 100 8h8a4 4 0 100 8H6'];
                            $menus[] = ['guru.siswa', 'Siswa', 'M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1a4 4 0 10-4-4 4 4 0 004 4z'];
                        }
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
                <div class="guru-rolecard bg-brand-green/20 rounded-xl p-4 flex items-center gap-3">
                    <span class="flex items-center justify-center size-9 rounded-lg bg-brand-green/30 shrink-0 text-lg">@if ($isAdmin) 🛡️ @else 👩‍🏫 @endif</span>
                    <div class="guru-rolecard-text">
                        <p class="text-white text-xs font-bold">Role: {{ $isAdmin ? 'Admin' : 'Guru' }}</p>
                        <p class="text-navy-300 text-[11px] font-semibold">{{ $isAdmin ? 'Operator: paket & akun siswa' : 'Mengelola konten bimbel' }}</p>
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

    <div class="dash-main min-h-screen" id="guruMain">
        @include('komponen.dashboard.topbar')
        <main class="px-8 py-8 flex flex-col gap-8">
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
            @php $pkUserCtx = auth()->user() ? auth()->user()->only(['name','email','foto','role']) : null; @endphp
            const u = @json($pkUserCtx);
            if (u && window.pintarKuyAuth) {
                window.pintarKuyAuth.login({ name: u.name, email: u.email, photo: u.foto, role: u.role });
            }
        } catch (_) {}
        const wrap = document.getElementById('guruWrap');
        const toggle = document.getElementById('sidebarToggle');
        if (wrap && toggle) {
            const apply = (collapsed) => { wrap.classList.toggle('is-collapsed', collapsed); try { localStorage.setItem('pintarKuySidebar', collapsed ? '1' : '0'); } catch (e) {} };
            try { if (localStorage.getItem('pintarKuySidebar') === '1') apply(true); } catch (e) {}
            toggle.addEventListener('click', () => apply(!wrap.classList.contains('is-collapsed')));
        }
        document.querySelectorAll('.dash-reveal').forEach((el) => el.classList.add('is-visible'));
        const toast = document.getElementById('guruToast');
        if (toast) window.setTimeout(() => toast.classList.add('show'), 30);
    })();
</script>
@endsection