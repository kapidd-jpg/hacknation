@extends('layout.app')

@section('title', 'Daftar - PintarKuy')

@section('content')
<div class="min-h-screen flex flex-col lg:flex-row">

    {{-- ============ LEFT: BRAND PANEL ============ --}}
    <div class="relative lg:flex-1 bg-gradient-navy overflow-hidden p-10 md:p-16 flex flex-col justify-between">
        <div class="absolute -top-32 -left-32 size-96 rounded-full bg-brand-greenlight/20 blur-3xl"></div>
        <div class="absolute bottom-10 right-0 size-[448px] rounded-full bg-brand-greenlight/15 blur-3xl"></div>

        <div class="relative flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <img src="{{ asset('assets/images/logopintar.png') }}" alt="PintarKuy" class="h-11 w-auto shrink-0">
                <div>
                    <p class="text-white font-semibold text-lg leading-tight">PintarKuy</p>
                    <p class="text-navy-300 text-[11px] font-bold tracking-widest uppercase">Bimbingan Belajar Adaptif</p>
                </div>
            </div>
            <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 text-navy-300 text-xs font-bold hover:text-white transition shrink-0">
                <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Beranda
            </a>
        </div>

        <div class="relative flex flex-col gap-4 max-w-xl py-10">
            <span class="inline-flex items-center gap-2 bg-white/10 backdrop-blur rounded-full px-3.5 py-1.5 w-fit shadow-sm">
                <span class="size-2.5 rounded-full bg-brand-greenlight"></span>
                <span class="text-brand-greenlight text-xs font-semibold tracking-wide">Platform Bimbingan UTBK & Belajar #1</span>
            </span>
            <h1 class="text-white text-4xl font-extrabold leading-tight tracking-tight">Mulai langkah pertamamu.</h1>
            <p class="text-navy-300/90 leading-relaxed">Buat akun gratis, lalu biarkan sistem adaptif kami menyusun rencana belajar yang sesuai targetmu.</p>

            <div class="relative rounded-2xl overflow-hidden shadow-2xl mt-2" style="background:linear-gradient(34deg, rgba(15,23,42,0.6) 0%, rgba(15,23,42,0) 100%); padding:22px 6px 6px;">
                <div class="relative aspect-video bg-navy-950 rounded-xl overflow-hidden">
                    <img src="{{ asset('assets/images/banner-auth.jpg') }}"
                         alt="Meja belajar dengan laptop menampilkan analitik kurikulum"
                         class="absolute inset-0 w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-navy-800/60 to-transparent"></div>
                    <div class="absolute left-4 right-4 bottom-4 bg-navy-800/85 backdrop-blur rounded-xl p-3 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="flex items-center justify-center size-8 rounded-lg bg-brand-greenlight/20">
                                <svg class="size-3.5 text-brand-greenlight" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                            </span>
                            <div>
                                <p class="text-white text-[11px] font-bold">Rencana Adaptif</p>
                                <p class="text-navy-300 text-xs">Asesmen awal 10 menit</p>
                            </div>
                        </div>
                        <span class="bg-brand-green/30 text-brand-greenlight text-xs font-semibold px-2.5 py-1 rounded-full">Mulai Gratis</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="relative flex items-center gap-2">
            <span class="h-0.5 w-4 bg-brand-greenlight"></span>
            <p class="text-navy-300/70 text-xs italic">Belajar lebih terarah. Berkembang lebih cepat. Solusi bimbel adaptif.</p>
        </div>
    </div>

    {{-- ============ RIGHT: REGISTER FORM ============ --}}
    <div class="lg:flex-1 bg-navy-50 flex items-center justify-center px-6 md:px-12 py-12">
        <div class="w-full max-w-2xl">
            <span class="inline-block bg-navy-100 text-navy-950 text-[11px] font-bold tracking-widest uppercase px-2.5 py-1 rounded">Pendaftaran Siswa Baru</span>
            <h2 class="text-navy-950 text-2xl font-bold mt-2">Buat Akun</h2>
            <p class="text-ink-soft text-sm mt-1 mb-6">Daftar sekali, langsung dapatkan rencana belajar adaptif pribadimu.</p>

            @if ($errors->any())
                <div class="mb-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('register.attempt') }}" id="daftarForm" class="flex flex-col gap-5">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="nama" class="text-navy-900 text-sm font-semibold">Nama Lengkap</label>
                        <div class="relative mt-2">
                            <svg class="absolute left-4 top-1/2 -translate-y-1/2 size-4 text-ink-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <input id="nama" name="name" type="text" required autofocus value="{{ old('name') }}"
                                   placeholder="Nama lengkap kamu"
                                   class="w-full bg-white rounded-xl shadow-sm pl-12 pr-4 py-4 text-sm text-navy-900 placeholder:text-ink-muted focus:outline-none focus:ring-2 focus:ring-navy-800">
                        </div>
                    </div>

                    <div>
                        <label for="email" class="text-navy-900 text-sm font-semibold">Email</label>
                        <div class="relative mt-2">
                            <svg class="absolute left-4 top-1/2 -translate-y-1/2 size-4 text-ink-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <input id="email" name="email" type="email" required value="{{ old('email') }}"
                                   placeholder="nama@email.com"
                                   class="w-full bg-white rounded-xl shadow-sm pl-12 pr-4 py-4 text-sm text-navy-900 placeholder:text-ink-muted focus:outline-none focus:ring-2 focus:ring-navy-800">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="text-navy-900 text-sm font-semibold">Password</label>
                        <div class="relative mt-2">
                            <svg class="absolute left-4 top-1/2 -translate-y-1/2 size-4 text-ink-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            <input id="password" name="password" type="password" required
                                   placeholder="Minimal 8 karakter"
                                   class="w-full bg-white rounded-xl shadow-sm pl-12 pr-12 py-4 text-sm text-navy-900 placeholder:text-ink-muted focus:outline-none focus:ring-2 focus:ring-navy-800">
                            <button type="button" onclick="const i=document.getElementById('password'); i.type = i.type==='password' ? 'text' : 'password';"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 p-1.5 rounded-lg text-ink-muted hover:bg-navy-50">
                                <svg class="size-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label for="password_confirmation" class="text-navy-900 text-sm font-semibold">Konfirmasi Password</label>
                        <div class="relative mt-2">
                            <svg class="absolute left-4 top-1/2 -translate-y-1/2 size-4 text-ink-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.62-4.13A11.14 11.14 0 0012 2 11.14 11.14 0 004.38 7.87L12 21l7.62-13.13z"/></svg>
                            <input id="password_confirmation" name="password_confirmation" type="password" required
                                   placeholder="Ulangi password kamu"
                                   class="w-full bg-white rounded-xl shadow-sm pl-12 pr-12 py-4 text-sm text-navy-900 placeholder:text-ink-muted focus:outline-none focus:ring-2 focus:ring-navy-800">
                            <button type="button" onclick="const i=document.getElementById('password_confirmation'); i.type = i.type==='password' ? 'text' : 'password';"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 p-1.5 rounded-lg text-ink-muted hover:bg-navy-50">
                                <svg class="size-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="sekolah" class="text-navy-900 text-sm font-semibold">Sekolah / Instansi</label>
                        <div class="relative mt-2">
                            <svg class="absolute left-4 top-1/2 -translate-y-1/2 size-4 text-ink-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M4 21V10m6 11V10m4 11V10m6 11V10M2 10l10-7 10 7M12 3v2m-4 1v1m8-1v1"/></svg>
                            <input id="sekolah" name="sekolah" type="text" required autocomplete="organization" value="{{ old('sekolah') }}"
                                   placeholder="Nama SMA/SMK/Mahasiswa kamu"
                                   class="w-full bg-white rounded-xl shadow-sm pl-12 pr-4 py-4 text-sm text-navy-900 placeholder:text-ink-muted focus:outline-none focus:ring-2 focus:ring-navy-800">
                        </div>
                    </div>

                    <div>
                        <label for="kelas_jurusan" class="text-navy-900 text-sm font-semibold">Tingkat Kelas & Jurusan</label>
                        <div class="relative mt-2">
                            <svg class="absolute left-4 top-1/2 -translate-y-1/2 size-4 text-ink-muted pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            <select id="kelas_jurusan" name="kelas_jurusan" required
                                    class="w-full bg-white rounded-xl shadow-sm pl-12 pr-10 py-4 text-sm text-navy-900 placeholder:text-ink-muted focus:outline-none focus:ring-2 focus:ring-navy-800 appearance-none">
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
                    <input type="checkbox" name="syarat" required @checked(old('syarat')) class="size-4 mt-0.5 rounded-[3px] border-ink-muted text-navy-800 focus:ring-navy-800">
                    <span class="text-ink-soft text-sm">Saya menyetujui <span class="text-navy-950 font-semibold underline">Ketentuan Layanan</span> & <span class="text-navy-950 font-semibold underline">Kebijakan Privasi</span> PintarKuy.</span>
                </label>

                <button type="submit" class="w-full bg-gradient-brand text-white font-semibold text-sm rounded-xl px-6 py-3.5 shadow-md hover:brightness-110 transition flex items-center justify-center gap-2">
                    Daftar Sekarang
                    <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </button>
            </form>

            <div class="relative flex items-center justify-center my-7">
                <span class="absolute inset-0 flex items-center"><span class="w-full border-t border-navy-100"></span></span>
                <span class="relative bg-navy-50 px-4 text-ink-soft text-xs font-semibold tracking-widest uppercase">Atau</span>
            </div>

            <div class="grid grid-cols-2 gap-3 hidden">
                <button type="button" title="Segera hadir" class="bg-white shadow-sm rounded-xl py-3 flex items-center justify-center gap-2.5 hover:bg-navy-50 transition">
                    <svg class="size-4" viewBox="0 0 24 24"><path fill="#4285F4" d="M23.49 12.27c0-.79-.07-1.54-.19-2.27H12v4.51h6.47c-.29 1.48-1.14 2.73-2.4 3.58v3h3.86c2.26-2.09 3.56-5.17 3.56-8.82z"/><path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.86-3c-1.08.72-2.45 1.16-4.07 1.16-3.13 0-5.78-2.11-6.73-4.96H1.29v3.09C3.26 21.3 7.31 24 12 24z"/><path fill="#FBBC05" d="M5.27 14.29c-.25-.72-.38-1.49-.38-2.29s.14-1.57.38-2.29V6.62H1.29A11.96 11.96 0 000 12c0 1.93.46 3.76 1.29 5.38l3.98-3.09z"/><path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.31 0 3.26 2.7 1.29 6.62l3.98 3.09c.95-2.85 3.6-4.96 6.73-4.96z"/></svg>
                    <span class="text-navy-900 text-sm font-semibold">Google</span>
                </button>
                <button type="button" title="Segera hadir" class="bg-white shadow-sm rounded-xl py-3 flex items-center justify-center gap-2.5 hover:bg-navy-50 transition">
                    <svg class="size-4" viewBox="0 0 24 24"><rect width="11" height="11" x="1" y="1" fill="#f25022"/><rect width="11" height="11" x="12" y="1" fill="#7fba00"/><rect width="11" height="11" x="1" y="12" fill="#00a4ef"/><rect width="11" height="11" x="12" y="12" fill="#ffb900"/></svg>
                    <span class="text-navy-900 text-sm font-semibold">Microsoft</span>
                </button>
            </div>
            <p class="text-center text-ink-muted text-xs">Daftar dengan Google & Microsoft segera hadir.</p>

            <p class="text-center text-sm text-ink-soft mt-6">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-navy-950 font-semibold">Masuk</a>
            </p>
            <p class="text-center text-ink-muted text-xs leading-relaxed mt-6">
                Dengan mendaftar, kamu setuju untuk mengikuti program secara adil tanpa akun ganda.
            </p>
        </div>
    </div>
</div>
@endsection

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