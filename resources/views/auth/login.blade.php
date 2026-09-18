@extends('layout.app')

@section('title', 'Masuk - PintarKuy')

@section('content')

<main class="relative w-full bg-paper paper-bg h-screen overflow-y-auto lg:overflow-y-hidden">
    <a href="{{ route('home') }}" class="absolute left-4 md:left-8 top-4 md:top-6 inline-flex items-center gap-1.5 bg-whitewarm text-ink text-xs font-bold border border-ink/25 rounded-sm px-3 py-2 shadow-[1px_1px_0px_0px_#101A2E] hover:border-ink hover:shadow-[2px_2px_0px_0px_#101A2E] hover:bg-gold hover:border-gold transition-all">
        <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Kembali ke Beranda
    </a>

    <div class="h-full flex px-4 md:px-margin py-12">

        <div class="w-full max-w-md m-auto bg-whitewarm border border-ink rounded-sm shadow-[2px_2px_0px_0px_#101A2E] p-8 lg:p-10">

            <span class="lb-code text-teal font-semibold uppercase tracking-wider">Portal Masuk</span>
            <h1 class="lb-headline-md text-ink font-bold mt-2">Selamat datang kembali.</h1>
            <p class="lb-body-md text-ink-soft mt-1 mb-6">Masuk untuk lanjutkan langkah belajarmu hari ini.</p>

            @if ($errors->any())
                <div class="mb-5 rounded-sm bg-gold-50 border border-gold-600 text-brick-800 text-sm px-4 py-3">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.attempt') }}" id="loginForm" class="flex flex-col gap-5">
                @csrf

                <div>
                    <label for="email" class="lb-label text-ink font-bold">Email atau Username</label>
                    <div class="relative mt-2">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 size-4 text-ink-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <input id="email" name="email" type="text" required autofocus value="{{ old('email') }}"
                               placeholder="nama@email.com atau username"
                               class="w-full bg-whitewarm rounded-[2px] border border-ink/20 pl-12 pr-4 py-3.5 text-sm text-ink placeholder:text-ink-muted focus:outline-none focus:ring-2 focus:ring-ink focus:border-ink transition focus:shadow-[2px_2px_0_0_#101A2E]">
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between">
                        <label for="password" class="lb-label text-ink font-bold">Password</label>
                        <a href="https://wa.me/6282135523130?text=Halo%20admin%20PintarKuy%2C%20saya%20lupa%20password%20akun%20saya.%20Mohon%20dibantu%20reset%20password.%20Terima%20kasih." target="_blank" rel="noopener" class="text-gold-600 text-[11px] font-bold hover:text-brick hover:underline underline-offset-2 transition-colors">Lupa password? Hubungi admin.</a>
                    </div>
                    <div class="relative mt-2">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 size-4 text-ink-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        <input id="password" name="password" type="password" required
                               placeholder="Masukkan password kamu"
                               class="w-full bg-whitewarm rounded-[2px] border border-ink/20 pl-12 pr-12 py-3.5 text-sm text-ink placeholder:text-ink-muted focus:outline-none focus:ring-2 focus:ring-ink focus:border-ink transition focus:shadow-[2px_2px_0_0_#101A2E]">
                        <button type="button" onclick="const i=document.getElementById('password'); i.type = i.type==='password' ? 'text' : 'password';"
                                class="absolute right-3 top-1/2 -translate-y-1/2 p-1.5 rounded-sm text-ink-muted hover:bg-paper-100">
                            <svg class="size-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </button>
                    </div>
                </div>

                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="remember" @checked(old('remember')) class="size-4 rounded-[2px] border-ink-muted text-ink focus:ring-ink">
                    <span class="lb-body-md text-ink-soft">Ingat saya di perangkat ini</span>
                </label>

                <button type="submit" class="w-full bg-ink text-paper lb-label-md font-semibold rounded-sm border border-ink px-6 py-3.5 shadow-[2px_2px_0px_0px_#101A2E] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[1px_1px_0px_0px_#101A2E] transition-all flex items-center justify-center gap-2">
                    Masuk
                    <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </button>
            </form>

            <div class="mt-6 pt-4 border-t border-ink/10 text-center">
                <p class="text-sm text-ink-soft">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="text-ink font-bold underline decoration-gold/60 underline-offset-4 hover:text-brick transition-colors">Daftar</a>
                </p>
            </div>

            <p class="text-center text-ink-muted text-xs leading-relaxed mt-4">
                Dengan masuk, kamu menyetujui <span class="underline">Ketentuan Layanan</span> & <span class="underline">Kebijakan Privasi</span> PintarKuy.
            </p>
        </div>
    </div>
</main>

@endsection

@push('styles')
    @vite(['resources/css/halaman/landing.css'])
@endpush