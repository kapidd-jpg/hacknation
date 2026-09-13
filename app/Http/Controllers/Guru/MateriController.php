<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Materi;
use Illuminate\Http\Request;

class MateriController extends Controller
{
    public function index(Request $request)
    {
        $query = Materi::query()->with('kelas');
        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->integer('kelas_id'));
        }

        return view('guru.materi.index', [
            'materi' => $query->orderBy('kelas_id')->orderBy('urutan')->get(),
            'kelasSemua' => Kelas::query()->orderBy('name')->get(),
            'filterKelas' => $request->integer('kelas_id'),
        ]);
    }

    public function create()
    {
        return view('guru.materi.form', [
            'materi' => null,
            'kelasSemua' => Kelas::query()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        Materi::query()->create($this->validated($request));

        return redirect()->route('guru.materi.index')->with('status', 'Materi berhasil ditambahkan.');
    }

    public function edit(Materi $materi)
    {
        return view('guru.materi.form', [
            'materi' => $materi,
            'kelasSemua' => Kelas::query()->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Materi $materi)
    {
        $materi->update($this->validated($request));

        return redirect()->route('guru.materi.index')->with('status', 'Materi berhasil diperbarui.');
    }

    public function destroy(Materi $materi)
    {
        $materi->delete();

        return redirect()->route('guru.materi.index')->with('status', 'Materi berhasil dihapus.');
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
        ]);
    }
}