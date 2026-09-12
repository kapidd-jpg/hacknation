<footer class="bg-navy-800">
    <div class="w-full px-6 md:px-12 pt-16 pb-12 flex flex-col gap-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
            <div class="flex flex-col gap-4">
                <div class="flex items-center gap-3">
                    <span class="flex items-center justify-center size-12 rounded-xl bg-gradient-to-br from-brand-green to-navy-950 shadow-lg ring-1 ring-white/20 shrink-0">
                        <svg class="size-7 text-brand-greenlight" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.25278V19.25M12 6.25278C10.8321 5.47686 9.24649 5 7.5 5C5.75351 5 4.16789 5.47686 3 6.25278V19.25C4.16789 18.4741 5.75351 18 7.5 18C9.24649 18 10.8321 18.4741 12 19.25M12 6.25278C13.1679 5.47686 14.7535 5 16.5 5C18.2465 5 19.8321 5.47686 21 6.25278V19.25C19.8321 18.4741 18.2465 18 16.5 18C14.7535 18 13.1679 18.4741 12 19.25"/></svg>
                    </span>
                    <span class="text-white font-bold text-xl">PintarKuy</span>
                </div>
                <p class="text-navy-400 text-xs leading-relaxed">Platform bimbingan belajar digital terdepan untuk persiapan UTBK-SNBT dan ujian sekolah dengan teknologi adaptif berstandar nasional.</p>
            </div>
            <div class="flex flex-col gap-3">
                <h4 class="text-white text-sm font-semibold tracking-widest uppercase">Navigasi</h4>
                <a href="{{ url('/') }}" class="text-navy-400 text-sm hover:text-white transition">Beranda</a>
                <a href="{{ route('about') }}" class="text-navy-400 text-sm hover:text-white transition">Tentang Kami</a>
                <a href="{{ route('classes') }}" class="text-navy-400 text-sm hover:text-white transition">Program Kelas</a>
                <a href="{{ route('contact') }}" class="text-navy-400 text-sm hover:text-white transition">Hubungi Kami</a>
            </div>
            <div class="flex flex-col gap-3">
                <h4 class="text-white text-sm font-semibold tracking-widest uppercase">Program Unggulan</h4>
                <a href="{{ route('classes') }}" class="text-navy-400 text-sm hover:text-white transition">Simulasi UTBK/SNBT</a>
                <a href="{{ route('classes') }}" class="text-navy-400 text-sm hover:text-white transition">Bimbel Intensif SMA</a>
                <a href="{{ route('classes') }}" class="text-navy-400 text-sm hover:text-white transition">Bank Soal & Pembahasan</a>
                <a href="{{ route('classes') }}" class="text-navy-400 text-sm hover:text-white transition">Live Tutoring Interaktif</a>
            </div>
            <div class="flex flex-col gap-3">
                <h4 class="text-white text-sm font-semibold tracking-widest uppercase">Hubungi Kami</h4>
                <p class="text-navy-400 text-xs leading-relaxed">Jakarta Selatan, DKI Jakarta<br>support@PintarKuy.id<br>+62 (021) 8899-2345</p>
                <div class="flex gap-3 pt-1">
                    @for ($i = 0; $i < 3; $i++)
                        <span class="flex items-center justify-center size-9 rounded-full bg-navy-950"></span>
                    @endfor
                </div>
            </div>
        </div>
        <div class="border-t border-white/10 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-navy-400 text-xs">© {{ date('Y') }} PT PintarKuy Edukasi Indonesia. Hak Cipta Dilindungi.</p>
            <div class="flex gap-6">
                <a href="#" class="text-navy-400 text-xs hover:text-white transition">Kebijakan Privasi</a>
                <a href="#" class="text-navy-400 text-xs hover:text-white transition">Syarat & Ketentuan</a>
            </div>
        </div>
    </div>
</footer>