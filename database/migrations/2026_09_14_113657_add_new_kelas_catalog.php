<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    protected const ROWS = [
        // ---- UTBK-SNBT (paket UTBK) ----
        ['slug' => 'penalaran-matematika', 'name' => 'Penalaran Matematika (PM)', 'cat' => 'UTBK-SNBT', 'ico' => 'PM', 'meta' => 'Kelas 12 · Persiapan UTBK', 'desc' => 'Riset masalah kontekstual dan penalaran kuantitatif bertingkat SNBT.', 'modul' => 26, 'durasi' => '10 Minggu', 'siswa' => 187, 'price' => 'Rp 549K', 'old' => 'Rp 749K', 'bg' => 'rgba(94,234,212,0.4)', 'color' => '#0F766E'],
        ['slug' => 'pengetahuan-kuantitatif', 'name' => 'Pengetahuan Kuantitatif (PK)', 'cat' => 'UTBK-SNBT', 'ico' => 'PK', 'meta' => 'Kelas 12 · Persiapan UTBK', 'desc' => 'Bilangan, aljabar, geometri, dan statistika dasar dengan trik cepat.', 'modul' => 24, 'durasi' => '10 Minggu', 'siswa' => 156, 'price' => 'Rp 499K', 'old' => 'Rp 699K', 'bg' => 'rgba(254,243,199,1)', 'color' => '#92400e'],
        ['slug' => 'pbm', 'name' => 'Pemahaman Bacaan & Menulis (PBM)', 'cat' => 'UTBK-SNBT', 'ico' => 'PB', 'meta' => 'Kelas 12 · Persiapan UTBK', 'desc' => 'Memahami wacana kompleks dan latihan menulis efektif bergaya formal.', 'modul' => 22, 'durasi' => '9 Minggu', 'siswa' => 143, 'price' => 'Rp 449K', 'old' => 'Rp 649K', 'bg' => 'rgba(223,228,251,1)', 'color' => '#1E3ABA'],
        ['slug' => 'literasi-inggris', 'name' => 'Literasi Bahasa Inggris', 'cat' => 'UTBK-SNBT', 'ico' => 'LI', 'meta' => 'Kelas 12 · Persiapan UTBK', 'desc' => 'Reading comprehension dan vocabulary untuk soal literasi bahasa Inggris.', 'modul' => 20, 'durasi' => '8 Minggu', 'siswa' => 168, 'price' => 'Rp 469K', 'old' => 'Rp 629K', 'bg' => 'rgba(223,228,251,1)', 'color' => '#1E3ABA'],

        // ---- SMA (paket SMA + Ekstra) ----
        ['slug' => 'matematika-wajib', 'name' => 'Matematika Wajib SMA', 'cat' => 'SMA', 'ico' => 'MW', 'meta' => 'Kelas 10-12 · Wajib', 'desc' => 'Aljabar, fungsi, dan statistika untuk semua jurusan.', 'modul' => 24, 'durasi' => '10 Minggu', 'siswa' => 214, 'price' => 'Rp 429K', 'old' => 'Rp 579K', 'bg' => 'rgba(254,243,199,1)', 'color' => '#92400e'],
        ['slug' => 'ekonomi', 'name' => 'Ekonomi', 'cat' => 'SMA', 'ico' => 'EK', 'meta' => 'Kelas 10-12 · Peminatan IPS', 'desc' => 'Ekonomi mikro-makro, akuntansi dasar, dan kebijakan fiskal.', 'modul' => 25, 'durasi' => '10 Minggu', 'siswa' => 132, 'price' => 'Rp 419K', 'old' => 'Rp 559K', 'bg' => 'rgba(223,228,251,1)', 'color' => '#1E3ABA'],
        ['slug' => 'sosiologi', 'name' => 'Sosiologi', 'cat' => 'SMA', 'ico' => 'SO', 'meta' => 'Kelas 10-12 · Peminatan IPS', 'desc' => 'Struktur sosial, interaksi, dan dinamika masyarakat.', 'modul' => 21, 'durasi' => '9 Minggu', 'siswa' => 118, 'price' => 'Rp 389K', 'old' => 'Rp 529K', 'bg' => 'rgba(94,234,212,0.4)', 'color' => '#0F766E'],
        ['slug' => 'geografi', 'name' => 'Geografi', 'cat' => 'SMA', 'ico' => 'GO', 'meta' => 'Kelas 10-12 · Peminatan IPS', 'desc' => 'Bumi, atmosfer, dan interaksi ruang-wilayah dengan pemetaan.', 'modul' => 23, 'durasi' => '9 Minggu', 'siswa' => 111, 'price' => 'Rp 399K', 'old' => 'Rp 539K', 'bg' => 'rgba(223,228,251,1)', 'color' => '#1E3ABA'],
        ['slug' => 'sejarah', 'name' => 'Sejarah Indonesia', 'cat' => 'SMA', 'ico' => 'SJ', 'meta' => 'Kelas 10-12 · Wajib', 'desc' => 'Kronologi sejarah nasional dan kesadaran historis.', 'modul' => 22, 'durasi' => '9 Minggu', 'siswa' => 127, 'price' => 'Rp 379K', 'old' => 'Rp 519K', 'bg' => 'rgba(254,243,199,1)', 'color' => '#92400e'],

        // ---- Bahasa (paket Bahasa) ----
        ['slug' => 'ielts', 'name' => 'IELTS Preparation', 'cat' => 'Bahasa', 'ico' => 'IE', 'meta' => 'Semua jenjang · Tes Internasional', 'desc' => 'Latihan intensif Listening, Reading, Writing, dan Speaking IELTS.', 'modul' => 24, 'durasi' => '10 Minggu', 'siswa' => 176, 'price' => 'Rp 699K', 'old' => 'Rp 899K', 'bg' => 'rgba(223,228,251,1)', 'color' => '#1E3ABA'],
        ['slug' => 'toeic', 'name' => 'TOEIC Preparation', 'cat' => 'Bahasa', 'ico' => 'TC', 'meta' => 'Semua jenjang · Tes Internasional', 'desc' => 'Tingkatkan skor TOEIC untuk karier dan studi ke luar negeri.', 'modul' => 20, 'durasi' => '8 Minggu', 'siswa' => 98, 'price' => 'Rp 599K', 'old' => 'Rp 799K', 'bg' => 'rgba(254,243,199,1)', 'color' => '#92400e'],
        ['slug' => 'jerman', 'name' => 'Bahasa Jerman', 'cat' => 'Bahasa', 'ico' => 'JM', 'meta' => 'Semua jenjang · Pemula', 'desc' => 'Dari nol sampai percakapan dasar dan persiapan Goethe A1-A2.', 'modul' => 18, 'durasi' => '8 Minggu', 'siswa' => 134, 'price' => 'Rp 549K', 'old' => 'Rp 749K', 'bg' => 'rgba(223,228,251,1)', 'color' => '#1E3ABA'],
        ['slug' => 'korea', 'name' => 'Bahasa Korea', 'cat' => 'Bahasa', 'ico' => 'KO', 'meta' => 'Semua jenjang · Pemula', 'desc' => 'Hangul, tata bahasa, dan percakapan seru ala drakor.', 'modul' => 20, 'durasi' => '8 Minggu', 'siswa' => 241, 'price' => 'Rp 549K', 'old' => 'Rp 749K', 'bg' => 'rgba(94,234,212,0.4)', 'color' => '#0F766E'],
        ['slug' => 'jepang', 'name' => 'Bahasa Jepang', 'cat' => 'Bahasa', 'ico' => 'JP', 'meta' => 'Semua jenjang · Pemula', 'desc' => 'Hiragana, katakana, kanji dasar, dan persiapan JLPT N5-N4.', 'modul' => 22, 'durasi' => '10 Minggu', 'siswa' => 189, 'price' => 'Rp 569K', 'old' => 'Rp 769K', 'bg' => 'rgba(223,228,251,1)', 'color' => '#1E3ABA'],
        ['slug' => 'mandarin', 'name' => 'Bahasa Mandarin', 'cat' => 'Bahasa', 'ico' => 'MD', 'meta' => 'Semua jenjang · Pemula', 'desc' => 'Pinyin, nada, dan percakapan bisnis dasar HSK 1-2.', 'modul' => 20, 'durasi' => '8 Minggu', 'siswa' => 156, 'price' => 'Rp 559K', 'old' => 'Rp 759K', 'bg' => 'rgba(254,243,199,1)', 'color' => '#92400e'],

        // ---- Ekstra (paket SMA + Ekstra) ----
        ['slug' => 'public-speaking', 'name' => 'Public Speaking', 'cat' => 'Ekstra', 'ico' => 'PS', 'meta' => 'Ekstra · Soft Skill', 'desc' => 'Atasi grogi, bangun materi, dan berbicara di depan umum dengan percaya diri.', 'modul' => 12, 'durasi' => '6 Minggu', 'siswa' => 145, 'price' => 'Rp 299K', 'old' => 'Rp 399K', 'bg' => 'rgba(223,228,251,1)', 'color' => '#1E3ABA'],
        ['slug' => 'digital-marketing', 'name' => 'Digital Marketing', 'cat' => 'Ekstra', 'ico' => 'DM', 'meta' => 'Ekstra · Skill Digital', 'desc' => 'Strategi konten, iklan, dan analitik untuk pemula bisnis online.', 'modul' => 16, 'durasi' => '7 Minggu', 'siswa' => 137, 'price' => 'Rp 349K', 'old' => 'Rp 449K', 'bg' => 'rgba(94,234,212,0.4)', 'color' => '#0F766E'],
    ];

    public function up(): void
    {
        foreach (self::ROWS as $row) {
            DB::table('kelas')->updateOrInsert(['slug' => $row['slug']], $row);
        }
    }

    public function down(): void
    {
        DB::table('kelas')->whereIn('slug', array_column(self::ROWS, 'slug'))->delete();
    }
};