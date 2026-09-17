# Project Tracker — PintarKuy

> Bimbel adaptif UTBK-SNBT — Laravel 10 + MySQL + Tailwind (Vite)
> Hackathon "Tour 2 - #Nation Hackathon" · Periode 10–18 September 2026

---

## 🎯 Status Ringkas

| Item | Status |
|------|--------|
| Tahap | **Final / Demo-ready** (hari terakhir hackathon) |
| Backend | ✅ Selesai |
| Frontend | ✅ Selesai |
| Fitur inti | ✅ Lengkap |
| Pengujian | ✅ Feature/Unit test lulus |
| Deployment Vercel | ⚠️ Siap tapi belum final diuji produksi |

---

## 📅 Timeline & Milestone

| Milestone | Target | Status |
|-----------|--------|--------|
| Scaffold Laravel + env + database | 10 Sep | ✅ |
| Autentikasi 3 role (siswa/guru/admin) | 12 Sep | ✅ |
| CRUD Kelas, Materi, Paket, Soal | 13 Sep | ✅ |
| Katalog kelas (DB) + enroll + kuota | 14 Sep | ✅ |
| Enroll + checkout paket (simulasi) | 15 Sep | ✅ |
| Latsol & scoring + Nilai & Laporan | 16 Sep | ✅ |
| UI rebrand "ledger print" + landing | 17 Sep | ✅ |
| Security hardening + test suite | 17 Sep | ✅ |
| **Demo / penyerahan** | **18 Sep** | 🔜 |

---

## ✅ Checklist Fitur

### Role Siswa
- [x] Registrasi & login (session-based, throttled: 5/menit)
- [x] Katalog kelas (DB) + search + filter kategori
- [x] Daftar kelas (cek kuota paket, anti-duplikat)
- [x] Kelas saya + progres modul (`progres_modul.bab_cur`)
- [x] Materi/silabus per kelas dari DB
- [x] Latsol: bank soal per set, scoring instan + pembahasan
- [x] Nilai (riwayat skor & akurasi)
- [x] Laporan (rekap progres & statistik)
- [x] Pilih paket + checkout simulasi (VA/QRIS/Transfer, anti-duplikat)
- [x] Kontak (throttle + CRLF guard)
- [x] Pengaturan profil (foto compressed → dataURL, ganti password, preferensi localStorage)
- [ ] **2FA toggle belum di-enforce saat login** (tersimpan di DB)

### Role Guru (dengan Admin)
- [x] Dashboard statistik
- [x] Kelola Kelas (CRUD + toggle aktif)
- [x] Kelola Materi (CRUD + filter kelas)
- [x] Kelola Soal (CRUD + filter materi/set)
- [x] Middleware `role:guru,admin` → 403 untuk siswa

### Role Admin (saja)
- [x] Kelola Paket (CRUD: harga, kuota, fitur)
- [x] Daftar Siswa + hapus akun
- [x] Pengampu (assign guru ↔ kelas)
- [x] `php artisan akun:guru --admin`

### Pubilc / Marketing
- [x] Landing (hero, statistik, testimoni, quiz interaktif)
- [x] Tentang, Kelas (dari DB), Kontak

### Keamanan
- [x] Throttle login/daftar/kontak/enroll/checkout
- [x] XSS hardening (helper `esc()`, `@json()`, anti-CRLF)
- [x] Validasi foto (blokir SVG, max 3MB, regex dataURL)
- [x] Env produksi (TiDB) tidak di-commit

---

## 🔧 Backend Handover (Rekap terakhir)

| # | Item | Detail |
|---|------|--------|
| 1 | Autentikasi DB | Session-based, email + password |
| 2 | 3 role aktif | `siswa` · `guru` · `admin` |
| 3 | Middleware variadic | `role:guru,admin` · `role:admin` |
| 4 | CRUD Kelas | Tambah/edit/hapus/toggle aktif |
| 5 | CRUD Materi | Filter per kelas |
| 6 | Kelola Paket (admin) | Harga, kuota dari DB |
| 7 | Kelola Siswa (admin) | Lihat profil + hapus akun |
| 8 | Enroll + kuota | Tidak hardcode, kuota dari DB |
| 9 | Checkout paket | VA/QRIS/Transfer, `syncWithoutDetaching` |
| 10 | Foto profil | RegEx dataURL, SVG diblokir |
| 11 | `akun:guru --admin` | Pembuatan akun operator |
| 12 | Throttle | 5 percobaan/menit pada auth & form |
| 13 | XSS hardening | `esc()` + `@json()` |
| 14 | Latsol & scoring | Transaksi DB: pengerjaan/jawaban/nilai |
| 15 | CRUD Soal | Per kelas & set, filter materi |
| 16 | Pengampu | Assign guru → kelas diampu |
| 17 | Nilai & Laporan | DB-backed |
| 18 | Kontak | Throttle + CRLF guard |
| 19 | `/kelas` publik | Dari DB |
| 20 | DemoSeeder | Idempotent, 27 kelas/162 materi/135 soal |

---

## 🐛 Isu Terbuka / Blocker

| # | Isu | Prioritas | Status |
|---|-----|-----------|--------|
| 1 | Toggle **2FA** tersimpan di DB tapi belum divalidasi saat login | Rendah | Terbuka |
| 2 | Email verification & forgot/reset password belum aktif (tabel sudah ada) | Rendah | Terbuka |
| 3 | **Pembayaran masih simulasi** — belum ada integrasi payment gateway | Rendah | Terbuka |
| 4 | `public/build` di-git-ignore → wajib `npm run build` di deploy | — | Catatan |
| 5 | Foto profil dataURL di DB (limitasi; produksi: file storage + enkripsi) | — | Catatan |
| 6 | Verifikasi akhir deployment Vercel (cache `/tmp`, TiDB) | Sedang | Terbuka |

---

## 🚀 Next Action (23–24 jam terakhir)

- [ ] Run ulang `php artisan test` → pastikan semua lulus
- [ ] `npm run build` → aset fresh untuk demo
- [ ] Demo run: login semua role, enroll, latsol, checkout, CRUD guru
- [ ] (Opsional) Uji hands-on deploy Vercel + seed TiDB
- [ ] Finalisasi README & materi penyerahan (sesuai §5.2 / §8.1)
- [ ] **Presentasi demo** — 18 September 2026

---

## 🧩 Arsitektur Cepat

```
app/Http/Controllers/
├── DashboardController.php      # siswa: katalog, enroll, latsol, nilai, laporan, profil
├── HalamanController.php        # publik: /kelas dari DB
├── KontakController.php         # kontak (throttle)
├── PaketPilihanController.php   # pilih paket & checkout
├── Guru/                        # dashboard + CRUD (kelas/materi/soal/paket/siswa/pengampu)
└── Auth/                        # login / register / logout

resources/views/
├── halaman/  → landing, tentang, kelas, kontak
├── dashboard/ → 10 halaman siswa
└── guru/      → 7 seksi CRUD
```

---

## 🔑 Akun Demo

| Role  | Email          | Password  | Profil |
|-------|----------------|-----------|--------|
| Siswa | `siswa@demo.id` | `password` | Brian Pratama — SMA 1 Jakarta, paket `sma-ekstra`, 3 kelas |
| Guru  | `guru@demo.id`  | `password` | Rina Kumala, M.Si. — pengampu fisika & kimia |
| Admin | `admin@demo.id` | `password` | Operator PintarKuy |

---

_File diperbarui: 17 September 2026 · dirawat sepanjang hackathon._