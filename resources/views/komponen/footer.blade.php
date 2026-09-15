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
                <p class="text-navy-400 text-xs leading-relaxed">Jakarta Selatan, DKI Jakarta<br>support@PintarKuy.id<br>+62 895-4240-1128</p>
                <div class="flex gap-3 pt-1">
                    <a href="https://www.instagram.com/pintarkuyid?stkn=dzJ2dWFnYjRpd2h1" target="_blank" rel="noopener" title="Instagram" class="flex items-center justify-center size-9 rounded-full bg-navy-950 hover:bg-navy-700 transition">
                        <svg class="size-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12.75A2.25 2.25 0 0 1 5.25 10.5h13.5A2.25 2.25 0 0 1 21 12.75v5.25a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 18V12.75Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M8.625 11.625v-2.25a2.625 2.625 0 0 1 5.25 0v2.25"/><circle cx="12" cy="14.625" r="2.625"/><path stroke-linecap="round" stroke-linejoin="round" d="M17.625 7.5h.008v.008h-.008V7.5Z"/></svg>
                    </a>
                    <a href="https://mail.google.com/mail/?view=cm&fs=1&to=pintarkuyid@gmail.com" target="_blank" rel="noopener" title="Email" class="flex items-center justify-center size-9 rounded-full bg-navy-950 hover:bg-navy-700 transition">
                        <svg class="size-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
                    </a>
                    <a href="https://wa.me/6281225124408" target="_blank" rel="noopener" title="WhatsApp" class="flex items-center justify-center size-9 rounded-full bg-navy-950 hover:bg-navy-700 transition">
                        <svg class="size-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38a9.9 9.9 0 0 0 4.74 1.21h.01c5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.83 9.83 0 0 0 12.04 2Zm0 18.15h-.01a8.2 8.2 0 0 1-4.19-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.19 8.19 0 0 1-1.26-4.38c0-4.54 3.7-8.23 8.25-8.23 2.2 0 4.27.86 5.82 2.42a8.18 8.18 0 0 1 2.41 5.83c0 4.54-3.7 8.23-8.23 8.23Zm4.52-6.16c-.25-.12-1.47-.72-1.69-.81-.23-.08-.39-.12-.56.13-.17.25-.64.81-.78.97-.14.17-.29.19-.54.06-.25-.12-1.05-.39-1.99-1.23-.74-.66-1.23-1.47-1.38-1.72-.14-.25-.02-.38.11-.51.11-.11.25-.29.37-.43.12-.14.17-.25.25-.41.08-.17.04-.31-.02-.43-.06-.12-.56-1.34-.76-1.84-.2-.48-.41-.42-.56-.43h-.48c-.17 0-.43.06-.66.31-.22.25-.86.85-.86 2.07 0 1.22.89 2.4 1.01 2.56.12.17 1.75 2.67 4.23 3.74.59.26 1.05.41 1.41.52.59.19 1.13.16 1.56.1.48-.07 1.47-.6 1.67-1.18.21-.58.21-1.07.15-1.18-.06-.1-.23-.16-.48-.29Z"/></svg>
                    </a>
                </div>
            </div>
        </div>
        <div class="border-t border-white/10 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-navy-400 text-xs">© {{ date('Y') }} PT PintarKuy Edukasi Indonesia. Hak Cipta Dilindungi.</p>
            <div class="flex gap-6">
                <span class="text-navy-400 text-xs">Kebijakan Privasi</span>
                <span class="text-navy-400 text-xs">Syarat &amp; Ketentuan</span>
            </div>
        </div>
    </div>
</footer>