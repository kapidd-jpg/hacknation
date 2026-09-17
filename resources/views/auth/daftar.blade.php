@extends('layout.app')

@section('title', 'Daftar - PintarKuy')

@section('content')

<main class="relative w-full bg-paper paper-bg h-screen overflow-y-auto lg:overflow-y-hidden">
    <a href="{{ route('home') }}" class="absolute left-4 md:left-8 top-4 md:top-6 inline-flex items-center gap-1.5 bg-whitewarm text-ink text-xs font-bold border border-ink/25 rounded-sm px-3 py-2 shadow-[1px_1px_0px_0px_#101A2E] hover:border-ink hover:shadow-[2px_2px_0px_0px_#101A2E] hover:bg-gold hover:border-gold transition-all">
        <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Kembali ke Beranda
    </a>

    <div class="h-full flex px-4 md:px-margin py-12">

        <div class="w-full max-w-2xl m-auto bg-whitewarm border border-ink rounded-sm shadow-[2px_2px_0px_0px_#101A2E] p-8 lg:p-10">

            <span class="lb-code text-teal font-semibold uppercase tracking-wider">Pendaftaran Siswa Baru</span>
            <h1 class="lb-headline-md text-ink font-bold mt-2">Buat akunmu.</h1>
            <p class="lb-body-md text-ink-soft mt-1 mb-6">Daftar sekali, langsung dapatkan rencana belajar adaptif pribadimu.</p>

            @if ($errors->any())
                <div class="mb-5 rounded-sm bg-gold-50 border border-gold-600 text-brick-800 text-sm px-4 py-3">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('register.attempt') }}" id="daftarForm" class="flex flex-col gap-5">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="nama" class="lb-label text-ink font-bold">Nama Lengkap</label>
                        <div class="relative mt-2">
                            <svg class="absolute left-4 top-1/2 -translate-y-1/2 size-4 text-ink-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <input id="nama" name="name" type="text" required autofocus value="{{ old('name') }}"
                                   placeholder="Nama lengkap kamu"
                                   class="w-full bg-whitewarm rounded-[2px] border border-ink/20 pl-12 pr-4 py-3.5 text-sm text-ink placeholder:text-ink-muted focus:outline-none focus:ring-2 focus:ring-ink focus:border-ink transition focus:shadow-[2px_2px_0_0_#101A2E]">
                        </div>
                    </div>

                    <div>
                        <label for="email" class="lb-label text-ink font-bold">Email</label>
                        <div class="relative mt-2">
                            <svg class="absolute left-4 top-1/2 -translate-y-1/2 size-4 text-ink-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <input id="email" name="email" type="email" required value="{{ old('email') }}"
                                   placeholder="nama@email.com"
                                   class="w-full bg-whitewarm rounded-[2px] border border-ink/20 pl-12 pr-4 py-3.5 text-sm text-ink placeholder:text-ink-muted focus:outline-none focus:ring-2 focus:ring-ink focus:border-ink transition focus:shadow-[2px_2px_0_0_#101A2E]">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="lb-label text-ink font-bold">Password</label>
                        <div class="relative mt-2">
                            <svg class="absolute left-4 top-1/2 -translate-y-1/2 size-4 text-ink-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            <input id="password" name="password" type="password" required
                                   placeholder="Minimal 8 karakter"
                                   class="w-full bg-whitewarm rounded-[2px] border border-ink/20 pl-12 pr-12 py-3.5 text-sm text-ink placeholder:text-ink-muted focus:outline-none focus:ring-2 focus:ring-ink focus:border-ink transition focus:shadow-[2px_2px_0_0_#101A2E]">
                            <button type="button" onclick="const i=document.getElementById('password'); i.type = i.type==='password' ? 'text' : 'password';"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 p-1.5 rounded-sm text-ink-muted hover:bg-paper-100">
                                <svg class="size-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label for="password_confirmation" class="lb-label text-ink font-bold">Konfirmasi Password</label>
                        <div class="relative mt-2">
                            <svg class="absolute left-4 top-1/2 -translate-y-1/2 size-4 text-ink-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.62-4.13A11.14 11.14 0 0012 2 11.14 11.14 0 004.38 7.87L12 21l7.62-13.13z"/></svg>
                            <input id="password_confirmation" name="password_confirmation" type="password" required
                                   placeholder="Ulangi password kamu"
                                   class="w-full bg-whitewarm rounded-[2px] border border-ink/20 pl-12 pr-12 py-3.5 text-sm text-ink placeholder:text-ink-muted focus:outline-none focus:ring-2 focus:ring-ink focus:border-ink transition focus:shadow-[2px_2px_0_0_#101A2E]">
                            <button type="button" onclick="const i=document.getElementById('password_confirmation'); i.type = i.type==='password' ? 'text' : 'password';"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 p-1.5 rounded-sm text-ink-muted hover:bg-paper-100">
                                <svg class="size-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="sekolah" class="lb-label text-ink font-bold">Sekolah / Instansi</label>
                        <div class="relative mt-2">
                            <svg class="absolute left-4 top-1/2 -translate-y-1/2 size-4 text-ink-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M4 21V10m6 11V10m4 11V10m6 11V10M2 10l10-7 10 7M12 3v2m-4 1v1m8-1v1"/></svg>
                            <input id="sekolah" name="sekolah" type="text" required autocomplete="organization" value="{{ old('sekolah') }}"
                                   placeholder="Nama SMA/SMK/Mahasiswa kamu"
                                   class="w-full bg-whitewarm rounded-[2px] border border-ink/20 pl-12 pr-4 py-3.5 text-sm text-ink placeholder:text-ink-muted focus:outline-none focus:ring-2 focus:ring-ink focus:border-ink transition focus:shadow-[2px_2px_0_0_#101A2E]">
                        </div>
                    </div>

                    <div>
                        <label for="kelas_jurusan" class="lb-label text-ink font-bold">Tingkat Kelas & Jurusan</label>
                        <div class="relative mt-2">
                            <svg class="absolute left-4 top-1/2 -translate-y-1/2 size-4 text-ink-muted pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            <select id="kelas_jurusan" name="kelas_jurusan" required
                                    class="w-full bg-whitewarm rounded-[2px] border border-ink/20 pl-12 pr-10 py-3.5 text-sm text-ink placeholder:text-ink-muted focus:outline-none focus:ring-2 focus:ring-ink focus:border-ink transition appearance-none focus:shadow-[2px_2px_0_0_#101A2E]">
                                <option value="" disabled {{ old('kelas_jurusan') ? '' : 'selected' }}>Pilih kelas kamu...</option>
                                @foreach (['Kelas 10 · IPA/IPS', 'Kelas 11 · IPA', 'Kelas 11 · IPS', 'Kelas 12 · IPA', 'Kelas 12 · IPS', 'SMK', 'Mahasiswa / Umum', 'Lainnya'] as $opt)
                                    <option value="{{ $opt }}" {{ old('kelas_jurusan') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                @endforeach
                            </select>
                            <svg class="absolute right-4 top-1/2 -translate-y-1/2 size-4 text-ink-muted pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                </div>

                <label class="flex items-start gap-3 cursor-pointer">
                    <input type="checkbox" name="syarat" required @checked(old('syarat')) class="size-4 mt-0.5 rounded-[2px] border-ink-muted text-ink focus:ring-ink">
                    <span class="lb-body-md text-ink-soft">Saya menyetujui <span class="text-ink font-semibold underline">Ketentuan Layanan</span> & <span class="text-ink font-semibold underline">Kebijakan Privasi</span> PintarKuy.</span>
                </label>

                <button type="submit" class="w-full bg-ink text-paper lb-label-md font-semibold rounded-sm border border-ink px-6 py-3.5 shadow-[2px_2px_0px_0px_#101A2E] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[1px_1px_0px_0px_#101A2E] transition-all flex items-center justify-center gap-2">
                    Daftar Sekarang
                    <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </button>
            </form>

            <div class="mt-6 pt-4 border-t border-ink/10 text-center">
                <p class="text-sm text-ink-soft">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="text-ink font-bold underline decoration-gold/60 underline-offset-4 hover:text-brick transition-colors">Masuk</a>
                </p>
            </div>

            <p class="text-center text-ink-muted text-xs leading-relaxed mt-4">
                Dengan mendaftar, kamu setuju untuk mengikuti program secara adil tanpa akun ganda.
            </p>
        </div>
    </div>
</main>

@endsection

@push('styles')
    @vite(['resources/css/halaman/landing.css'])
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const auth = window.pintarKuyAuth;
            const form = document.getElementById('daftarForm');
            if (!form) return;
            form.addEventListener('submit', () => {
                const nama = document.getElementById('nama');
                const email = document.getElementById('email');
                const sekolah = document.getElementById('sekolah');
                const kelasJurusan = document.getElementById('kelas_jurusan');
                if (auth) auth.login({
                    name: (nama && nama.value.trim()) ? nama.value.trim() : '',
                    email: email ? email.value.trim() : '',
                    sekolah: sekolah ? sekolah.value.trim() : '',
                    kelas_jurusan: kelasJurusan ? kelasJurusan.value : '',
                    role: 'siswa',
                });
            });
        });
    </script>
@endpush