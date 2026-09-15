<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        return view('guru.kelas.index', [
            'kelas' => Kelas::query()->withCount('pendaftaran')->orderBy('id', 'desc')->get(),
        ]);
    }

    public function create()
    {
        return view('guru.kelas.form', ['kelas' => null]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        Kelas::query()->create($data);

        return redirect()->route('guru.kelas.index')->with('status', 'Kelas berhasil ditambahkan.');
    }

    public function edit(Kelas $kelas)
    {
        return view('guru.kelas.form', ['kelas' => $kelas]);
    }

    public function update(Request $request, Kelas $kelas)
    {
        $data = $this->validated($request, $kelas->id);
        $kelas->update($data);

        return redirect()->route('guru.kelas.index')->with('status', 'Kelas berhasil diperbarui.');
    }

    public function destroy(Kelas $kelas)
    {
        if ($kelas->pendaftaran()->exists() || $kelas->materi()->exists()) {
            return redirect()->route('guru.kelas.index')->with('status', 'Tidak bisa menghapus kelas yang masih memiliki materi atau siswa terdaftar.');
        }

        $kelas->delete();

        return redirect()->route('guru.kelas.index')->with('status', 'Kelas berhasil dihapus.');
    }

    protected function validated(Request $request, ?int $ignoreId = null): array
    {
        $rules = [
            'slug' => ['required', 'string', 'max:255', 'unique:kelas,slug' . ($ignoreId ? ',' . $ignoreId : '')],
            'name' => ['required', 'string', 'max:255'],
            'cat' => ['required', 'string', 'max:255'],
            'ico' => ['nullable', 'string', 'max:4'],
            'meta' => ['nullable', 'string', 'max:255'],
            'desc' => ['nullable', 'string', 'max:1000'],
            'modul' => ['required', 'integer', 'min:1'],
            'durasi' => ['nullable', 'string', 'max:255'],
            'price' => ['nullable', 'integer', 'min:0'],
            'old' => ['nullable', 'integer', 'min:0'],
            'bg' => ['nullable', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'max:255'],
            'aktif' => ['nullable', 'boolean'],
        ];

        $data = $request->validate($rules);
        $data['aktif'] = $request->boolean('aktif');

        return $data;
    }
}