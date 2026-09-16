# PintarKuy

**Bimbel adaptif UTBK-SNBT** - platform belajar interaktif dengan tutor lulusan PTN terbaik,
ribuan latihan soal adaptif, dan simulasi tryout berstandar resmi.

Dibangun dengan **Laravel 10 + MySQL + Tailwind (via Vite)** untuk hackathon
"Tour 2 - #Nation Hackathon" (periode 10–18 September 2026).

---

## Fitur Utama

### 👨‍🎓 Role Siswa
- Registrasi & login (session-based, SPA-style flows tetap dipertahankan)
- **Katalog kelas** - lihat daftar kelas aktif dari database, pencarian & filter kategori
- **Daftar kelas** - enroll ke kelas dengan cek kuota per paket serta cegah pendaftaran ganda
- **Kelas saya** - daftar kelas terdaftar dengan progres modul
- **Materi** - modul & silabus yang ditarik dari database per kelas (progres `bab_cur` otomatis)
- **Latihan Soal (Latsol)** - kerjakan bank soal per set per kelas, langsung dinilai:
  skor/akurasi + pembahasan tiap jawaban; riwayat masuk ke Nilai & Laporan
- **Nilai** - riwayat skor & akurasi per kelas
- **Laporan** - rekap progres & statistik belajar
- **Pilih Paket** - pilih paket berlangganan, checkout simulasi (VA/QRIS/Transfer) →
  paket aktif + akses kategori kelas (anti-duplikat enroll)
- **Kontak** - form kirim pesan (rate-limited)
- **Pengaturan profil** - ubah nama, email, foto (upload via file → dataURL), sekolah,
  kelas/jurusan, bio, ganti password, serta preferensi notifikasi (localStorage).
  Catatan: toggle 2FA tersimpan di DB namun belum divalidasi saat login.

### 👩‍🏫 Role Guru & Admin
> Akses berlapis role via middleware `role:guru,admin` (siswa yang mencoba membuka
> halaman guru akan mendapat **403 Forbidden**). **Paket** & **hapus akun siswa**
> hanya bisa diakses role **admin** (`role:admin`).
- **Dashboard statistik** - jumlah kelas, materi, paket, siswa, dan pendaftaran
- **Kelola Kelas** - CRUD lengkap (tambah/edit/hapus/aktif-nonaktif)
- **Kelola Materi** - CRUD + filter per kelas
- **Kelola Soal** - CRUD bank soal per kelas & set (opsi A-D, kunci, pembahasan, aktif)
- **Kelola Paket** *(admin)* - CRUD (harga, kuota, daftar fitur)
- **Daftar Siswa** - lihat profil + kelas yang diikuti (semua role); hapus akun siswa *(admin)*
- **Pengampu** *(admin)* - assign guru pengampu per mapel/kelas

---

## 🔑 Akun Demo

| Role  | Email          | Password  | Profil                                  |
|-------|----------------|-----------|-----------------------------------------|
| Siswa | `siswa@demo.id` | `password` | Brian Pratama - SMA 1 Jakarta, Kelas 12 IPA, paket `sma-ekstra` (sudah ikut 3 kelas) |
| Guru  | `guru@demo.id`  | `password` | Rina Kumala, M.Si. - pengelola konten   |
| Admin | `admin@demo.id` | `password` | Operator PintarKuy - paket & akun siswa |

> Kredensial demo tertera langsung di halaman **Masuk** (satu form - role diarahkan otomatis dari email).

### 👨‍🏫 Buat Akun Guru (operator)

Public sign-up hanya untuk **siswa** (anti spam akun guru). Akun guru dibuat manual oleh operator:

```bash
php artisan akun:guru --nama="Nama Guru" --email="guru@baru.id"
```

Atau interaktif (password otomatis digenerate & tampil sekali):

```bash
php artisan akun:guru
```

> Akun guru akan menuju **Dashboard Guru** saat login dan bisa mengelola kelas/materi.
> Tambahkan flag `--admin` untuk akun **admin** (bisa mengelola paket & menghapus akun siswa):

```bash
php artisan akun:guru --admin --nama="Operator" --email="admin@baru.id"
```

---

## ⚙️ Instalasi Lokal

Persyaratan: PHP 8.1+, Composer, Node.js, MySQL.

```bash
# 1. Dependency
composer install
npm install

# 2. Environment
cp .env.example .env        # Windows: copy .env.example .env
php artisan key:generate

# 3. Konfigurasi database di .env
#   DB_DATABASE=pintarkuy
#   DB_USERNAME=root
#   DB_PASSWORD=

# 4. Buat database
mysql -u root -e "CREATE DATABASE pintarkuy CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 5. Migrasi + seed demo
php artisan migrate:fresh --seed

# 6. Kompilasi aset
npm run build               # atau npm run dev (vite dev server)

# 7. Jalankan
php artisan serve           # http://127.0.0.1:8000
```

Setelah `migrate:fresh --seed`, akses demo dengan akun pada tabel di atas.

---

## 🗄️ Struktur Database

- **users** - `role` (siswa/guru/admin), `foto` (text/dataURL), `sekolah`, `kelas_jurusan`, `bio`, `two_factor_enabled`
- **kelas** - nama, slug (unik), kategori, ikon, deskripsi, jumlah modul, durasi, harga, `aktif`, warna
- **materi** - `kelas_id` (FK), judul, tutor, pertemuan, durasi, `bab`, `urutan`, tipe
- **paket** - `key` (unik), nama, tag, harga, `kategori` (JSON), `fitur` (JSON), `aktif`
- **soal** - `kelas_id`/`materi_id` (FK), pertanyaan, `opsi` (JSON), `kunci`, pembahasan, `set_label`, `urutan`, `aktif`
- **pengerjaan** - sesi mengerjakan latsol (user/kelas, `set_label`, `tipe`, skor, akurasi, benar, total)
- **jawaban** - jawaban per soal dalam satu pengerjaan (`soal_id`, `pilihan`, `benar`)
- **nilai** - rekapitulasi skor/akurasi per user + kelas + tanggal
- **pendaftaran** - `user_id` + `kelas_id` **(unik)** - mencegah pendaftaran ganda
- **pengampu** - relasi guru ↔ kelas yang diampu
- **progres_modul** - progres materi per user (`bab_cur`)
- **kontak** - pesan dari form kontak
- **user_paket** - pivot user ↔ paket (many-to-many)
- Catatan: `password_reset_tokens`, `personal_access_tokens`, `failed_jobs` sudah ada (infrastruktur Laravel), tapi fitur email verify/lupa password belum diaktifkan.

Seeder `DemoSeeder` mengisi (±): 3 user demo, 27 kelas katalog, 162 materi (6 per kelas),
135 soal (5 per kelas), 3 paket, 3 pendaftaran, dan guru demo sebagai pengampu fisika & kimia.

---

## 🔧 Backend: Handover Tim

### ✅ Sudah Dikerjakan

| # | Item | Detail |
|---|------|--------|
| 1 | Autentikasi DB (login/register) | Session-based; email + password |
| 2 | 3 role aktif | `siswa` · `guru` · `admin` (kolom `role` string) |
| 3 | Middleware `role:` | Variadic - `role:guru,admin`, `role:admin` |
| 4 | CRUD Kelas (guru & admin) | Tambah / edit / hapus / toggle aktif |
| 5 | CRUD Materi (guru & admin) | Filter per kelas, CRUD penuh |
| 6 | Kelola Paket (admin-only) | CRUD paket; kuota disimpan di DB |
| 7 | Kelola Siswa (admin-only) | Lihat profil + kelas; hapus akun siswa (admin) |
| 8 | Enroll + kuota dari DB | `Paket::where('key')->value('kuota')` - tidak hardcode |
| 9 | Checkout paket | `POST /pilih-paket/bayar` → `paket.bayar` (pilih VA/QRIS/Transfer, `syncWithoutDetaching`, anti-duplikat) |
| 10 | Profil siswa (foto) | Validasi `max:3MB` + regex `data:image/(png\|jpeg\|webp\|gif);base64,` atau URL - **SVG diblokir** |
| 11 | `php artisan akun:guru --admin` | Flag `--admin` untuk role admin |
| 12 | Throttle login | `throttle:5,1` pada POST `/masuk` & `/daftar` |
| 13 | XSS hardening (katalog JS) | `esc()` helper; interpolasi HTML/JSON menggunakan `@json()` |
| 14 | Foto kolom → TEXT | Migration via raw `ALTER TABLE` (tanpa doctrine/dbal) |
| 15 | Latsol & scoring | `pengerjaan`/`jawaban`/`nilai` ditulis dalam satu transaksi; skor = akurasi = benar/total × 100 |
| 16 | CRUD Soal (guru & admin) | Bank soal per kelas & `set_label`, filter per materi |
| 17 | Pengampu mapel (admin) | Assign guru → kelas diampu (guru tidak boleh diampu = 403 saat akses) |
| 18 | Nilai & Laporan DB-backed | Dari `nilai`/`pengerjaan` (`dashboard.nilai`, `dashboard.laporan`) |
| 19 | Kontak | `POST /kontak` (throttle 5,1, CRLF guard) → tabel `kontak` |
| 20 | Halaman `/kelas` publik | Dari DB (`HalamanController@kelas`) termasuk harga per kategori |
| 21 | DemoSeeder diperluas | Kelas/materi/soal/pengampu/pendaftaran idempotent; guru demo diampu fisika & kimia |

### ⬜ BELUM / Menunggu Tim Lain

| # | Item | Catatan |
|---|------|---------|
| 1 | Email verify / lupa password | Belum diimplementasi (tabel `password_reset_tokens` sudah ada) |
| 2 | Audit log | Belum ada logging aksi penting |
| 3 | Ownership multi-guru | Kelas bisa diedit guru mana saja (belum di-scope per guru) |
| 4 | Aset eksternal (Figma) | Ikon & gambar dari CDN / asset publik |
| 5 | Counter `kelas.siswa` | Jumlah siswa per kelas dihitung manual dari `pendaftaran` |
| 6 | Race condition TOCTOU | Enroll + kuota belum pakai database lock/transaction |
| 7 | Password di console | `akun:guru` output password plaintext ke terminal |
| 8 | Config deploy | Sudah live di **Vercel** (`vercel.json` + vercel-php + TiDB Cloud), tapi `APP_DEBUG`/`APP_ENV`/session driver produksi **belum diaudit** (masih `file` session di serverless) |
| 9 | 2FA login | Toggle 2FA tersimpan di DB tapi belum dicek saat login |

---

## 🛠️ Alat AI yang Digunakan (per §5.2)

Pengembangan dibantu oleh asisten AI kode (`opencode`):
- **opencode** - scaffolding backend (controller, middleware, migrasi, model, routes),
  perbaikan bug UI (pengaturan profil, foto, katalog), dan penulisan seeder demo.
- **Claude / AI chat model** - asisten penulisan & evaluasi kode front-end.
- **GitHub Copilot** *(jika digunakan tim)* - autocomplete saat menulis komponen UI.
- Framework & library utama: **Laravel 10**, **MySQL 8**, **Tailwind CSS**, **Vite**, **Alpine.js (vanilla JS lokal)**.

---

## 🔒 Privasi (per §8.1)

- Data demo **bukan data pribadi nyata** - semua profil contoh digenerate untuk keperluan demo.
- Foto profil disimpan sebagai data tersemat (dataURL) di server demo; pada produksi
  disarankan penyimpanan file + enkripsi & kebijakan privasi terpisah.
- Preferensi UI (toggle notifikasi, dsb.) disimpan di `localStorage` perangkat pengguna.

---

## 📁 Struktur Proyek (ringkas)

```
app/Http/Controllers/
├── DashboardController.php      # halaman siswa + enroll + latsol + profil
├── HalamanController.php        # halaman publik (kelas dari DB)
├── KontakController.php         # form kontak (throttle + CRLF guard)
├── PaketPilihanController.php   # pilih paket & checkout simulasi
├── Guru/                        # Dashboard, Kelas, Materi, Soal, Paket, Siswa, Pengampu
├── Auth/                        # Login, Register, Logout
resources/views/
├── halaman/                     # landing, tentang, kelas, kontak
├── dashboard/                   # katalog, kelas, materi, latsol, nilai, laporan, pengaturan
├── guru/                        # dashboard + CRUD kelas/materi/soal/paket/siswa/pengampu
database/migrations + seeders/   # skema database & DemoSeeder
```