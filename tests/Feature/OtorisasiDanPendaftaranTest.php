<?php

namespace Tests\Feature;

use App\Models\Kelas;
use App\Models\Materi;
use App\Models\Paket;
use App\Models\Pendaftaran;
use App\Models\ProgresModul;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Tests\TestCase;

class OtorisasiDanPendaftaranTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(VerifyCsrfToken::class);
    }

    protected function createSiswa(string $email = null): User
    {
        $user = new User([
            'name' => 'Siswa Test',
            'email' => $email ?? 'siswa-' . uniqid() . '@test.id',
        ]);
        $user->role = 'siswa';
        $user->password = 'password';
        $user->save();

        return $user;
    }

    protected function createStaff(string $role, string $email = null): User
    {
        $user = new User([
            'name' => ucfirst($role) . ' Test',
            'email' => $email ?? $role . '-' . uniqid() . '@test.id',
        ]);
        $user->role = $role;
        $user->password = 'password';
        $user->save();

        return $user;
    }

    protected function createPaket(array $overrides = []): Paket
    {
        $data = array_merge([
            'key' => 'utbk-' . uniqid(),
            'nama' => 'UTBK Pro',
            'tag' => 'Best Seller',
            'kuota' => 3,
            'harga' => 399000,
            'harga_lama' => 499000,
            'kategori' => ['UTBK-SNBT'],
            'fitur' => ['Bank soal', 'Konsultasi'],
            'aktif' => true,
        ], $overrides);

        return Paket::query()->updateOrCreate(['key' => $data['key']], $data);
    }

    protected function createKelas(array $overrides = []): Kelas
    {
        return Kelas::create(array_merge([
            'slug' => 'siswa-' . uniqid(),
            'name' => 'Kelas Test',
            'cat' => 'UTBK-SNBT',
            'modul' => 8,
            'durasi' => '8 Minggu',
            'siswa' => 0,
            'price' => 399000,
            'old' => 499000,
            'aktif' => true,
        ], $overrides));
    }

    // ------------------------------------------------------------------
    //  Login / Logout
    // ------------------------------------------------------------------

    public function test_login_berhasil_dan_redirect_sesuai_role(): void
    {
        foreach (['siswa', 'guru', 'admin'] as $role) {
            $user = $role === 'siswa'
                ? $this->createSiswa('login-' . $role . '@test.id')
                : $this->createStaff($role, 'login-' . $role . '@test.id');

            $response = $this->post('/masuk', [
                'email' => $user->email,
                'password' => 'password',
            ]);

            $response->assertStatus(302);
            $this->assertAuthenticatedAs($user);

            $expectedUrl = in_array($role, ['guru', 'admin'], true)
                ? route('guru.dashboard')
                : route('dashboard');

            $response->assertRedirect($expectedUrl);

            $this->post('/logout');
            $this->assertGuest();
        }
    }

    public function test_login_salah_password_redirect_back_dengan_error(): void
    {
        $user = $this->createSiswa('salah@test.id');

        $response = $this->post('/masuk', [
            'email' => $user->email,
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(302);
        $response->assertInvalid('email');
        $this->assertGuest();
    }

    public function test_logout_hapus_sesi(): void
    {
        $user = $this->createSiswa();

        $this->post('/masuk', ['email' => $user->email, 'password' => 'password']);
        $this->assertAuthenticatedAs($user);

        $this->post('/logout');
        $this->assertGuest();

        $response = $this->get('/dashboard');
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    // ------------------------------------------------------------------
    //  Otorisasi (Authorization Matrix)
    // ------------------------------------------------------------------

    public function test_siswa_bisa_akses_dashboard_siswa(): void
    {
        $siswa = $this->createSiswa();
        $paket = $this->createPaket();
        $siswa->pakets()->attach($paket->id);
        $this->actingAs($siswa);

        $this->get('/dashboard')->assertOk();
    }

    public function test_siswa_dilarang_akses_dashboard_guru(): void
    {
        $siswa = $this->createSiswa();
        $this->actingAs($siswa);

        $this->get('/dashboard-guru')->assertStatus(403);
    }

    public function test_guru_bisa_akses_dashboard_guru(): void
    {
        $guru = $this->createStaff('guru');
        $this->actingAs($guru);

        $this->get('/dashboard-guru')->assertOk();
    }

    public function test_admin_bisa_akses_dashboard_guru(): void
    {
        $admin = $this->createStaff('admin');
        $this->actingAs($admin);

        $this->get('/dashboard-guru')->assertOk();
    }

    public function test_guru_redirect_dari_dashboard_siswa(): void
    {
        $guru = $this->createStaff('guru');
        $this->actingAs($guru);

        $this->get('/dashboard')->assertRedirect(route('guru.dashboard'));
    }

    public function test_tamu_redirect_ke_login(): void
    {
        $this->assertGuest();

        $this->get('/dashboard')->assertRedirect(route('login'));
        $this->get('/dashboard/kelas')->assertRedirect(route('login'));
        $this->get('/dashboard/nilai')->assertRedirect(route('login'));
    }

    // ------------------------------------------------------------------
    //  Pendaftaran Kelas (Enrollment)
    // ------------------------------------------------------------------

    public function test_daftar_kelas_berhasil(): void
    {
        $paket = $this->createPaket();
        $siswa = $this->createSiswa();
        $siswa->pakets()->attach($paket->id);
        $kelas = $this->createKelas();

        $this->actingAs($siswa);

        $response = $this->postJson('/dashboard/katalog/daftar', [
            'kelas_id' => $kelas->id,
        ]);

        $response->assertOk()->assertJson([
            'ok' => true,
        ]);

        $this->assertDatabaseHas('pendaftaran', [
            'user_id' => $siswa->id,
            'kelas_id' => $kelas->id,
        ]);
    }

    public function test_daftar_kelas_duplikat_ditolak(): void
    {
        $paket = $this->createPaket();
        $siswa = $this->createSiswa();
        $siswa->pakets()->attach($paket->id);
        $kelas = $this->createKelas();

        Pendaftaran::create(['user_id' => $siswa->id, 'kelas_id' => $kelas->id]);

        $this->actingAs($siswa);

        $response = $this->postJson('/dashboard/katalog/daftar', [
            'kelas_id' => $kelas->id,
        ]);

        $response->assertOk()->assertJson([
            'ok' => false,
            'message' => 'Kamu sudah terdaftar di kelas ini.',
        ]);
    }

    public function test_daftar_kelas_di_luar_kategori_paket_ditolak(): void
    {
        $paket = $this->createPaket(['key' => 'bahasa-' . uniqid(), 'kategori' => ['Bahasa']]);
        $siswa = $this->createSiswa();
        $siswa->pakets()->attach($paket->id);

        $kelas = $this->createKelas(['cat' => 'UTBK-SNBT']);

        $this->actingAs($siswa);

        $response = $this->postJson('/dashboard/katalog/daftar', [
            'kelas_id' => $kelas->id,
        ]);

        $response->assertOk()->assertJson([
            'ok' => false,
        ]);

        $this->assertDatabaseMissing('pendaftaran', [
            'user_id' => $siswa->id,
            'kelas_id' => $kelas->id,
        ]);
    }

    public function test_staff_tidak_bisa_daftar_kelas(): void
    {
        $guru = $this->createStaff('guru');
        $kelas = $this->createKelas();

        $this->actingAs($guru);

        $this->postJson('/dashboard/katalog/daftar', ['kelas_id' => $kelas->id])
            ->assertOk()->assertJson(['ok' => false]);
    }

    // ------------------------------------------------------------------
    //  Nilai / Materi - bab_cur dinamis
    // ------------------------------------------------------------------

    public function test_bab_cur_dinamis_dari_progres_modul(): void
    {
        $siswa = $this->createSiswa();
        $kelas = Kelas::query()->updateOrCreate(['slug' => 'matematika'], [
            'name' => 'Kelas Test',
            'cat' => 'UTBK-SNBT',
            'modul' => 10,
            'durasi' => '8 Minggu',
            'price' => 399000,
            'old' => 499000,
            'aktif' => true,
        ]);

        Pendaftaran::create(['user_id' => $siswa->id, 'kelas_id' => $kelas->id]);

        $materiIds = $kelas->materi()->orderBy('urutan')->pluck('id');
        foreach ($materiIds->take(2) as $materiId) {
            ProgresModul::query()->create([
                'user_id' => $siswa->id,
                'materi_id' => $materiId,
            ]);
        }

        $this->actingAs($siswa);
        $response = $this->get('/dashboard/materi');
        $response->assertOk();

        $courses = $response->viewData('courses');
        $this->assertArrayHasKey('matematika', $courses);
        $this->assertEquals(3, $courses['matematika']['bab_cur']);
    }

    public function test_bab_cur_tidak_melesbihi_modul(): void
    {
        $siswa = $this->createSiswa();
        $kelas = Kelas::query()->updateOrCreate(['slug' => 'python'], [
            'name' => 'Kelas Test',
            'cat' => 'Ekstra',
            'modul' => 3,
            'durasi' => '8 Minggu',
            'price' => 399000,
            'old' => 499000,
            'aktif' => true,
        ]);

        Pendaftaran::create(['user_id' => $siswa->id, 'kelas_id' => $kelas->id]);

        $materiIds = $kelas->materi()->orderBy('urutan')->pluck('id');
        foreach ($materiIds as $materiId) {
            ProgresModul::query()->create([
                'user_id' => $siswa->id,
                'materi_id' => $materiId,
            ]);
        }

        $this->actingAs($siswa);
        $response = $this->get('/dashboard/materi');
        $response->assertOk();

        $courses = $response->viewData('courses');
        $modulTotal = $kelas->materi()->count();
        $this->assertEquals($modulTotal, $courses['python']['bab_cur']);
        $this->assertLessThanOrEqual($modulTotal, $courses['python']['bab_cur']);
    }

    // ------------------------------------------------------------------
    //  Halaman Publik
    // ------------------------------------------------------------------

    public function test_halaman_publik_tersedia(): void
    {
        $this->get('/')->assertOk();
        $this->get('/tentang')->assertOk();
        $this->get('/kontak')->assertOk();
        $this->get('/masuk')->assertOk();
        $this->get('/daftar')->assertOk();
    }

    // ------------------------------------------------------------------
    //  Keamanan email - tolak CRLF injection (CVE-2026-48019)
    // ------------------------------------------------------------------

    public function test_email_mengandung_crlf_ditolak_di_semua_jalur(): void
    {
        $payloads = [
            ['/masuk', 'email', [
                'email' => "bob@test.id\r\nBcc: spam@evil.test",
                'password' => 'password',
            ]],
            ['/daftar', 'email', [
                'name' => 'Bob',
                'email' => "bob@test.id\r\nBcc: spam@evil.test",
                'password' => 'rahasia123',
                'password_confirmation' => 'rahasia123',
                'sekolah' => 'SMA Negeri 1',
                'kelas_jurusan' => 'IPA',
            ]],
            ['/kontak', 'email', [
                'nama' => 'Bob',
                'email' => "bob@test.id\nSubject: x",
                'subjek' => 'Halo',
                'pesan' => 'Pesan panjang untuk kontak.',
            ]],
        ];

        foreach ($payloads as [$uri, $field, $data]) {
            $response = $this->post($uri, $data);
            $response->assertSessionHasErrors($field);
            $this->assertGuest();
        }
    }

    public function test_email_mengandung_crlf_ditolak_saat_update_profil(): void
    {
        $siswa = $this->createSiswa();
        $this->actingAs($siswa);

        $response = $this->post('/dashboard/pengaturan', [
            'name' => 'Siswa Test',
            'email' => "siswa@test.id\r\nBcc: spam@evil.test",
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertDatabaseMissing('users', ['email' => "siswa@test.id\r\nBcc: spam@evil.test"]);
    }
}
