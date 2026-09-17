<?php

namespace Tests\Feature;

use App\Events\MateriChanged;
use App\Models\Kelas;
use App\Models\Materi;
use App\Models\Paket;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class RuangBelajarTest extends TestCase
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
        return Paket::query()->updateOrCreate(['key' => 'utbk-' . uniqid()], array_merge([
            'key' => 'utbk-' . uniqid(),
            'nama' => 'UTBK Pro',
            'tag' => 'Best Seller',
            'kuota' => 3,
            'harga' => 399000,
            'harga_lama' => 499000,
            'kategori' => ['UTBK-SNBT'],
            'fitur' => ['Ruang Belajar'],
            'aktif' => true,
        ], $overrides));
    }

    protected function createKelas(array $overrides = []): Kelas
    {
        return Kelas::create(array_merge([
            'slug' => 'room-' . uniqid(),
            'name' => 'Kelas Room Test',
            'cat' => 'UTBK-SNBT',
            'modul' => 4,
            'durasi' => '4 Minggu',
            'siswa' => 0,
            'price' => 399000,
            'old' => 499000,
            'aktif' => true,
        ], $overrides));
    }

    protected function createRoom(array $overrides = []): Room
    {
        return Room::query()->create(array_merge([
            'kategori' => 'UTBK-SNBT',
            'nama' => 'Ruang UTBK-SNBT 1',
            'slug' => 'utbk-snbt-room-' . uniqid(),
            'aktif' => true,
        ], $overrides));
    }

    protected function createMateri(Kelas $kelas, array $overrides = []): Materi
    {
        return Materi::create(array_merge([
            'kelas_id' => $kelas->id,
            'judul' => 'TPS Pengetahuan Kuantitatif',
            'tutor' => 'Guru Test',
            'pertemuan' => 1,
            'durasi' => '20 Menit',
            'bab' => 'TPS',
            'urutan' => 1,
            'tipe' => 'video',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'konten' => null,
        ], $overrides));
    }

    protected function attachAkses(User $siswa, array $kategori = ['UTBK-SNBT']): void
    {
        $paket = $this->createPaket(['kategori' => $kategori]);
        $siswa->pakets()->attach($paket->id);
    }

    // ------------------------------------------------------------------
    //  Daftar & detail room (siswa)
    // ------------------------------------------------------------------

    public function test_siswa_tanpa_paket_tidak_bisa_masuk_room(): void
    {
        $siswa = $this->createSiswa();
        $room = $this->createRoom();
        $this->actingAs($siswa);

        $this->get(route('room.show', $room->slug))->assertStatus(403);
        $this->get(route('room.state', $room->slug))->assertStatus(403);
        $this->postJson(route('room.token'), ['room' => $room->slug])->assertStatus(403);
    }

    public function test_siswa_dengan_paket_bisa_lihat_list_dan_detail_room(): void
    {
        $siswa = $this->createSiswa();
        $this->attachAkses($siswa);
        $this->actingAs($siswa);

        $this->get(route('room.index'))->assertOk();
        $room = $this->createRoom();
        $this->get(route('room.show', $room->slug))->assertOk();
        $this->get(route('room.state', $room->slug))
            ->assertOk()
            ->assertJson(['ok' => true, 'room' => ['slug' => $room->slug], 'materi' => null]);
    }

    public function test_room_nonaktif_abort_404(): void
    {
        $siswa = $this->createSiswa();
        $this->attachAkses($siswa);
        $this->actingAs($siswa);

        $room = $this->createRoom(['aktif' => false]);
        $this->get(route('room.show', $room->slug))->assertStatus(404);
    }

    public function test_token_room_balas_503_jika_livekit_belum_dikonfigurasi(): void
    {
        $siswa = $this->createSiswa();
        $this->attachAkses($siswa);
        $this->actingAs($siswa);

        config(['services.livekit.url' => '', 'services.livekit.key' => '', 'services.livekit.secret' => '']);

        $response = $this->postJson(route('room.token'), ['room' => $this->createRoom()->slug]);
        $response->assertStatus(503)->assertJson(['ok' => false]);
    }

    // ------------------------------------------------------------------
    //  Sync materi (guru) & state
    // ------------------------------------------------------------------

    public function test_guru_sync_materi_ke_room_dan_siswa_lihat_state_baru(): void
    {
        Event::fake([MateriChanged::class]);

        $kelas = $this->createKelas();
        $materi = $this->createMateri($kelas);
        $room = $this->createRoom();
        $guru = $this->createStaff('guru');
        $guru->kelasDiampu()->attach($kelas->id);
        $this->actingAs($guru);

        $response = $this->postJson(route('room.materi'), [
            'room' => $room->slug,
            'kelas_id' => $kelas->id,
            'materi_id' => $materi->id,
            'halaman' => 3,
        ]);
        $response->assertOk()->assertJson(['ok' => true]);

        Event::assertDispatched(MateriChanged::class, fn (MateriChanged $e) => $e->room === $room->slug
            && $e->materi_id === $materi->id
            && $e->halaman === 3
            && $e->pengirim === $guru->name);

        $state = $room->state()->first();
        $this->assertNotNull($state);
        $this->assertSame($kelas->id, $state->kelas_id);
        $this->assertSame($materi->id, $state->materi_id);
        $this->assertSame(3, $state->halaman);

        $siswa = $this->createSiswa();
        $this->attachAkses($siswa);
        $this->actingAs($siswa);

        $this->get(route('room.state', $room->slug))
            ->assertOk()
            ->assertJsonPath('materi.id', $materi->id)
            ->assertJsonPath('halaman', 3)
            ->assertJsonPath('pengirim', $guru->name);
    }

    public function test_sync_materi_kelas_lintas_kategori_ditolak(): void
    {
        $kelasSma = $this->createKelas(['cat' => 'SMA']);
        $room = $this->createRoom(['kategori' => 'UTBK-SNBT']);
        $admin = $this->createStaff('admin');
        $this->actingAs($admin);

        $this->postJson(route('room.materi'), [
            'room' => $room->slug,
            'kelas_id' => $kelasSma->id,
            'materi_id' => null,
            'halaman' => 1,
        ])->assertStatus(422);
    }

    public function test_guru_materi_list_hanya_menampilkan_materi_kelas_diampu(): void
    {
        $kelas = $this->createKelas();
        $materi = $this->createMateri($kelas);
        $room = $this->createRoom();
        $guru = $this->createStaff('guru');
        $guru->kelasDiampu()->attach($kelas->id);
        $this->actingAs($guru);

        $this->get(route('guru.room.materi.list', [$room->slug, $kelas->id]))
            ->assertJson(['ok' => true])
            ->assertJsonPath('materi.0.judul', $materi->judul);
    }

    public function test_guru_tanpa_pengampu_tidak_bisa_mengelola_room(): void
    {
        $room = $this->createRoom(['kategori' => 'UTBK-SNBT']);
        $guru = $this->createStaff('guru'); // belum mengampu kelas UTBK
        $this->actingAs($guru);

        $this->get(route('guru.room.show', $room->slug))->assertStatus(403);
    }

    public function test_admin_bisa_akses_room_lintas_mapel(): void
    {
        $admin = $this->createStaff('admin');
        $this->actingAs($admin);

        $this->get(route('room.index'))->assertOk();

        $kelas = $this->createKelas();
        $room = $this->createRoom();
        $this->get(route('guru.room.show', $room->slug))->assertOk();
        $this->get(route('guru.room.materi.list', [$room->slug, $kelas->id]))
            ->assertOk()
            ->assertJson(['ok' => true]);
    }

    public function test_admin_guru_bisa_melihat_presensi_peserta_di_room(): void
    {
        $room = $this->createRoom();
        $admin = $this->createStaff('admin', 'admin-presence@test.id');
        $this->actingAs($admin);

        $this->postJson(route('room.presence'), ['room' => $room->slug, 'voice' => true])
            ->assertOk()
            ->assertJsonPath('count', 1)
            ->assertJsonPath('list.0.nama', 'Admin Test')
            ->assertJsonPath('list.0.role', 'guru')
            ->assertJsonPath('list.0.voice', true)
            ->assertJsonPath('list.0.iniAku', true);

        $siswa = $this->createSiswa('siswa-presence@test.id');
        $this->attachAkses($siswa);
        $this->actingAs($siswa);

        $this->postJson(route('room.presence'), ['room' => $room->slug])
            ->assertOk()
            ->assertJsonPath('count', 2)
            ->assertJsonPath('list.0.role', 'siswa')
            ->assertJsonPath('list.0.voice', false);
    }

    public function test_kontrol_play_pause_video_guru_terlihat_siswa(): void
    {
        $kelas = $this->createKelas();
        $materi = $this->createMateri($kelas, ['tipe' => 'video']);
        $room = $this->createRoom();
        $guru = $this->createStaff('guru');
        $guru->kelasDiampu()->attach($kelas->id);
        $this->actingAs($guru);

        $this->postJson(route('room.materi'), [
            'room' => $room->slug,
            'kelas_id' => $kelas->id,
            'materi_id' => $materi->id,
            'halaman' => 1,
        ])->assertOk();

        $this->postJson(route('room.kontrol'), ['room' => $room->slug, 'play' => true, 'waktu' => 15.5])
            ->assertOk()
            ->assertJsonPath('play', true)
            ->assertJsonPath('waktu', 15.5);

        $siswa = $this->createSiswa();
        $this->attachAkses($siswa);
        $this->actingAs($siswa);

        $this->get(route('room.state', $room->slug))
            ->assertOk()
            ->assertJsonPath('materi.id', $materi->id)
            ->assertJsonPath('play', true)
            ->assertJsonPath('waktu', 15.5);

        $this->actingAs($guru)->postJson(route('room.kontrol'), ['room' => $room->slug, 'play' => false, 'waktu' => 20])
            ->assertOk()
            ->assertJsonPath('play', false);

        $this->get(route('room.state', $room->slug))
            ->assertOk()
            ->assertJsonPath('play', false)
            ->assertJsonPath('waktu', 20);
    }

    public function test_kontrol_tanpa_materi_video_ditolak(): void
    {
        $kelas = $this->createKelas();
        $room = $this->createRoom();
        $guru = $this->createStaff('guru');
        $guru->kelasDiampu()->attach($kelas->id);
        $this->actingAs($guru);

        $this->postJson(route('room.kontrol'), ['room' => $room->slug, 'play' => true, 'waktu' => 5])
            ->assertStatus(422);
    }
}