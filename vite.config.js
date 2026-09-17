import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/halaman/site.css',
                'resources/css/halaman/tentang.css',
                'resources/css/halaman/kelas.css',
                'resources/css/halaman/kontak.css',
                'resources/css/halaman/landing.css',
                'resources/js/halaman/tentang.js',
                'resources/js/halaman/landing.js',
                'resources/js/halaman/kelas.js',
                'resources/js/halaman/kontak.js',
                'resources/css/dashboard/site.css',
                'resources/css/dashboard/kelas.css',
                'resources/css/dashboard/materi.css',
                'resources/css/dashboard/katalog.css',
                'resources/css/dashboard/nilai.css',
                'resources/css/dashboard/laporan.css',
                'resources/css/dashboard/pengaturan.css',
                'resources/css/dashboard/guru.css',
                'resources/css/dashboard/latsol.css',
                'resources/css/dashboard/paket.css',
                'resources/js/dashboard/kelas.js',
                'resources/js/dashboard/materi.js',
                'resources/js/dashboard/katalog.js',
                'resources/js/dashboard/nilai.js',
                'resources/js/dashboard/laporan.js',
                'resources/js/dashboard/pengaturan.js',
                'resources/js/dashboard/latsol.js',
                'resources/js/dashboard/paket.js',
                'resources/js/guru/soal.js',
            ],
            refresh: true,
        }),
    ],
});
