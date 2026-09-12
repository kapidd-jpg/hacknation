<header class="bg-navy-800 sticky top-0 z-50 shadow-[0px_4px_20px_-2px_rgba(15,27,76,0.15)]">
    <div class="w-full flex items-center justify-between h-20 px-6 md:px-12">
        <a href="{{ url('/') }}" class="flex items-center gap-3">
            <span class="flex items-center justify-center size-12 rounded-xl bg-gradient-to-br from-brand-green to-navy-950 shadow-lg ring-1 ring-white/20 shrink-0">
                <svg class="size-7 text-brand-greenlight" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.25278V19.25M12 6.25278C10.8321 5.47686 9.24649 5 7.5 5C5.75351 5 4.16789 5.47686 3 6.25278V19.25C4.16789 18.4741 5.75351 18 7.5 18C9.24649 18 10.8321 18.4741 12 19.25M12 6.25278C13.1679 5.47686 14.7535 5 16.5 5C18.2465 5 19.8321 5.47686 21 6.25278V19.25C19.8321 18.4741 18.2465 18 16.5 18C14.7535 18 13.1679 18.4741 12 19.25"/></svg>
            </span>
            <span class="text-white font-bold text-xl tracking-tight">PintarKuy</span>
        </a>

        <nav class="hidden md:flex items-center gap-8">
            @php $routeName = request()->route() ? request()->route()->getName() : ''; @endphp
            @foreach ([
                ['Beranda', 'home', url('/')],
                ['Tentang', 'about', route('about')],
                ['Kelas', 'classes', route('classes')],
                ['Kontak', 'contact', route('contact')],
            ] as [$label, $name, $href])
                @if ($routeName === $name)
                    <a href="{{ $href }}" class="text-white font-bold border-b-2 border-brand-greenlight pb-1.5">{{ $label }}</a>
                @else
                    <a href="{{ $href }}" class="text-navy-400 text-sm font-semibold hover:text-white transition">{{ $label }}</a>
                @endif
            @endforeach
        </nav>

        <div class="flex items-center gap-4">
            <a href="{{ route('login') }}" class="text-white text-sm font-semibold px-3 py-2 hover:text-navy-300 transition">Masuk</a>
            <a href="{{ route('register') }}" class="bg-white text-navy-950 text-sm font-semibold px-6 py-2.5 rounded-full shadow hover:bg-navy-50 transition">Daftar</a>
        </div>
    </div>
</header>