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
            'kelas' => Kelas::query()->orderBy('id', 'desc')->get(),
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
            'siswa' => ['nullable', 'integer', 'min:0'],
            'price' => ['nullable', 'string', 'max:255'],
            'old' => ['nullable', 'string', 'max:255'],
            'bg' => ['nullable', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'max:255'],
            'aktif' => ['nullable', 'boolean'],
        ];

        return $request->validate($rules);
    }
}