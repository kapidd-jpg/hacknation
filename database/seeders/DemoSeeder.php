<?php

namespace Database\Seeders;

use App\Models\Kelas;
use App\Models\Materi;
use App\Models\Nilai;
use App\Models\Paket;
use App\Models\Pendaftaran;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    protected const FOTO_DEFAULT = 'https://www.figma.com/api/mcp/asset/4b9001eb-320b-418b-b43a-9ddbb0503794.png';

    public function run(): void
    {
        if (app()->isProduction()) {
            $this->command?->warn('DemoSeeder dilewati: aplikasi sedang berjalan di production.');

            return;
        }

        $siswa = User::query()->where('email', 'siswa@demo.id')->first();
        if ($siswa === null) {
            $siswa = new User();
            $siswa->email = 'siswa@demo.id';
            $siswa->name = 'Brian Pratama';
            $siswa->foto = self::FOTO_DEFAULT;
            $siswa->sekolah = 'SMA Negeri 1 Jakarta';
            $siswa->kelas_jurusan = 'Kelas 12 · IPA';
            $siswa->bio = 'Pejuang UTBK 2026. Target: FK UI.';
        }
        $siswa->password = 'password';
        $siswa->paket = 'utbk-pro';
        $siswa->role = 'siswa';
        $siswa->save();

        $guru = User::query()->where('email', 'guru@demo.id')->first();
        if ($guru === null) {
            $guru = new User();
            $guru->email = 'guru@demo.id';
            $guru->name = 'Rina Kumala, M.Si.';
            $guru->foto = self::FOTO_DEFAULT;
            $guru->sekolah = 'Tim Tutor PintarKuy';
            $guru->kelas_jurusan = null;
            $guru->bio = 'Pengajar materi Saintek di PintarKuy sejak 2024.';
        }
        $guru->password = 'password';
        $guru->role = 'guru';
        $guru->save();

        $admin = User::query()->where('email', 'admin@demo.id')->first();
        if ($admin === null) {
            $admin = new User();
            $admin->email = 'admin@demo.id';
            $admin->name = 'Operator PintarKuy';
            $admin->foto = self::FOTO_DEFAULT;
            $admin->sekolah = 'Tim Operasional PintarKuy';
            $admin->kelas_jurusan = null;
            $admin->bio = 'Admin: mengelola paket & akun siswa.';
        }
        $admin->password = 'password';
        $admin->role = 'admin';
        $admin->save();

        $kelasRows = [
            ['slug' => 'tps', 'name' => 'TPS Penalaran Umum', 'cat' => 'UTBK-SNBT', 'ico' => 'PU', 'meta' => 'Kelas 12 · Persiapan UTBK', 'desc' => 'Logika, analisis, dan penalaran kuantitatif berpola SNBT.', 'modul' => 32, 'durasi' => '12 Minggu', 'siswa' => 284, 'price' => 'Rp 599K', 'old' => 'Rp 799K', 'bg' => 'rgba(126,252,154,0.35)', 'color' => '#007433'],
            ['slug' => 'literasi', 'name' => 'Literasi Bahasa Indonesia', 'cat' => 'UTBK-SNBT', 'ico' => 'BI', 'meta' => 'Kelas 12 · Persiapan UTBK', 'desc' => 'Teknik membaca cepat dan inferensi untuk teks panjang.', 'modul' => 28, 'durasi' => '10 Minggu', 'siswa' => 231, 'price' => 'Rp 499K', 'old' => 'Rp 699K', 'bg' => 'rgba(222,225,255,1)', 'color' => '#111c4e'],
            ['slug' => 'matematika', 'name' => 'Matematika Saintek', 'cat' => 'UTBK-SNBT', 'ico' => 'MS', 'meta' => 'Kelas 12 · Saintek', 'desc' => 'Integral, trigonometri, statistika dengan trik cepat 20 detik.', 'modul' => 30, 'durasi' => '12 Minggu', 'siswa' => 318, 'price' => 'Rp 649K', 'old' => 'Rp 849K', 'bg' => 'rgba(254,243,199,1)', 'color' => '#92400e'],
            ['slug' => 'fisika', 'name' => 'Fisika Mekanika', 'cat' => 'SMA', 'ico' => 'FM', 'meta' => 'Kelas 11 · Wajib & Peminatan', 'desc' => 'Kinematika, dinamika, dan energi dengan pendekatan visual.', 'modul' => 24, 'durasi' => '10 Minggu', 'siswa' => 197, 'price' => 'Rp 449K', 'old' => 'Rp 599K', 'bg' => 'rgba(237,233,254,1)', 'color' => '#6d28d9'],
            ['slug' => 'kimia', 'name' => 'Kimia Dasar & Stoikiometri', 'cat' => 'SMA', 'ico' => 'KN', 'meta' => 'Kelas 10-11 · Wajib', 'desc' => 'Perhitungan kimia dan konsep mol yang dibuat simpel.', 'modul' => 22, 'durasi' => '9 Minggu', 'siswa' => 164, 'price' => 'Rp 399K', 'old' => 'Rp 549K', 'bg' => 'rgba(126,252,154,0.35)', 'color' => '#007433'],
            ['slug' => 'biologi', 'name' => 'Biologi Sel & Genetika', 'cat' => 'SMA', 'ico' => 'BG', 'meta' => 'Kelas 12 · Peminatan Saintek', 'desc' => 'Sel, hereditas, dan bioteknologi dengan peta konsep.', 'modul' => 26, 'durasi' => '10 Minggu', 'siswa' => 152, 'price' => 'Rp 429K', 'old' => 'Rp 579K', 'bg' => 'rgba(222,225,255,1)', 'color' => '#111c4e'],
            ['slug' => 'toefl', 'name' => 'TOEFL & English Daily', 'cat' => 'Bahasa', 'ico' => 'TR', 'meta' => 'Semua jenjang', 'desc' => 'Naikkan skor TOEFL dan percakapan harian.', 'modul' => 20, 'durasi' => '8 Minggu', 'siswa' => 208, 'price' => 'Rp 529K', 'old' => 'Rp 699K', 'bg' => 'rgba(254,243,199,1)', 'color' => '#92400e'],
            ['slug' => 'inggris-literasi', 'name' => 'Bahasa Inggris Literasi', 'cat' => 'Bahasa', 'ico' => 'IV', 'meta' => 'Kelas 12 · UTBK', 'desc' => 'Reading comprehension dan grammar level HOTS SNBT.', 'modul' => 24, 'durasi' => '10 Minggu', 'siswa' => 175, 'price' => 'Rp 469K', 'old' => 'Rp 629K', 'bg' => 'rgba(222,225,255,1)', 'color' => '#111c4e'],
            ['slug' => 'python', 'name' => 'Coding Python Dasar', 'cat' => 'Ekstra', 'ico' => 'PY', 'meta' => 'Ekstra · Maks 25 siswa', 'desc' => 'Logika pemrograman dan sains data untuk pemula.', 'modul' => 18, 'durasi' => '8 Minggu', 'siswa' => 121, 'price' => 'Rp 399K', 'old' => 'Rp 499K', 'bg' => 'rgba(237,233,254,1)', 'color' => '#6d28d9'],
            ['slug' => 'web-design', 'name' => 'Web Design & UI/UX', 'cat' => 'Ekstra', 'ico' => 'WD', 'meta' => 'Ekstra · Studio Kreatif', 'desc' => 'Dari wireframe sampai prototype interaktif.', 'modul' => 16, 'durasi' => '7 Minggu', 'siswa' => 98, 'price' => 'Rp 349K', 'old' => 'Rp 449K', 'bg' => 'rgba(222,225,255,1)', 'color' => '#111c4e'],
        ];

        foreach ($kelasRows as $row) {
            Kelas::query()->updateOrCreate(['slug' => $row['slug']], $row);
        }

        $paketRows = [
            [
                'key' => 'starter', 'nama' => 'Starter', 'tag' => 'Pemula', 'harga' => 199000, 'harga_lama' => 249000, 'kuota' => 1,
                'fitur' => ['1 program kelas', 'Akses materi 30 hari', 'Rekaman kelas full HD', 'Forum diskusi komunitas'],
            ],
            [
                'key' => 'utbk-pro', 'nama' => 'UTBK Pro', 'tag' => 'Paling Populer', 'harga' => 499000, 'harga_lama' => 599000, 'kuota' => 3,
                'fitur' => ['3 program kelas', 'Akses materi 120 hari', 'Live class + tanya tutor', 'Tryout & simulasi IRT', 'Rekaman kelas full HD'],
            ],
            [
                'key' => 'golden', 'nama' => 'Golden Campus', 'tag' => 'Program Unggulan', 'harga' => 899000, 'harga_lama' => 1099000, 'kuota' => null,
                'fitur' => ['Semua program kelas', 'Akses tanpa batas', 'Mentor pribadi 1-on-1', 'Tryout & simulasi IRT', 'Konsultasi SNBP/UTBK', 'Prioritas bimbingan PTN'],
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

        foreach (Kelas::query()->where('aktif', true)->get() as $kelas) {
            $tutor = $tutorBySlug[$kelas->slug] ?? 'Tim Tutor Master PTN';
            if ($kelas->materi()->exists()) {
                continue;
            }
            $kelas->materi()->createMany([
                ['judul' => 'Perkenalan Kelas & Strategi Belajar', 'tutor' => $tutor, 'pertemuan' => 1, 'durasi' => '45 Menit', 'bab' => 'Pembukaan', 'urutan' => 1],
                ['judul' => 'Pembahasan Materi Inti ' . $kelas->name, 'tutor' => $tutor, 'pertemuan' => 3, 'durasi' => '90 Menit', 'bab' => 'Bab 1', 'urutan' => 2],
                ['judul' => 'Latihan Soal & Pembahasan HOTS', 'tutor' => $tutor, 'pertemuan' => 5, 'durasi' => '90 Menit', 'bab' => 'Bab 2', 'urutan' => 3],
            ]);
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
            ['slug' => 'tps', 'skor' => 638, 'akurasi' => 81, 'tanggal' => '2026-03-18'],
            ['slug' => 'tps', 'skor' => 652, 'akurasi' => 82, 'tanggal' => '2026-04-22'],
            ['slug' => 'matematika', 'skor' => 668, 'akurasi' => 84, 'tanggal' => '2026-05-21'],
            ['slug' => 'matematika', 'skor' => 685, 'akurasi' => 85, 'tanggal' => '2026-06-19'],
            ['slug' => 'fisika', 'skor' => 698, 'akurasi' => 86, 'tanggal' => '2026-07-23'],
            ['slug' => 'fisika', 'skor' => 712, 'akurasi' => 87, 'tanggal' => '2026-08-20'],
        ];

        Nilai::query()->where('user_id', $siswa->id)->delete();
        foreach ($tryout as $row) {
            $kelas = Kelas::query()->where('slug', $row['slug'])->first();
            Nilai::query()->create([
                'user_id' => $siswa->id,
                'kelas_id' => $kelas?->id,
                'skor' => $row['skor'],
                'akurasi' => $row['akurasi'],
                'tanggal' => $row['tanggal'],
            ]);
        }
    }
}