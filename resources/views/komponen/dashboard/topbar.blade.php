<header class="sticky top-0 z-30 h-20 bg-white/90 backdrop-blur shadow-[0px_1px_8px_0px_rgba(15,27,76,0.04)] flex items-center justify-between px-4 sm:px-8 gap-3 sm:gap-6">
    <div class="flex items-center gap-2 sm:gap-4 flex-1 min-w-0">
        <button id="sidebarToggle" class="flex items-center justify-center size-10 rounded-full hover:bg-navy-50 transition shrink-0" title="Sembunyikan / Tampilkan sidebar">
            <svg class="size-5 text-navy-800" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M3 12h18M3 18h18"/></svg>
        </button>
        <a href="{{ route('home') }}" class="hidden md:flex items-center justify-center size-10 rounded-full hover:bg-navy-50 transition shrink-0" title="Kembali ke Beranda">
            <svg class="size-5 text-navy-800" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h4v-6h4v6h4a1 1 0 001-1V10"/></svg>
        </a>
        <div class="relative max-w-md w-full min-w-0 hidden" title="Pencarian segera hadir">
            <svg class="absolute left-4 top-1/2 -translate-y-1/2 size-4 text-ink-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" placeholder="Cari materi, kelas, tugas, atau tutor..."
                   class="w-full bg-navy-50 rounded-full pl-11 pr-12 sm:pr-16 py-2.5 text-xs text-navy-900 placeholder:text-ink-muted focus:outline-none focus:ring-2 focus:ring-navy-100">
        </div>
    </div>

    <div class="flex items-center gap-2 sm:gap-4 shrink-0">
        <span class="hidden md:flex items-center gap-2 bg-navy-50 rounded-full px-3 py-1.5">
            <svg class="size-3 text-ink-soft" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <span class="text-[11px] font-bold text-ink-soft">Semester Ganjil 2026/2027</span>
        </span>
        <button class="relative flex items-center justify-center size-10 rounded-full hover:bg-navy-50 transition hidden" title="Notifikasi segera hadir">
            <svg class="size-[18px] text-ink-soft" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2a2 2 0 01-.6 1.4L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            <span class="absolute top-2 right-2 size-2 rounded-full bg-red-600 ring-2 ring-white"></span>
        </button>
        <span class="hidden md:block h-6 w-px bg-navy-100"></span>
        <div class="flex items-center gap-2">
            <div class="relative size-8 shrink-0">
                <img src="{{ auth()->user()->foto ? \App\Support\UserFoto::src(auth()->user()->foto) : '' }}" alt="Foto profil" data-user-photo
                     class="absolute inset-0 size-8 rounded-full object-cover ring-2 ring-navy-800/20 {{ auth()->user()->foto ? '' : 'hidden' }}">
                <span data-user-initials
                      class="absolute inset-0 size-8 rounded-full ring-2 ring-navy-800/20 bg-navy-800 text-white text-xs font-bold flex items-center justify-center {{ auth()->user()->foto ? 'hidden' : '' }}">{{ \App\Support\UserFoto::initials(auth()->user()->name) }}</span>
                <span class="absolute -bottom-0.5 -right-0.5 size-2.5 rounded-full bg-brand-green ring-2 ring-white"></span>
            </div>
            <div class="hidden lg:block">
                <p class="text-navy-900 text-sm font-semibold leading-tight" data-user-name>{{ auth()->user()->name }}</p>
                <p class="text-ink-soft text-[11px] font-bold">{{ Auth::user()->role === 'guru' ? 'Guru PintarKuy' : (Auth::user()->kelas_jurusan ?: 'Siswa PintarKuy') }}</p>
            </div>
            <svg class="size-2.5 text-ink-soft hidden sm:block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
        </div>
    </div>
</header>