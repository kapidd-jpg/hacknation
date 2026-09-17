@extends('layout.app')

@section('title', 'Masuk - PintarKuy')

@section('content')
<div class="min-h-screen flex flex-col lg:flex-row">

    {{-- ============ LEFT: BRAND PANEL (navy ledger) ============ --}}
    <div class="relative lg:flex-1 bg-ink overflow-hidden p-6 md:p-16 flex flex-col justify-between">
        <div class="absolute inset-0 pointer-events-none" style="background-image:repeating-linear-gradient(0deg, rgba(253,253,249,0.04) 0, rgba(253,253,249,0.04) 1px, transparent 1px, transparent 27px);"></div>

        <div class="relative flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <img src="{{ asset('assets/images/logopintar.png') }}" alt="PintarKuy" class="h-11 w-auto shrink-0">
                <div>
                    <p class="text-whitewarm font-display font-bold text-lg leading-tight">PintarKuy</p>
                    <p class="text-white/50 text-[11px] font-bold tracking-widest uppercase">Bimbingan Belajar Adaptif</p>
                </div>
            </div>
            <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 text-white/60 text-xs font-bold hover:text-white transition shrink-0">
                <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Beranda
            </a>
        </div>

        <div class="relative flex flex-col gap-5 max-w-xl py-10">
            <span class="inline-flex items-center gap-2 bg-gold/15 border border-gold/50 rounded-sm px-3.5 py-1.5 w-fit">
                <span class="size-2 rounded-full bg-gold"></span>
                <span class="text-gold-100 text-xs font-bold tracking-wide">Platform Bimbingan UTBK & Belajar #1</span>
            </span>
            <h1 class="font-display text-whitewarm text-4xl md:text-5xl font-bold leading-[1.05] tracking-tight">
                Selamat datang <em class="text-gold-100 not-italic">kembali.</em>
            </h1>
            <p class="text-white/60 leading-relaxed">Lanjutkan langkah belajarmu hari ini menuju kampus tujuannya, bersama PintarKuy.</p>

            <div class="relative rounded-sm overflow-hidden mt-2 p-1 bg-white/[0.06] border border-white/15">
                <div class="relative aspect-video bg-navy-800 rounded-[2px] overflow-hidden">
                    <img src="{{ asset('assets/images/banner-auth.jpg') }}"
                         alt="Meja belajar dengan laptop menampilkan analitik kurikulum"
                         class="absolute inset-0 w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-ink/80 via-ink/20 to-transparent"></div>
                    <div class="absolute left-3 right-3 bottom-3 bg-ink/70 backdrop-blur rounded-sm p-3 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <span class="flex items-center justify-center size-8 rounded-sm bg-gold/25 text-gold-100">
                                <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                            </span>
                            <div>
                                <p class="text-whitewarm text-[11px] font-bold">Target SNBT 2026</p>
                                <p class="text-white/50 text-xs">Simulasi Tryout Nasional ke-4</p>
                            </div>
                        </div>
                        <span class="bg-gold text-ink text-xs font-bold px-2.5 py-1 rounded-[2px] shrink-0">Simulasi Nasional</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="relative flex items-center gap-3">
            <span class="h-0.5 w-8 bg-gold"></span>
            <p class="text-white/50 text-xs">Belajar lebih terarah. Berkembang lebih cepat. Solusi bimbel adaptif.</p>
        </div>
    </div>

    {{-- ============ RIGHT: AUTH FORM ============ --}}
    <div class="lg:flex-1 bg-paper flex items-center justify-center px-6 md:px-24 py-12">
        <div class="w-full max-w-md">
            <span class="inline-block bg-gold-100 text-ink font-display text-[11px] font-bold tracking-widest uppercase px-3 py-1.5 rounded-sm border border-ink">Portal Masuk</span>
            <h2 class="font-display text-ink text-3xl font-bold mt-4">Masuk</h2>
            <p class="text-ink-soft text-sm mt-1 mb-6">Silakan masukkan detail akun PintarKuy kamu.</p>

            @if ($errors->any())
                <div class="mb-4 rounded-sm bg-gold-50 border border-gold-600 text-brick-800 text-sm px-4 py-3">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.attempt') }}" id="loginForm" class="flex flex-col gap-5">
                @csrf

                <div>
                    <label for="email" class="text-ink text-sm font-bold">Email atau Username</label>
                    <div class="relative mt-2">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 size-4 text-ink-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <input id="email" name="email" type="text" required autofocus value="{{ old('email') }}"
                               placeholder="nama@email.com atau username"
                               class="w-full bg-whitewarm rounded-[2px] border border-ink/20 pl-12 pr-4 py-3.5 text-sm text-ink placeholder:text-ink-muted focus:outline-none focus:ring-2 focus:ring-brick focus:border-brick transition focus:shadow-[2px_2px_0_0_#101A2E]">
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between">
                        <label for="password" class="text-ink text-sm font-bold">Password</label>
                        <span class="text-gold-600 text-[11px] font-bold">Lupa password? Hubungi admin.</span>
                    </div>
                    <div class="relative mt-2">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 size-4 text-ink-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        <input id="password" name="password" type="password" required
                               placeholder="Masukkan password kamu"
                               class="w-full bg-whitewarm rounded-[2px] border border-ink/20 pl-12 pr-12 py-3.5 text-sm text-ink placeholder:text-ink-muted focus:outline-none focus:ring-2 focus:ring-brick focus:border-brick transition focus:shadow-[2px_2px_0_0_#101A2E]">
                        <button type="button" onclick="const i=document.getElementById('password'); i.type = i.type==='password' ? 'text' : 'password';"
                                class="absolute right-3 top-1/2 -translate-y-1/2 p-1.5 rounded-sm text-ink-muted hover:bg-paper-100">
                            <svg class="size-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </button>
                    </div>
                </div>

                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="remember" @checked(old('remember')) class="size-4 rounded-[2px] border-ink-muted text-brick focus:ring-brick">
                    <span class="text-ink-soft text-sm">Ingat saya di perangkat ini</span>
                </label>

                <button type="submit" class="w-full bg-brick text-whitewarm font-display font-bold text-sm rounded-[2px] px-6 py-3.5 shadow-[3px_3px_0_0_#E3A23B] hover:bg-brick-600 hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[2px_2px_0_0_#E3A23B] transition flex items-center justify-center gap-2">
                    Masuk
                    <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </button>
            </form>

            <p class="text-center text-ink-muted text-xs leading-relaxed mt-6">
                Dengan masuk, kamu menyetujui <span class="underline">Ketentuan Layanan</span> & <span class="underline">Kebijakan Privasi</span> PintarKuy.
            </p>
        </div>
    </div>
</div>
@endsection