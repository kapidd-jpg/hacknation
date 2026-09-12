@php
    $current = request()->route() ? request()->route()->getName() : 'dashboard';
    $menus = [
        ['dashboard',          'Dashboard',   'M3 12l9-9 9 9M5 10v10a1 1 0 001 1h4v-6h4v6h4a1 1 0 001-1V10'],
        ['dashboard.kelas',    'Kelas Saya',  'M12 6.25278V19.25M12 6.25278C10.8321 5.47686 9.24649 5 7.5 5C5.75351 5 4.16789 5.47686 3 6.25278V19.25C4.16789 18.4741 5.75351 18 7.5 18C9.24649 18 10.8321 18.4741 12 19.25M12 6.25278C13.1679 5.47686 14.7535 5 16.5 5C18.2465 5 19.8321 5.47686 21 6.25278V19.25C19.8321 18.4741 18.2465 18 16.5 18C14.7535 18 13.1679 18.4741 12 19.25'],
        ['dashboard.katalog',  'Katalog',     'M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z'],
        ['dashboard.nilai',    'Nilai',       'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm10 0V9a2 2 0 00-2-2h-2a2 2 0 00-2 2v10a2 2 0 002 2h2a2 2 0 002-2z'],
        ['dashboard.laporan',  'Laporan',     'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
        ['dashboard.pengaturan', 'Pengaturan', 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z'],
    ];
@endphp

<aside class="fixed inset-y-0 left-0 w-72 bg-navy-800 shadow-xl flex flex-col justify-between z-40">
    <div>
        <div class="flex items-center gap-4 h-24 px-6">
            <span class="flex items-center justify-center size-12 rounded-2xl bg-gradient-to-br from-brand-green to-navy-950 shadow-lg ring-1 ring-white/20 shrink-0">
                <svg class="size-7 text-brand-greenlight" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.25278V19.25M12 6.25278C10.8321 5.47686 9.24649 5 7.5 5C5.75351 5 4.16789 5.47686 3 6.25278V19.25C4.16789 18.4741 5.75351 18 7.5 18C9.24649 18 10.8321 18.4741 12 19.25M12 6.25278C13.1679 5.47686 14.7535 5 16.5 5C18.2465 5 19.8321 5.47686 21 6.25278V19.25C19.8321 18.4741 18.2465 18 16.5 18C14.7535 18 13.1679 18.4741 12 19.25"/></svg>
            </span>
            <div>
                <p class="text-white font-extrabold text-[22px] leading-tight tracking-tight">PintarKuy</p>
                <p class="text-navy-400 text-xs font-bold tracking-[0.14em] uppercase">Bimbel Adaptif</p>
            </div>
        </div>

        <nav class="flex flex-col gap-1 px-4 mt-2">
            @foreach ($menus as [$route, $label, $icon])
                @php $active = $current === $route; @endphp
                <a href="{{ route($route) }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition
                    {{ $active ? 'bg-white text-navy-950 shadow-lg' : 'text-navy-300 hover:bg-white/5 font-semibold text-sm' }}">
                    <span class="flex items-center justify-center size-7 rounded-lg shrink-0 {{ $active ? 'bg-navy-50' : 'bg-white/10' }}">
                        <svg class="size-4 {{ $active ? 'text-navy-800' : 'text-navy-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/></svg>
                    </span>
                    <span class="{{ $active ? 'text-base' : 'text-sm font-semibold' }}">{{ $label }}</span>
                </a>
            @endforeach
        </nav>
    </div>

    <div class="flex flex-col gap-3 p-4">
        <div class="bg-black/25 rounded-xl p-3.5 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <span class="flex items-center justify-center size-8 rounded-lg bg-brand-green/30 shrink-0">
                    <svg class="size-4 text-brand-greenlight" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </span>
                <div>
                    <p class="text-white text-xs font-semibold">Paket UTBK Pro</p>
                    <p class="text-navy-400 text-[11px] font-bold">Aktif s/d Mei 2025</p>
                </div>
            </div>
        </div>
        <a href="{{ route('login') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-navy-300 text-xs font-semibold hover:bg-white/5 transition">
            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            Keluar
        </a>
    </div>
</aside>