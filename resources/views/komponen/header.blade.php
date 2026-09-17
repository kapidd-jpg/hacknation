@php
    $routeName = request()->route() ? request()->route()->getName() : '';
    $dashHomeUrl = Auth::check()
        ? (Auth::user()->isGuru() ? route('guru.dashboard') : route('dashboard'))
        : route('login');
    $dashKatalogUrl = Auth::check()
        ? (Auth::user()->isGuru() ? route('guru.dashboard') : route('dashboard.katalog'))
        : route('register');
    $userFoto = Auth::check() ? \App\Support\UserFoto::src(Auth::user()->foto) : '';
    $userNama = Auth::user()?->name ?? '';
@endphp
<header class="site-header" id="siteHeader">
    <div class="site-nav-wrap">
        <div class="site-nav-shell" id="siteNavShell">
            <a href="{{ url('/') }}" class="site-brand" title="PintarKuy">
                <img src="{{ asset('assets/images/logopintar.png') }}" alt="PintarKuy" class="site-brand-logo">
                <span class="site-brand-name">PintarKuy</span>
            </a>

            <nav class="site-nav-links" aria-label="Navigasi utama">
                @foreach ([
                    ['Beranda', 'home', url('/')],
                    ['Tentang', 'about', route('about')],
                    ['Kelas', 'classes', route('classes')],
                    ['Kontak', 'contact', route('contact')],
                ] as [$label, $name, $href])
                    <a href="{{ $href }}" class="site-nav-link {{ $routeName === $name ? 'is-active' : '' }}">{{ $label }}</a>
                @endforeach
            </nav>

            <div class="site-nav-actions" id="landingAuth">
                @auth
                    <a href="{{ $dashHomeUrl }}" class="site-userchip" title="Buka Dashboard">
                        <span class="relative size-7 shrink-0">
                            <img src="{{ $userFoto }}" data-user-photo alt="" class="absolute inset-0 size-7 rounded-full object-cover {{ Auth::user()->foto ? '' : 'hidden' }}">
                            <span data-user-initials class="absolute inset-0 size-7 rounded-full {{ Auth::user()->foto ? 'hidden' : '' }}">{{ \App\Support\UserFoto::initials($userNama) }}</span>
                        </span>
                        <span class="site-userchip-name">{{ explode(' ', $userNama)[0] }}</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="site-btn site-btn--ghost">Masuk</a>
                    <a href="{{ route('register') }}" class="site-btn site-btn--solid">Daftar Sekarang</a>
                @endauth
            </div>

            <button class="site-nav-toggle" id="siteNavToggle" type="button" aria-expanded="false" aria-controls="siteNavMenu" aria-label="Buka menu">
                <span></span><span></span><span></span>
            </button>
        </div>

        <nav class="site-nav-menu" id="siteNavMenu" aria-label="Menu mobile">
            @foreach ([
                ['Beranda', 'home', url('/')],
                ['Tentang', 'about', route('about')],
                ['Kelas', 'classes', route('classes')],
                ['Kontak', 'contact', route('contact')],
            ] as [$label, $name, $href])
                <a href="{{ $href }}" class="site-nav-menu-link {{ $routeName === $name ? 'is-active' : '' }}">{{ $label }}</a>
            @endforeach
            @auth
                <a href="{{ $dashHomeUrl }}" class="site-nav-menu-cta">Dashboard</a>
            @else
                <a href="{{ route('register') }}" class="site-nav-menu-cta">Daftar Sekarang</a>
                <a href="{{ route('login') }}" class="site-nav-menu-login">Masuk</a>
            @endauth
        </nav>
        <div class="site-nav-overlay" id="siteNavOverlay"></div>
    </div>
</header>

@push('scripts')
<script>
(function(){
    var header=document.getElementById('siteHeader'),
        toggle=document.getElementById('siteNavToggle'),
        overlay=document.getElementById('siteNavOverlay'),
        menu=document.getElementById('siteNavMenu');
    function onScroll(){ if(header) header.classList.toggle('is-scrolled',window.scrollY>8); }
    function closeNav(){ document.body.classList.remove('nav-open'); if(toggle) toggle.setAttribute('aria-expanded','false'); }
    window.addEventListener('scroll',onScroll,{passive:true}); onScroll();
    if(toggle) toggle.addEventListener('click',function(){ document.body.classList.toggle('nav-open'); toggle.setAttribute('aria-expanded',document.body.classList.contains('nav-open')?'true':'false'); });
    if(overlay) overlay.addEventListener('click',closeNav);
    if(menu) menu.addEventListener('click',function(e){ if(e.target.closest('a')) closeNav(); });
    document.addEventListener('keydown',function(e){ if(e.key==='Escape') closeNav(); });
})();
</script>
@endpush