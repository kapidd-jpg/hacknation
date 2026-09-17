<?php

namespace App\Http\Controllers;

use App\Events\MateriChanged;
use App\Models\Kelas;
use App\Models\Materi;
use App\Models\Room;
use App\Models\RoomPresence;
use App\Models\RoomState;
use App\Models\User;
use App\Support\LiveKitService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RoomController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $rooms = Room::query()->where('aktif', true)->orderBy('kategori')->orderBy('id')->get();

        if ($user->isStaff()) {
            return view('guru.ruang', [
                'rooms' => $rooms,
            ]);
        }

        return view('dashboard.ruang', [
            'rooms' => $rooms,
            'aksesKategori' => $user->aksesKategori(),
        ]);
    }

    public function show(Room $room)
    {
        if (! $room->aktif) {
            abort(404, 'Room tidak ditemukan.');
        }

        $user = Auth::user();
        $this->authorizeRoom($user, $room);

        $state = $room->state()->with(['kelas', 'materi'])->first();

        if ($user->isStaff()) {
            return view('guru.room', [
                'room' => $room,
                'kelasList' => $this->kelasUntukGuru($user, $room),
                'state' => $state,
            ]);
        }

        return view('dashboard.room', [
            'room' => $room,
            'state' => $state,
        ]);
    }

    public function state(Room $room)
    {
        $user = Auth::user();
        $this->authorizeRoom($user, $room);

        $state = $room->state()->with(['kelas', 'materi'])->first();

        return response()->json([
            'ok' => true,
            'room' => $room->only(['id', 'slug', 'nama', 'kategori']),
            'kelas' => $state?->kelas ? $state->kelas->only(['id', 'name', 'slug']) : null,
            'materi' => $state?->materi ? [
                'id' => $state->materi->id,
                'judul' => $state->materi->judul,
                'tipe' => $state->materi->tipe ?: 'video',
                'video_url' => $state->materi->video_url,
                'konten' => $state->materi->konten,
            ] : null,
            'halaman' => $state?->halaman,
            'play' => (bool) $state?->play,
            'waktu' => (float) ($state?->waktu ?? 0),
            'pengirim' => $state?->pengirim,
            'updated_at' => $state?->updated_at?->toIso8601String(),
        ]);
    }

    public function token(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'room' => ['required', 'string', 'max:120'],
        ]);

        $room = Room::query()->where('slug', $data['room'])->where('aktif', true)->firstOrFail();
        $this->authorizeRoom($user, $room);

        $livekitUrl = (string) config('services.livekit.url');

        if (blank($livekitUrl) || blank(config('services.livekit.key')) || blank(config('services.livekit.secret'))) {
            return response()->json(['ok' => false, 'message' => 'LiveKit belum dikonfigurasi.'], 503);
        }

        return response()->json([
            'ok' => true,
            'url' => $livekitUrl,
            'token' => LiveKitService::token($user, $room->livekitName()),
            'room' => $room->livekitName(),
            'identity' => $user->name,
        ]);
    }

    public function materi(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'room' => ['required', 'string', 'max:120'],
            'kelas_id' => ['nullable', 'integer', 'exists:kelas,id'],
            'materi_id' => ['nullable', 'integer', 'exists:materi,id'],
            'halaman' => ['nullable', 'integer', 'min:0'],
        ]);

        $room = Room::query()->where('slug', $data['room'])->where('aktif', true)->firstOrFail();
        $this->authorizeRoom($user, $room);

        $materi = $data['materi_id'] ?? null ? Materi::query()->find($data['materi_id']) : null;
        $kelas = $data['kelas_id'] ?? null ? Kelas::query()->find($data['kelas_id']) : ($materi?->kelas ?? null);

        if ($kelas && $kelas->cat !== $room->kategori) {
            return response()->json(['ok' => false, 'message' => 'Kelas tidak se-kategori dengan room.'], 422);
        }

        DB::transaction(function () use ($room, $kelas, $materi, $data, $user) {
            RoomState::query()->updateOrCreate(
                ['room_id' => $room->id],
                [
                    'kelas_id' => $kelas?->id,
                    'materi_id' => $materi?->id,
                    'halaman' => $data['halaman'] ?? null,
                    'pengirim' => $user->name,
                    'play' => false,
                    'waktu' => 0,
                ]
            );
        });

        broadcast(new MateriChanged($room, $kelas, $materi, $data['halaman'] ?? null, $user->name, false, 0));

        return response()->json(['ok' => true, 'message' => 'Materi disinkronkan ke room.']);
    }

    public function kontrol(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'room' => ['required', 'string', 'max:120'],
            'play' => ['sometimes', 'boolean'],
            'waktu' => ['sometimes', 'numeric', 'min:0'],
        ]);

        $room = Room::query()->where('slug', $data['room'])->where('aktif', true)->firstOrFail();
        $this->authorizeRoom($user, $room);

        $state = RoomState::query()->where('room_id', $room->id)->first();
        if (! $state || ! $state->materi_id || $state->materi->tipe === 'teks') {
            return response()->json(['ok' => false, 'message' => 'Belum ada materi video yang dipresentasikan.'], 422);
        }

        $kelas = $state->kelas;
        $materi = $state->materi;

        $state->update([
            'play' => (bool) ($data['play'] ?? $state->play),
            'waktu' => (float) ($data['waktu'] ?? $state->waktu),
        ]);

        broadcast(new MateriChanged(
            $room,
            $kelas,
            $materi,
            $state->halaman,
            $user->name,
            (bool) $data['play'] ?? $state->play,
            (float) $data['waktu'] ?? $state->waktu
        ));

        return response()->json(['ok' => true, 'play' => $state->play, 'waktu' => $state->waktu]);
    }

    public function materiList(Room $room, Kelas $kelas)
    {
        $user = Auth::user();
        $this->authorizeRoom($user, $room);

        if ($kelas->cat !== $room->kategori) {
            return response()->json(['ok' => false, 'message' => 'Kelas tidak se-kategori dengan room.'], 422);
        }

        return response()->json([
            'ok' => true,
            'materi' => $kelas->materi()->orderBy('urutan')->get(['id', 'judul', 'tipe', 'durasi', 'urutan', 'video_url', 'konten']),
        ]);
    }

    public function presence(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'room' => ['required', 'string', 'max:120'],
            'voice' => ['sometimes', 'boolean'],
        ]);

        $room = Room::query()->where('slug', $data['room'])->where('aktif', true)->firstOrFail();
        $this->authorizeRoom($user, $room);

        RoomPresence::query()->updateOrCreate(
            ['room_id' => $room->id, 'user_id' => $user->id],
            [
                'nama' => $user->name,
                'role' => $user->isStaff() ? 'guru' : 'siswa',
                'voice' => (bool) ($data['voice'] ?? false),
                'last_seen_at' => now(),
            ]
        );

        $cutoff = now()->subSeconds(45);
        RoomPresence::query()->where('room_id', $room->id)->where('last_seen_at', '<', $cutoff)->delete();

        $rows = RoomPresence::query()
            ->where('room_id', $room->id)
            ->where('last_seen_at', '>=', $cutoff)
            ->orderByDesc('last_seen_at')
            ->get(['id', 'user_id', 'nama', 'role', 'voice', 'last_seen_at']);

        $list = $rows->map(fn (RoomPresence $p) => [
            'id' => $p->id,
            'nama' => $p->nama,
            'role' => $p->role,
            'voice' => (bool) $p->voice,
            'iniAku' => $p->user_id === $user->id,
            'sejak' => $p->last_seen_at->diffForHumans(),
        ]);

        return response()->json(['ok' => true, 'count' => $list->count(), 'list' => $list->values()]);
    }

    protected function authorizeRoom(User $user, Room $room): void
    {
        if ($user->isStaff() && ! $user->isAdmin()) {
            if ($this->kelasUntukGuru($user, $room)->isEmpty()) {
                abort(403, 'Anda hanya bisa mengelola room pada mapel yang Anda ampu.');
            }
        }

        if ($user->isSiswa() && ! in_array($room->kategori, $user->aksesKategori(), true)) {
            abort(403, 'Beli paket section tersebut untuk bergabung dengan room ini.');
        }
    }

    protected function kelasUntukGuru(User $user, Room $room)
    {
        $query = Kelas::query()->where('cat', $room->kategori)->orderBy('name');

        if (! $user->isAdmin()) {
            $query->whereIn('id', $user->kelasDiampu()->pluck('kelas.id'));
        }

        return $query->get();
    }
}