<?php

namespace Database\Seeders;

use App\Models\Jawaban;
use App\Models\Kelas;
use App\Models\Materi;
use App\Models\Nilai;
use App\Models\Paket;
use App\Models\Pendaftaran;
use App\Models\Pengerjaan;
use App\Models\Soal;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    protected const FOTO_DEFAULT = null;

    public function run(): void
    {
        $siswa = User::query()->firstOrCreate(
            ['email' => 'siswa@demo.id'],
            [
                'name' => 'Brian Pratama',
                'password' => bcrypt('password'),
                'role' => 'siswa',
                'foto' => self::FOTO_DEFAULT,
                'sekolah' => 'SMA Negeri 1 Jakarta',
                'kelas_jurusan' => 'Kelas 12 · IPA',
                'bio' => 'Pejuang UTBK 2026. Target: FK UI.',
            ]
        );
        $siswa->pakets()->syncWithoutDetaching([Paket::where('key', 'sma-ekstra')->first()->id]);

        User::query()->whereIn('email', ['siswa@demo.id', 'guru@demo.id', 'admin@demo.id'])
            ->update(['foto' => null]);

        User::query()->firstOrCreate(
            ['email' => 'guru@demo.id'],
            [
                'name' => 'Rina Kumala, M.Si.',
                'password' => bcrypt('password'),
                'role' => 'guru',
                'foto' => self::FOTO_DEFAULT,
                'sekolah' => 'Tim Tutor PintarKuy',
                'kelas_jurusan' => null,
                'bio' => 'Pengajar materi Saintek di PintarKuy sejak 2024.',
            ]
        );

        User::query()->firstOrCreate(
            ['email' => 'admin@demo.id'],
            [
                'name' => 'Operator PintarKuy',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'foto' => self::FOTO_DEFAULT,
                'sekolah' => 'Tim Operasional PintarKuy',
                'kelas_jurusan' => null,
                'bio' => 'Admin: mengelola paket & akun siswa.',
            ]
        );

        $kelasRows = [
            // UTBK-SNBT
            ['slug' => 'tps', 'name' => 'TPS Penalaran Umum', 'cat' => 'UTBK-SNBT', 'ico' => 'PU', 'meta' => 'Kelas 12 · Persiapan UTBK', 'desc' => 'Logika, analisis, dan penalaran kuantitatif berpola SNBT.', 'modul' => 32, 'durasi' => '12 Minggu', 'siswa' => 284, 'price' => 'Rp 599K', 'old' => 'Rp 799K', 'bg' => 'rgba(94,234,212,0.4)', 'color' => '#0F766E'],
            ['slug' => 'literasi', 'name' => 'Literasi Bahasa Indonesia', 'cat' => 'UTBK-SNBT', 'ico' => 'BI', 'meta' => 'Kelas 12 · Persiapan UTBK', 'desc' => 'Teknik membaca cepat dan inferensi untuk teks panjang.', 'modul' => 28, 'durasi' => '10 Minggu', 'siswa' => 231, 'price' => 'Rp 499K', 'old' => 'Rp 699K', 'bg' => 'rgba(223,228,251,1)', 'color' => '#1E3ABA'],
            ['slug' => 'matematika', 'name' => 'Matematika Saintek', 'cat' => 'UTBK-SNBT', 'ico' => 'MS', 'meta' => 'Kelas 12 · Saintek', 'desc' => 'Integral, trigonometri, statistika dengan trik cepat 20 detik.', 'modul' => 30, 'durasi' => '12 Minggu', 'siswa' => 318, 'price' => 'Rp 649K', 'old' => 'Rp 849K', 'bg' => 'rgba(254,243,199,1)', 'color' => '#92400e'],
            ['slug' => 'penalaran-matematika', 'name' => 'Penalaran Matematika (PM)', 'cat' => 'UTBK-SNBT', 'ico' => 'PM', 'meta' => 'Kelas 12 · Persiapan UTBK', 'desc' => 'Riset masalah kontekstual dan penalaran kuantitatif SNBT.', 'modul' => 26, 'durasi' => '10 Minggu', 'siswa' => 187, 'price' => 'Rp 549K', 'old' => 'Rp 749K', 'bg' => 'rgba(94,234,212,0.4)', 'color' => '#0F766E'],
            ['slug' => 'pengetahuan-kuantitatif', 'name' => 'Pengetahuan Kuantitatif (PK)', 'cat' => 'UTBK-SNBT', 'ico' => 'PK', 'meta' => 'Kelas 12 · Persiapan UTBK', 'desc' => 'Bilangan, aljabar, geometri, statistika dasar SNBT.', 'modul' => 24, 'durasi' => '10 Minggu', 'siswa' => 156, 'price' => 'Rp 499K', 'old' => 'Rp 699K', 'bg' => 'rgba(254,243,199,1)', 'color' => '#92400e'],
            ['slug' => 'pbm', 'name' => 'Pemahaman Bacaan & Menulis (PBM)', 'cat' => 'UTBK-SNBT', 'ico' => 'PB', 'meta' => 'Kelas 12 · Persiapan UTBK', 'desc' => 'Memahami wacana kompleks dan menulis efektif.', 'modul' => 22, 'durasi' => '9 Minggu', 'siswa' => 143, 'price' => 'Rp 449K', 'old' => 'Rp 649K', 'bg' => 'rgba(223,228,251,1)', 'color' => '#1E3ABA'],
            ['slug' => 'literasi-inggris', 'name' => 'Literasi Bahasa Inggris', 'cat' => 'UTBK-SNBT', 'ico' => 'LI', 'meta' => 'Kelas 12 · Persiapan UTBK', 'desc' => 'Reading comprehension dan vocabulary HOTS.', 'modul' => 20, 'durasi' => '8 Minggu', 'siswa' => 168, 'price' => 'Rp 469K', 'old' => 'Rp 629K', 'bg' => 'rgba(223,228,251,1)', 'color' => '#1E3ABA'],
            // SMA
            ['slug' => 'fisika', 'name' => 'Fisika Mekanika', 'cat' => 'SMA', 'ico' => 'FM', 'meta' => 'Kelas 11 · Wajib & Peminatan', 'desc' => 'Kinematika, dinamika, dan energi dengan pendekatan visual.', 'modul' => 24, 'durasi' => '10 Minggu', 'siswa' => 197, 'price' => 'Rp 449K', 'old' => 'Rp 599K', 'bg' => 'rgba(223,228,251,1)', 'color' => '#1E3ABA'],
            ['slug' => 'kimia', 'name' => 'Kimia Dasar & Stoikiometri', 'cat' => 'SMA', 'ico' => 'KN', 'meta' => 'Kelas 10-11 · Wajib', 'desc' => 'Perhitungan kimia dan konsep mol yang dibuat simpel.', 'modul' => 22, 'durasi' => '9 Minggu', 'siswa' => 164, 'price' => 'Rp 399K', 'old' => 'Rp 549K', 'bg' => 'rgba(94,234,212,0.4)', 'color' => '#0F766E'],
            ['slug' => 'biologi', 'name' => 'Biologi Sel & Genetika', 'cat' => 'SMA', 'ico' => 'BG', 'meta' => 'Kelas 12 · Peminatan Saintek', 'desc' => 'Sel, hereditas, dan bioteknologi dengan peta konsep.', 'modul' => 26, 'durasi' => '10 Minggu', 'siswa' => 152, 'price' => 'Rp 429K', 'old' => 'Rp 579K', 'bg' => 'rgba(223,228,251,1)', 'color' => '#1E3ABA'],
            ['slug' => 'matematika-wajib', 'name' => 'Matematika Wajib SMA', 'cat' => 'SMA', 'ico' => 'MW', 'meta' => 'Kelas 10-12 · Wajib', 'desc' => 'Aljabar, fungsi, dan statistika untuk semua jurusan.', 'modul' => 24, 'durasi' => '10 Minggu', 'siswa' => 214, 'price' => 'Rp 429K', 'old' => 'Rp 579K', 'bg' => 'rgba(254,243,199,1)', 'color' => '#92400e'],
            ['slug' => 'ekonomi', 'name' => 'Ekonomi', 'cat' => 'SMA', 'ico' => 'EK', 'meta' => 'Kelas 10-12 · IPS', 'desc' => 'Ekonomi mikro-makro, akuntansi dasar, kebijakan fiskal.', 'modul' => 25, 'durasi' => '10 Minggu', 'siswa' => 132, 'price' => 'Rp 419K', 'old' => 'Rp 559K', 'bg' => 'rgba(223,228,251,1)', 'color' => '#1E3ABA'],
            ['slug' => 'sosiologi', 'name' => 'Sosiologi', 'cat' => 'SMA', 'ico' => 'SO', 'meta' => 'Kelas 10-12 · IPS', 'desc' => 'Struktur sosial, interaksi, dan dinamika masyarakat.', 'modul' => 21, 'durasi' => '9 Minggu', 'siswa' => 118, 'price' => 'Rp 389K', 'old' => 'Rp 529K', 'bg' => 'rgba(94,234,212,0.4)', 'color' => '#0F766E'],
            ['slug' => 'geografi', 'name' => 'Geografi', 'cat' => 'SMA', 'ico' => 'GO', 'meta' => 'Kelas 10-12 · IPS', 'desc' => 'Bumi, atmosfer, dan interaksi ruang-wilayah.', 'modul' => 23, 'durasi' => '9 Minggu', 'siswa' => 111, 'price' => 'Rp 399K', 'old' => 'Rp 539K', 'bg' => 'rgba(223,228,251,1)', 'color' => '#1E3ABA'],
            ['slug' => 'sejarah', 'name' => 'Sejarah Indonesia', 'cat' => 'SMA', 'ico' => 'SJ', 'meta' => 'Kelas 10-12 · Wajib', 'desc' => 'Kronologi sejarah nasional dan kesadaran historis.', 'modul' => 22, 'durasi' => '9 Minggu', 'siswa' => 127, 'price' => 'Rp 379K', 'old' => 'Rp 519K', 'bg' => 'rgba(254,243,199,1)', 'color' => '#92400e'],
            // Bahasa
            ['slug' => 'toefl', 'name' => 'TOEFL & English Daily', 'cat' => 'Bahasa', 'ico' => 'TR', 'meta' => 'Semua jenjang', 'desc' => 'Naikkan skor TOEFL dan percakapan harian.', 'modul' => 20, 'durasi' => '8 Minggu', 'siswa' => 208, 'price' => 'Rp 529K', 'old' => 'Rp 699K', 'bg' => 'rgba(254,243,199,1)', 'color' => '#92400e'],
            ['slug' => 'inggris-literasi', 'name' => 'Bahasa Inggris Literasi', 'cat' => 'Bahasa', 'ico' => 'IV', 'meta' => 'Kelas 12 · UTBK', 'desc' => 'Reading comprehension dan grammar HOTS SNBT.', 'modul' => 24, 'durasi' => '10 Minggu', 'siswa' => 175, 'price' => 'Rp 469K', 'old' => 'Rp 629K', 'bg' => 'rgba(223,228,251,1)', 'color' => '#1E3ABA'],
            ['slug' => 'ielts', 'name' => 'IELTS Preparation', 'cat' => 'Bahasa', 'ico' => 'IE', 'meta' => 'Semua jenjang', 'desc' => 'Latihan intensif L-R-W-S IELTS.', 'modul' => 24, 'durasi' => '10 Minggu', 'siswa' => 176, 'price' => 'Rp 699K', 'old' => 'Rp 899K', 'bg' => 'rgba(223,228,251,1)', 'color' => '#1E3ABA'],
            ['slug' => 'toeic', 'name' => 'TOEIC Preparation', 'cat' => 'Bahasa', 'ico' => 'TC', 'meta' => 'Semua jenjang', 'desc' => 'Tingkatkan skor TOEIC untuk karier.', 'modul' => 20, 'durasi' => '8 Minggu', 'siswa' => 98, 'price' => 'Rp 599K', 'old' => 'Rp 799K', 'bg' => 'rgba(254,243,199,1)', 'color' => '#92400e'],
            ['slug' => 'jerman', 'name' => 'Bahasa Jerman', 'cat' => 'Bahasa', 'ico' => 'JM', 'meta' => 'Semua jenjang', 'desc' => 'Dari nol sampai percakapan Goethe A1-A2.', 'modul' => 18, 'durasi' => '8 Minggu', 'siswa' => 134, 'price' => 'Rp 549K', 'old' => 'Rp 749K', 'bg' => 'rgba(223,228,251,1)', 'color' => '#1E3ABA'],
            ['slug' => 'korea', 'name' => 'Bahasa Korea', 'cat' => 'Bahasa', 'ico' => 'KO', 'meta' => 'Semua jenjang', 'desc' => 'Hangul, tata bahasa, percakapan ala drakor.', 'modul' => 20, 'durasi' => '8 Minggu', 'siswa' => 241, 'price' => 'Rp 549K', 'old' => 'Rp 749K', 'bg' => 'rgba(94,234,212,0.4)', 'color' => '#0F766E'],
            ['slug' => 'jepang', 'name' => 'Bahasa Jepang', 'cat' => 'Bahasa', 'ico' => 'JP', 'meta' => 'Semua jenjang', 'desc' => 'Hiragana, katakana, kanji dasar JLPT N5-N4.', 'modul' => 22, 'durasi' => '10 Minggu', 'siswa' => 189, 'price' => 'Rp 569K', 'old' => 'Rp 769K', 'bg' => 'rgba(223,228,251,1)', 'color' => '#1E3ABA'],
            ['slug' => 'mandarin', 'name' => 'Bahasa Mandarin', 'cat' => 'Bahasa', 'ico' => 'MD', 'meta' => 'Semua jenjang', 'desc' => 'Pinyin, nada, percakapan bisnis HSK 1-2.', 'modul' => 20, 'durasi' => '8 Minggu', 'siswa' => 156, 'price' => 'Rp 559K', 'old' => 'Rp 759K', 'bg' => 'rgba(254,243,199,1)', 'color' => '#92400e'],
            // Ekstra
            ['slug' => 'python', 'name' => 'Coding Python Dasar', 'cat' => 'Ekstra', 'ico' => 'PY', 'meta' => 'Ekstra · Maks 25 siswa', 'desc' => 'Logika pemrograman dan sains data untuk pemula.', 'modul' => 18, 'durasi' => '8 Minggu', 'siswa' => 121, 'price' => 'Rp 399K', 'old' => 'Rp 499K', 'bg' => 'rgba(223,228,251,1)', 'color' => '#1E3ABA'],
            ['slug' => 'web-design', 'name' => 'Web Design & UI/UX', 'cat' => 'Ekstra', 'ico' => 'WD', 'meta' => 'Ekstra · Studio Kreatif', 'desc' => 'Dari wireframe sampai prototype interaktif.', 'modul' => 16, 'durasi' => '7 Minggu', 'siswa' => 98, 'price' => 'Rp 349K', 'old' => 'Rp 449K', 'bg' => 'rgba(223,228,251,1)', 'color' => '#1E3ABA'],
            ['slug' => 'public-speaking', 'name' => 'Public Speaking', 'cat' => 'Ekstra', 'ico' => 'PS', 'meta' => 'Ekstra · Soft Skill', 'desc' => 'Atasi grogi, bangun materi, bicara percaya diri.', 'modul' => 12, 'durasi' => '6 Minggu', 'siswa' => 145, 'price' => 'Rp 299K', 'old' => 'Rp 399K', 'bg' => 'rgba(223,228,251,1)', 'color' => '#1E3ABA'],
            ['slug' => 'digital-marketing', 'name' => 'Digital Marketing', 'cat' => 'Ekstra', 'ico' => 'DM', 'meta' => 'Ekstra · Skill Digital', 'desc' => 'Strategi konten, iklan, analitik bisnis online.', 'modul' => 16, 'durasi' => '7 Minggu', 'siswa' => 137, 'price' => 'Rp 349K', 'old' => 'Rp 449K', 'bg' => 'rgba(94,234,212,0.4)', 'color' => '#0F766E'],
        ];

        foreach ($kelasRows as $row) {
            Kelas::query()->updateOrCreate(['slug' => $row['slug']], $row);
        }

        $paketRows = [
            [
                'key' => 'utbk', 'nama' => 'Paket UTBK', 'tag' => 'Terlengkap', 'harga' => 599000, 'harga_lama' => 799000, 'kuota' => null,
                'kategori' => ['UTBK-SNBT'],
                'fitur' => ['Akses semua kelas UTBK-SNBT', 'Penalaran Umum, PM, PK, PBM', 'Literasi Bahasa Indonesia & Inggris', 'Matematika Saintek + bonus tryout', 'Live class 4x per minggu', 'Bank soal 6.000+ HOTS', 'Analitik skor prediksi IRT'],
            ],
            [
                'key' => 'sma-ekstra', 'nama' => 'Paket SMA + Ekstra', 'tag' => 'Paling Laris', 'harga' => 499000, 'harga_lama' => 699000, 'kuota' => null,
                'kategori' => ['SMA', 'Ekstra'],
                'fitur' => ['Semua mapel SMA + ekstrakurikuler', 'Fisika, Kimia, Biologi, Matematika', 'Ekonomi, Sosiologi, Geografi, Sejarah', 'Coding, Web Design, Public Speaking', 'Live class 3x per minggu', 'Tryout bulanan per mapel'],
            ],
            [
                'key' => 'bahasa', 'nama' => 'Paket Bahasa', 'tag' => 'Internasional', 'harga' => 399000, 'harga_lama' => 599000, 'kuota' => null,
                'kategori' => ['Bahasa'],
                'fitur' => ['TOEFL, IELTS, TOEIC preparation', 'Bahasa Inggris Literasi', 'Jerman, Korea, Jepang, Mandarin', 'Speaking practice dengan tutor', 'Live class 2x per minggu', 'Simulasi tes resmi berkala'],
            ],
        ];

        foreach ($paketRows as $row) {
            Paket::query()->updateOrCreate(['key' => $row['key']], $row);
        }

        $tutorBySlug = [
            'tps' => 'Dr. Andi Firmansyah, M.Ed.',
            'matematika' => 'Dr. Hendra Saputra, M.Sc.',
            'fisika' => 'Dr. Rina Kumala, M.Si.',
            'inggris-literasi' => 'Ms. Amelia Dwi, M.A.',
            'python' => 'Bapak Joko Prasetyo, M.Kom.',
            'kimia' => 'Ibu Sari Dewanti, M.Sc.',
        ];

        $kontenPembukaan = fn (Kelas $kelas) => "Selamat datang di program " . $kelas->name . "!\n\nPada sesi pertama ini kamu akan melihat peta belajar, strategi menaklukkan soal, dan pola materi yang dipakai sepanjang kelas. Pastikan kamu mencatat target belajar minggu ini dan siapkan alat tulis.";
        $kontenKonsep = fn (Kelas $kelas) => "Bab 1 membahas konsep inti dari " . $kelas->name . ".\n\nFokus utama:\n1) Pengertian dasar dan istilah penting\n2) Rumus serta logika yang mendasarinya\n3) Pola pengerjaan soal tipe dasar\n\nKerjakan latihan di akhir bab untuk menguatkan pemahamanmu.";
        $kontenLatihan = fn (Kelas $kelas) => "Bab 2 berisi kumpulan latihan soal pilihan ganda " . $kelas->name . ".\n\nSetiap soal punya pembahasan di halaman hasil latihan. Usahakan menjawab semua tanpa melihat kunci terlebih dahulu, lalu bandingkan skormu.";
        $kontenTryout = fn (Kelas $kelas) => "Tryout Mini mengukur sejauh mana kamu menguasai Bab 1–2 " . $kelas->name . ".\n\nWaktu 30 menit. Skor kamu otomatis masuk ke halaman Nilai dan Laporan.";

        foreach (Kelas::query()->where('aktif', true)->get() as $kelas) {
            $tutor = $tutorBySlug[$kelas->slug] ?? 'Tim Tutor Master PTN';
            $base = 'Pembahasan Materi Inti ' . $kelas->name;
            $defs = [
                ['judul' => 'Perkenalan Kelas & Strategi Belajar', 'tutor' => $tutor, 'pertemuan' => 1, 'durasi' => '45 Menit', 'bab' => 'Pembukaan', 'urutan' => 1, 'tipe' => 'video_teks', 'video_url' => null, 'konten' => $kontenPembukaan($kelas)],
                ['judul' => $base . ' — Konsep Dasar', 'tutor' => $tutor, 'pertemuan' => 2, 'durasi' => '90 Menit', 'bab' => 'Bab 1', 'urutan' => 2, 'tipe' => 'video_teks', 'video_url' => null, 'konten' => $kontenKonsep($kelas)],
                ['judul' => $base . ' — Penerapan & Trik Cepat', 'tutor' => $tutor, 'pertemuan' => 3, 'durasi' => '90 Menit', 'bab' => 'Bab 1', 'urutan' => 3, 'tipe' => 'video', 'video_url' => null, 'konten' => null],
                ['judul' => 'Latihan Soal & Pembahasan Bab 1', 'tutor' => $tutor, 'pertemuan' => 4, 'durasi' => '60 Menit', 'bab' => 'Bab 2', 'urutan' => 4, 'tipe' => 'video_teks', 'video_url' => null, 'konten' => $kontenLatihan($kelas)],
                ['judul' => $base . ' — Soal HOTS', 'tutor' => $tutor, 'pertemuan' => 5, 'durasi' => '90 Menit', 'bab' => 'Bab 2', 'urutan' => 5, 'tipe' => 'video', 'video_url' => null, 'konten' => null],
                ['judul' => 'Tryout Mini Bab 1-2', 'tutor' => $tutor, 'pertemuan' => 6, 'durasi' => '30 Menit', 'bab' => 'Bab 3', 'urutan' => 6, 'tipe' => 'teks', 'video_url' => null, 'konten' => $kontenTryout($kelas)],
            ];
            foreach ($defs as $def) {
                $kelas->materi()->updateOrCreate(
                    ['urutan' => $def['urutan']],
                    $def
                );
            }
        }

        $bank = [
            'tps' => [
                ['pertanyaan' => 'Semua kucing adalah mamalia. Sebagian mamalia adalah herbivora. Kesimpulan yang PALING benar adalah …', 'opsi' => ['Semua kucing adalah herbivora', 'Sebagian herbivora adalah kucing', 'Ada kemungkinan sebahagian kucing bukan herbivora', 'Tidak ada kucing yang bukan herbivora'], 'kunci' => 2, 'pembahasan' => 'Premis "sebagian mamalia herbivora" tidak memastikan kucing termasuk herbivora, sehingga kesimpulan yang sah adalah kemungkinan sebagai kucing bukan herbivora.'],
                ['pertanyaan' => 'Rangkaian bilangan: 2, 6, 12, 20, 30, … Bilangan berikutnya adalah …', 'opsi' => ['36', '42', '40', '44'], 'kunci' => 1, 'pembahasan' => 'Selisih bertambah 4, 6, 8, 10, sehingga selisih berikutnya 12: 30 + 12 = 42.'],
                ['pertanyaan' => 'Rata-rata lima bilangan adalah 12. Jumlah kelima bilangan itu adalah …', 'opsi' => ['60', '56', '66', '51'], 'kunci' => 0, 'pembahasan' => 'Jumlah = rata-rata × banyak data = 12 × 5 = 60.'],
                ['pertanyaan' => 'Sebuah koin dilempar sebanyak 3 kali. Banyak anggota ruang sampel adalah …', 'opsi' => ['6', '9', '24', '8'], 'kunci' => 3, 'pembahasan' => 'Ruang sampel tiap lemparan 2, maka 2³ = 8.'],
                ['pertanyaan' => 'Andi lebih tua dari Budi, dan Budi lebih muda dari Cici. Dari informasi ini, pernyataan yang PASTI benar adalah …', 'opsi' => ['Andi lebih tua dari Cici', 'Cici lebih tua dari Budi', 'Andi dan Cici sebaya', 'Umur Budi paling tua'], 'kunci' => 1, 'pembahasan' => 'Hanya relasi Cici > Budi yang pasti benar; relasi Andi vs Cici tidak dapat disimpulkan.'],
            ],
            'matematika' => [
                ['pertanyaan' => 'Nilai dari ∫ 2x dx adalah …', 'opsi' => ['x² + C', '½x² + C', '2x² + C', 'x + C'], 'kunci' => 0, 'pembahasan' => '∫ 2x dx = 2 · (½x²) + C = x² + C.'],
                ['pertanyaan' => 'Turunan dari f(x) = x² + 3x adalah …', 'opsi' => ['2x + 3', 'x + 3', '2x', '2x − 3'], 'kunci' => 0, 'pembahasan' => 'f\'(x) = 2x + 3 dari aturan turunan pangkat dan konstanta.'],
                ['pertanyaan' => 'Jika sin θ = 3/5 dengan θ sudut lancip, maka cos θ = …', 'opsi' => ['2/5', '4/5', '5/3', '3/4'], 'kunci' => 1, 'pembahasan' => 'Gunakan identitas sin²θ + cos²θ = 1, maka cos θ = √(1 − 9/25) = 4/5.'],
                ['pertanyaan' => 'Suku ke-10 dari barisan 3, 6, 9, 12, … adalah …', 'opsi' => ['27', '30', '33', '36'], 'kunci' => 1, 'pembahasan' => 'Barisan aritmetika dengan a = 3 dan b = 3, U₁₀ = 3 + 9(3) = 30.'],
                ['pertanyaan' => 'Banyak cara memilih 2 siswa dari 5 siswa adalah …', 'opsi' => ['15', '12', '25', '10'], 'kunci' => 3, 'pembahasan' => 'C(5,2) = 5!/2!3! = 10.'],
            ],
            'fisika' => [
                ['pertanyaan' => 'Sebuah mobil bergerak dengan kecepatan tetap 20 m/s selama 5 detik. Jarak yang ditempuh adalah …', 'opsi' => ['100 m', '25 m', '4 m', '50 m'], 'kunci' => 0, 'pembahasan' => 's = v × t = 20 × 5 = 100 m.'],
                ['pertanyaan' => 'Benda bermassa 2 kg diberi gaya sebesar 10 N. Percepatan benda adalah …', 'opsi' => ['20 m/s²', '5 m/s²', '12 m/s²', '8 m/s²'], 'kunci' => 1, 'pembahasan' => 'a = F/m = 10/2 = 5 m/s².'],
                ['pertanyaan' => 'Energi kinetik benda 4 kg yang bergerak dengan kecepatan 3 m/s adalah …', 'opsi' => ['6 J', '9 J', '36 J', '18 J'], 'kunci' => 3, 'pembahasan' => 'Ek = ½mv² = ½ · 4 · 9 = 18 J.'],
                ['pertanyaan' => 'Sebuah gaya 20 N bekerja searah perpindahan 5 m. Usaha yang dilakukan adalah …', 'opsi' => ['100 J', '25 J', '4 J', '50 J'], 'kunci' => 0, 'pembahasan' => 'W = F · s = 20 × 5 = 100 J.'],
                ['pertanyaan' => 'Hukum II Newton menyatakan bahwa percepatan benda berbanding lurus dengan …', 'opsi' => ['massa benda', 'kecepatan benda', 'gaya yang bekerja', 'waktu tempuh'], 'kunci' => 2, 'pembahasan' => 'ΣF = m·a, sehingga percepatan sebanding dengan gaya total.'],
            ],
            'kimia' => [
                ['pertanyaan' => 'Diketahui massa atom relatif C = 12 dan O = 16. Massa molekul relatif (Mr) CO₂ adalah …', 'opsi' => ['28', '44', '32', '40'], 'kunci' => 1, 'pembahasan' => 'Mr CO₂ = 12 + 2(16) = 44.'],
                ['pertanyaan' => 'Larutan dengan pH = 3 bersifat …', 'opsi' => ['asam', 'basa', 'netral', 'garam'], 'kunci' => 0, 'pembahasan' => 'pH < 7 menunjukkan larutan asam.'],
                ['pertanyaan' => 'Rumus molekul air yang benar adalah …', 'opsi' => ['CO₂', 'H₂O', 'O₂', 'H₂'], 'kunci' => 1, 'pembahasan' => 'Air terdiri dari 2 atom H dan 1 atom O, rumusnya H₂O.'],
                ['pertanyaan' => 'Jumlah mol dari 44 gram CO₂ (Mr = 44) adalah …', 'opsi' => ['1 mol', '2 mol', '0,5 mol', '44 mol'], 'kunci' => 0, 'pembahasan' => 'n = massa/Mr = 44/44 = 1 mol.'],
                ['pertanyaan' => 'Perubahan wujud zat dari padat menjadi cair disebut …', 'opsi' => ['membeku', 'menguap', 'menyublim', 'melebur'], 'kunci' => 3, 'pembahasan' => 'Padat → cair adalah proses melebur.'],
            ],
        ];

        $bankKelas = ['tps', 'matematika', 'fisika', 'kimia'];
        $bankKelasIds = Kelas::query()->whereIn('slug', $bankKelas)->pluck('id');
        Pengerjaan::query()->whereIn('kelas_id', $bankKelasIds)->delete();
        Soal::query()->whereIn('kelas_id', $bankKelasIds)->delete();
        foreach ($bankKelas as $slug) {
            $kelas = Kelas::query()->where('slug', $slug)->first();
            if (!$kelas) {
                continue;
            }
            $materiId = $kelas->materi()->where('urutan', 2)->first()?->id;
            foreach ($bank[$slug] as $i => $item) {
                Soal::query()->create([
                    'kelas_id' => $kelas->id,
                    'materi_id' => $materiId,
                    'set_label' => 'Latihan Bab 1',
                    'pertanyaan' => $item['pertanyaan'],
                    'opsi' => ['A' => $item['opsi'][0], 'B' => $item['opsi'][1], 'C' => $item['opsi'][2], 'D' => $item['opsi'][3]],
                    'kunci' => $item['kunci'],
                    'pembahasan' => $item['pembahasan'],
                    'urutan' => $i + 1,
                    'aktif' => true,
                ]);
            }
        }

        $guru = User::query()->where('email', 'guru@demo.id')->first();
        if ($guru) {
            $guru->kelasDiampu()->sync(
                Kelas::query()->whereIn('slug', ['fisika', 'kimia'])->pluck('id')
            );
        }

        Pendaftaran::query()->where('user_id', $siswa->id)->delete();
        foreach (['tps', 'matematika', 'fisika'] as $slug) {
            $kelas = Kelas::query()->where('slug', $slug)->first();
            if ($kelas) {
                Pendaftaran::query()->firstOrCreate([
                    'user_id' => $siswa->id,
                    'kelas_id' => $kelas->id,
                ]);
            }
        }

        $tryout = [
            ['slug' => 'tps', 'skor' => 81, 'akurasi' => 81, 'tanggal' => '2026-03-18'],
            ['slug' => 'tps', 'skor' => 82, 'akurasi' => 82, 'tanggal' => '2026-04-22'],
            ['slug' => 'matematika', 'skor' => 84, 'akurasi' => 84, 'tanggal' => '2026-05-21'],
            ['slug' => 'matematika', 'skor' => 85, 'akurasi' => 85, 'tanggal' => '2026-06-19'],
            ['slug' => 'fisika', 'skor' => 86, 'akurasi' => 86, 'tanggal' => '2026-07-23'],
            ['slug' => 'fisika', 'skor' => 87, 'akurasi' => 87, 'tanggal' => '2026-08-20'],
        ];

        Nilai::query()->where('user_id', $siswa->id)->delete();
        Pengerjaan::query()->where('user_id', $siswa->id)->delete();

        foreach ($tryout as $row) {
            $kelas = Kelas::query()->where('slug', $row['slug'])->first();
            Nilai::query()->create([
                'user_id' => $siswa->id,
                'kelas_id' => $kelas?->id,
                'skor' => $row['skor'],
                'akurasi' => $row['akurasi'],
                'tanggal' => $row['tanggal'],
            ]);

            if ($kelas === null) {
                continue;
            }

            $soalKelas = Soal::query()->where('kelas_id', $kelas->id)->where('aktif', true)->orderBy('urutan')->get();
            if ($soalKelas->isEmpty()) {
                continue;
            }

            $benar = $row['akurasi'];
            $total = 100;
            $pengerjaan = Pengerjaan::query()->create([
                'user_id' => $siswa->id,
                'kelas_id' => $kelas->id,
                'set_label' => 'Latihan Bab 1',
                'tipe' => 'latsol',
                'skor' => $row['skor'],
                'akurasi' => $row['akurasi'],
                'benar' => $benar,
                'total' => $total,
                'created_at' => $row['tanggal'] . ' 12:00:00',
                'updated_at' => $row['tanggal'] . ' 12:00:00',
            ]);

            foreach ($soalKelas as $i => $soal) {
                $isBenar = $i < count($soalKelas) - 2;
                if ($isBenar) {
                    $pilihan = (int) $soal->kunci;
                } else {
                    $pilihan = ((int) $soal->kunci + 1) % 4;
                }
                Jawaban::query()->create([
                    'user_id' => $siswa->id,
                    'pengerjaan_id' => $pengerjaan->id,
                    'soal_id' => $soal->id,
                    'pilihan' => $pilihan,
                    'benar' => $pilihan === (int) $soal->kunci,
                    'created_at' => $row['tanggal'] . ' 12:00:00',
                    'updated_at' => $row['tanggal'] . ' 12:00:00',
                ]);
            }
        }
    }
}