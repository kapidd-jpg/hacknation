<?php

namespace Tests\Feature;

use App\Models\Kelas;
use App\Models\Materi;
use App\Models\Pendaftaran;
use App\Models\ProgresModul;
use App\Models\Soal;
use App\Models\User;
use App\Services\LatsolService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Tests\TestCase;

class LatsolProgresGateTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(VerifyCsrfToken::class);
        config(['latsol.passing_threshold' => 60]);
    }

    protected function createSiswa(): User
    {
        $user = new User(['name' => 'Siswa Test', 'email' => 'latsol-' . uniqid() . '@test.id']);
        $user->role = 'siswa';
        $user->password = 'password';
        $user->save();

        return $user;
    }

    /**
     * Kelas + 1 materi + set latsol 3 soal (kunci A/B/C).
     *
     * @return array{kelas: Kelas, materi: Materi, soal: array<int, Soal>}
     */
    protected function seedSetLatsol(string $set = 'Latihan Bab 1'): array
    {
        $kelas = Kelas::create([
            'slug' => 'latsol-' . uniqid(),
            'name' => 'Kelas Latsol Test',
            'cat' => 'UTBK-SNBT',
            'modul' => 6,
            'durasi' => '8 Minggu',
            'siswa' => 0,
            'price' => 399000,
            'old' => 499000,
            'aktif' => true,
        ]);

        $materi = $kelas->materi()->create([
            'judul' => 'Konsep Dasar Test',
            'pertemuan' => 1,
            'durasi' => '45 Menit',
            'bab' => 'Bab 1',
            'urutan' => 1,
            'tipe' => 'video_teks',
        ]);

        $soals = [];
        foreach ([[0, 'A', 'Soal 1'], [1, 'B', 'Soal 2'], [2, 'C', 'Soal 3']] as [$kunci, $huruf, $text]) {
            $soals[] = Soal::create([
                'kelas_id' => $kelas->id,
                'materi_id' => $materi->id,
                'set_label' => $set,
                'pertanyaan' => $text,
                'opsi' => ['A' => 'Jaw A ' . $huruf, 'B' => 'Jaw B ' . $huruf, 'C' => 'Jaw C ' . $huruf, 'D' => 'Jaw D ' . $huruf],
                'kunci' => $kunci,
                'pembahasan' => 'Pembahasan ' . $text,
                'urutan' => $kunci + 1,
                'aktif' => true,
            ]);
        }

        return ['kelas' => $kelas, 'materi' => $materi, 'soal' => $soals];
    }

    protected function enroll(User $siswa, Kelas $kelas): void
    {
        Pendaftaran::create(['user_id' => $siswa->id, 'kelas_id' => $kelas->id]);
    }

    protected function kirimAttempt(User $siswa, Kelas $kelas, array $jawaban)
    {
        $this->actingAs($siswa);

        return $this->post('/dashboard/latsol/kirim', [
            'kelas_id' => $kelas->id,
            'set' => 'Latihan Bab 1',
            'jawaban' => $jawaban,
            'waktu_mulai' => now()->toISOString(),
        ]);
    }

    public function test_attempt_menghitung_skor_akurasi_dan_status_lengkap(): void
    {
        $siswa = $this->createSiswa();
        ['kelas' => $kelas] = $this->seedSetLatsol();
        $this->enroll($siswa, $kelas);

        $this->kirimAttempt($siswa, $kelas, []); // semua kosong

        // 3 soal, semua kosong → skor 0, akurasi 0, kosong 3
        $this->assertDatabaseHas('pengerjaan', [
            'user_id' => $siswa->id,
            'kelas_id' => $kelas->id,
            'set_label' => 'Latihan Bab 1',
            'benar' => 0,
            'salah' => 0,
            'kosong' => 3,
            'total' => 3,
            'skor' => 0,
            'akurasi' => 0,
        ]);

        $pengerjaan = \App\Models\Pengerjaan::query()
            ->where('user_id', $siswa->id)
            ->where('kelas_id', $kelas->id)
            ->first();

        $this->assertDatabaseHas('nilai', [
            'user_id' => $siswa->id,
            'kelas_id' => $kelas->id,
            'skor' => 0,
        ]);

        $this->assertSame(3, $pengerjaan->jawaban()->count());
        $this->assertSame(3, $pengerjaan->jawaban()->where('status', 'kosong')->count());
    }

    public function test_skor_dan_akurasi_berbeda_ketika_ada_kosong(): void
    {
        $siswa = $this->createSiswa();
        ['kelas' => $kelas, 'soal' => $soal] = $this->seedSetLatsol();

        // 2 benar (soal 1 & 2), 1 kosong (soal 3)
        $this->enroll($siswa, $kelas);
        $this->kirimAttempt($siswa, $kelas, [
            $soal[0]->id => 0,
            $soal[1]->id => 1,
        ]);

        $this->assertDatabaseHas('pengerjaan', [
            'user_id' => $siswa->id,
            'benar' => 2,
            'salah' => 0,
            'kosong' => 1,
            'total' => 3,
            'skor' => 67,        // round(2/3*100)
            'akurasi' => 100,    // 2/2 jawab
        ]);
    }

    public function test_akurasi_menghitung_salah_tanpa_minus(): void
    {
        $siswa = $this->createSiswa();
        ['kelas' => $kelas, 'soal' => $soal] = $this->seedSetLatsol();

        // 1 benar, 2 salah
        $this->enroll($siswa, $kelas);
        $this->kirimAttempt($siswa, $kelas, [
            $soal[0]->id => 0,
            $soal[1]->id => 0,
            $soal[2]->id => 1,
        ]);

        $this->assertDatabaseHas('pengerjaan', [
            'user_id' => $siswa->id,
            'benar' => 1,
            'salah' => 2,
            'kosong' => 0,
            'skor' => 33,   // round(1/3*100), tanpa minus
            'akurasi' => 33,
        ]);
    }

    public function test_get_nilai_terkini_mengembalikan_attempt_paling_baru(): void
    {
        $siswa = $this->createSiswa();
        ['kelas' => $kelas, 'materi' => $materi] = $this->seedSetLatsol();
        $this->enroll($siswa, $kelas);

        $service = new LatsolService();

        $soals = $kelas->soal()->orderBy('urutan')->get();
        $semuaBenar = $soals->mapWithKeys(fn ($s) => [$s->id => $s->kunci])->all();
        $satuBenar = [$soals->first()->id => $soals->first()->kunci];

        $service->buatAttempt($siswa, $kelas->id, 'Latihan Bab 1', $satuBenar);
        $service->buatAttempt($siswa, $kelas->id, 'Latihan Bab 1', $semuaBenar);

        $histori = $service->getHistoriAttempt($siswa->id, $kelas->id, 'Latihan Bab 1');
        $this->assertCount(2, $histori);

        $terkini = $service->getNilaiTerkini($siswa->id, $kelas->id, 'Latihan Bab 1');
        $this->assertSame(100, $terkini->skor);

        $terkiniPerMateri = $service->getNilaiTerkiniPerMateri($siswa->id, $materi->id);
        $this->assertSame($terkini->id, $terkiniPerMateri->id);
    }

    public function test_gate_progres_menolak_belum_tuntas(): void
    {
        $siswa = $this->createSiswa();
        ['kelas' => $kelas, 'materi' => $materi, 'soal' => $soal] = $this->seedSetLatsol();
        $this->enroll($siswa, $kelas);

        // 1 benar dari 3 → skor 33 < threshold 60
        $this->kirimAttempt($siswa, $kelas, [$soal[0]->id => 0]);

        $this->actingAs($siswa);
        $response = $this->postJson('/dashboard/progres-modul', ['materi_id' => $materi->id]);

        $response->assertStatus(422);
        $response->assertJson(['ok' => false, 'belum_tuntas' => true]);
        $this->assertDatabaseMissing('progres_modul', [
            'user_id' => $siswa->id,
            'materi_id' => $materi->id,
        ]);
    }

    public function test_gate_progres_menolak_saat_belum_pernah_attempt(): void
    {
        $siswa = $this->createSiswa();
        ['kelas' => $kelas, 'materi' => $materi] = $this->seedSetLatsol();
        $this->enroll($siswa, $kelas);

        $this->actingAs($siswa);
        $this->postJson('/dashboard/progres-modul', ['materi_id' => $materi->id])
            ->assertStatus(422)
            ->assertJson(['ok' => false]);
    }

    public function test_gate_progres_membolehkan_saat_skor_mencapai_threshold(): void
    {
        $siswa = $this->createSiswa();
        ['kelas' => $kelas, 'materi' => $materi, 'soal' => $soal] = $this->seedSetLatsol();
        $this->enroll($siswa, $kelas);

        // 2 benar dari 3 → skor 67 >= threshold 60 → lulus
        $this->kirimAttempt($siswa, $kelas, [$soal[0]->id => 0, $soal[1]->id => 1]);

        $this->actingAs($siswa);
        $this->postJson('/dashboard/progres-modul', ['materi_id' => $materi->id])
            ->assertOk()
            ->assertJson(['ok' => true, 'completed' => true]);

        $this->assertDatabaseHas('progres_modul', [
            'user_id' => $siswa->id,
            'materi_id' => $materi->id,
        ]);
    }

    public function test_materi_tanpa_set_latsol_tetap_bisa_ditandai_selesai(): void
    {
        $siswa = $this->createSiswa();
        $kelas = Kelas::create([
            'slug' => 'latsol-bebas-' . uniqid(),
            'name' => 'Kelas Bebas',
            'cat' => 'Bahasa',
            'modul' => 4,
            'durasi' => '8 Minggu',
            'siswa' => 0,
            'price' => 399000,
            'old' => 499000,
            'aktif' => true,
        ]);
        $materi = $kelas->materi()->create([
            'judul' => 'Materi Tanpa Soal',
            'pertemuan' => 1,
            'bab' => 'Bab 1',
            'urutan' => 1,
            'tipe' => 'teks',
        ]);
        $this->enroll($siswa, $kelas);

        $this->actingAs($siswa);
        $this->postJson('/dashboard/progres-modul', ['materi_id' => $materi->id])
            ->assertOk()
            ->assertJson(['ok' => true, 'completed' => true]);

        $this->assertDatabaseHas('progres_modul', [
            'user_id' => $siswa->id,
            'materi_id' => $materi->id,
        ]);
    }

    public function test_get_rata_rata_per_materi_mengelompokkan_skor(): void
    {
        $siswa = $this->createSiswa();
        ['kelas' => $kelas, 'materi' => $materi] = $this->seedSetLatsol();
        $this->enroll($siswa, $kelas);

        $service = new LatsolService();
        $soals = $kelas->soal()->orderBy('urutan')->get();
        $semuaBenar = $soals->mapWithKeys(fn ($s) => [$s->id => $s->kunci])->all();

        $service->buatAttempt($siswa, $kelas->id, 'Latihan Bab 1', $semuaBenar);
        $service->buatAttempt($siswa, $kelas->id, 'Latihan Bab 1', []);

        $perMateri = $service->getRataRataPerMateri($siswa->id);

        $this->assertCount(1, $perMateri);
        $this->assertSame($materi->judul, $perMateri[0]['materi']);
        $this->assertSame(50, $perMateri[0]['skor']); // round((100 + 0)/2)
    }

    public function test_landing_menampilkan_statistik_global_dari_cache(): void
    {
        $service = new LatsolService();
        $awal = $service->getStatistikGlobal();
        $this->assertArrayHasKey('rata_rata', $awal);
        $this->assertArrayHasKey('total_ujian', $awal);

        // Panggilan kedua harus datang dari cache (array driver) — tidak crash.
        $dua = $service->getStatistikGlobal();
        $this->assertSame($awal, $dua);

        $this->get('/')->assertOk();
    }
}