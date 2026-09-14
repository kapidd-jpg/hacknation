<header class="bg-navy-800 sticky top-0 z-50 shadow-[0px_4px_20px_-2px_rgba(15,27,76,0.15)]">
    <div class="w-full flex items-center justify-between h-20 px-6 md:px-12">
        <a href="{{ url('/') }}" class="flex items-center gap-3" title="PintarKuy">
            <img src="{{ asset('assets/images/logopintar.png') }}" alt="PintarKuy" class="h-9 w-auto shrink-0">
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

        @php
            $dashHomeUrl = Auth::check()
                ? (Auth::user()->isGuru() ? route('guru.dashboard') : route('dashboard'))
                : route('login');
            $userFoto = Auth::check() ? \App\Support\UserFoto::src(Auth::user()->foto) : '';
        @endphp
        <div class="flex items-center gap-4" id="landingAuth">
            @auth
                <a href="{{ $dashHomeUrl }}" class="flex items-center gap-2 bg-white/10 ring-1 ring-white/20 pl-1.5 pr-4 py-1.5 rounded-full hover:bg-white/20 transition" title="Buka Dashboard">
                    <img src="{{ $userFoto }}" alt="" class="size-7 rounded-full object-cover">
                    <span class="text-white text-sm font-bold">{{ explode(' ', Auth::user()->name)[0] }}</span>
                </a>
            @else
                <a href="{{ route('login') }}" class="text-white text-sm font-semibold px-3 py-2 hover:text-navy-300 transition">Masuk</a>
                <a href="{{ route('register') }}" class="bg-white text-navy-950 text-sm font-semibold px-6 py-2.5 rounded-full shadow hover:bg-navy-50 transition">Daftar</a>
            @endauth
        </div>
    </div>
</header>