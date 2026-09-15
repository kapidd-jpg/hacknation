<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Materi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MateriController extends Controller
{
    public function index(Request $request)
    {
        $query = Materi::query()->with('kelas');
        if (!Auth::user()->isAdmin()) {
            $query->whereIn('kelas_id', Auth::user()->kelasDiampu()->pluck('kelas.id'));
        }
        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->integer('kelas_id'));
        }

        return view('guru.materi.index', [
            'materi' => $query->orderBy('kelas_id')->orderBy('urutan')->get(),
            'kelasSemua' => $this->kelasSemua(),
            'filterKelas' => $request->integer('kelas_id'),
        ]);
    }

    public function create()
    {
        return view('guru.materi.form', [
            'materi' => null,
            'kelasSemua' => $this->kelasSemua(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $this->authorizeKelas($data['kelas_id']);
        Materi::query()->create($data);

        return redirect()->route('guru.materi.index')->with('status', 'Materi berhasil ditambahkan.');
    }

    public function edit(Materi $materi)
    {
        $this->authorizeKelas((string) $materi->kelas_id);

        return view('guru.materi.form', [
            'materi' => $materi,
            'kelasSemua' => $this->kelasSemua(),
        ]);
    }

    public function update(Request $request, Materi $materi)
    {
        $data = $this->validated($request);
        $this->authorizeKelas($data['kelas_id']);
        $materi->update($data);

        return redirect()->route('guru.materi.index')->with('status', 'Materi berhasil diperbarui.');
    }

    public function destroy(Materi $materi)
    {
        $this->authorizeKelas((string) $materi->kelas_id);
        $materi->delete();

        return redirect()->route('guru.materi.index')->with('status', 'Materi berhasil dihapus.');
    }

    protected function kelasSemua()
    {
        if (Auth::user()->isAdmin()) {
            return Kelas::query()->orderBy('name')->get();
        }

        return Auth::user()->kelasDiampu()->orderBy('kelas.name')->get();
    }

    protected function authorizeKelas(string $kelasId): void
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->kelasDiampu()->whereKey($kelasId)->exists()) {
            abort(403, 'Anda hanya bisa mengelola mapel yang diampu.');
        }
    }

    protected function validated(Request $request): array
    {
        return $request->validate([
            'kelas_id' => ['required', 'exists:kelas,id'],
            'judul' => ['required', 'string', 'max:255'],
            'tutor' => ['nullable', 'string', 'max:255'],
            'pertemuan' => ['nullable', 'integer', 'min:1'],
            'durasi' => ['nullable', 'string', 'max:255'],
            'bab' => ['nullable', 'string', 'max:255'],
            'urutan' => ['nullable', 'integer', 'min:1'],
            'tipe' => ['nullable', 'string', 'max:20'],
            'video_url' => ['nullable', 'string', 'max:255'],
            'konten' => ['nullable', 'string'],
        ]);
    }
}