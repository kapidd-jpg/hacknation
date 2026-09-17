<header class="dash-topbar">
    <div class="flex items-center gap-2 sm:gap-3 flex-1 min-w-0">
        <button id="sidebarToggle" class="dash-topbar-btn" title="Sembunyikan / Tampilkan sidebar">
            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M3 12h18M3 18h18"/></svg>
        </button>
        <a href="{{ route('home') }}" class="dash-topbar-btn hidden md:flex" title="Kembali ke Beranda">
            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h4v-6h4v6h4a1 1 0 001-1V10"/></svg>
        </a>
        <div class="relative max-w-md w-full min-w-0 hidden" title="Pencarian segera hadir">
            <svg class="dash-topbar-search-ico" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" placeholder="Cari materi, kelas, tugas, atau tutor..." class="dash-topbar-search">
        </div>
    </div>

    <div class="flex items-center gap-2 sm:gap-3 shrink-0">
        <span class="dash-semester hidden md:flex">
            <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <span>Ganjil 2026/2027</span>
        </span>
        <button class="dash-topbar-btn relative hidden" title="Notifikasi segera hadir">
            <svg class="size-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2a2 2 0 01-.6 1.4L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            <span class="dash-topbar-dot"></span>
        </button>
        <span class="dash-topbar-div hidden md:block"></span>
        <div class="flex items-center gap-2.5">
            <div class="relative size-8 shrink-0">
                <img src="{{ auth()->user()->foto ? \App\Support\UserFoto::src(auth()->user()->foto) : '' }}" alt="Foto profil" data-user-photo
                     class="dash-user-photo absolute inset-0 size-8 rounded-full object-cover {{ auth()->user()->foto ? '' : 'hidden' }}">
                <span data-user-initials
                      class="dash-user-initials absolute inset-0 size-8 rounded-full {{ auth()->user()->foto ? 'hidden' : '' }}">{{ \App\Support\UserFoto::initials(auth()->user()->name) }}</span>
                <span class="dash-user-dot"></span>
            </div>
            <div class="hidden lg:block">
                <p class="dash-user-name" data-user-name>{{ auth()->user()->name }}</p>
                <p class="dash-user-role">{{ Auth::user()->role === 'guru' ? 'Guru PintarKuy' : (Auth::user()->kelas_jurusan ?: 'Siswa PintarKuy') }}</p>
            </div>
            <svg class="size-2.5 text-ink-soft hidden sm:block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
        </div>
    </div>
</header>