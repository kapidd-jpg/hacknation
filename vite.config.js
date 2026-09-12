import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/pages/site.css',
                'resources/css/pages/tentang.css',
                'resources/css/pages/kelas.css',
                'resources/css/pages/kontak.css',
                'resources/js/pages/tentang.js',
                'resources/js/pages/kelas.js',
                'resources/js/pages/kontak.js',
                'resources/css/dashboard/site.css',
                'resources/css/dashboard/kelas.css',
                'resources/css/dashboard/katalog.css',
                'resources/css/dashboard/nilai.css',
                'resources/css/dashboard/laporan.css',
                'resources/css/dashboard/pengaturan.css',
                'resources/js/dashboard/kelas.js',
                'resources/js/dashboard/katalog.js',
                'resources/js/dashboard/nilai.js',
                'resources/js/dashboard/laporan.js',
                'resources/js/dashboard/pengaturan.js',
            ],
            refresh: true,
        }),
    ],
});
