<footer class="bg-navy-800">
    <div class="w-full px-6 md:px-12 pt-16 pb-12 flex flex-col gap-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
            <div class="flex flex-col gap-4">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('assets/images/logopintar.png') }}" alt="PintarKuy" class="h-9 w-auto">
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