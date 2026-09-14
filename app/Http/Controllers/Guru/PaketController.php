<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Paket;
use App\Models\User;
use Illuminate\Http\Request;

class PaketController extends Controller
{
    public function index()
    {
        return view('guru.paket.index', [
            'paket' => Paket::query()->orderBy('harga')->get(),
        ]);
    }

    public function create()
    {
        return view('guru.paket.form', ['paket' => null]);
    }

    public function store(Request $request)
    {
        Paket::query()->create($this->validated($request));

        return redirect()->route('guru.paket.index')->with('status', 'Paket berhasil ditambahkan.');
    }

    public function edit(Paket $paket)
    {
        return view('guru.paket.form', ['paket' => $paket]);
    }

    public function update(Request $request, Paket $paket)
    {
        $paket->update($this->validated($request, $paket->id));

        return redirect()->route('guru.paket.index')->with('status', 'Paket berhasil diperbarui.');
    }

    public function destroy(Paket $paket)
    {
        if (User::query()->where('paket', $paket->key)->exists()) {
            return redirect()->route('guru.paket.index')->with('status', 'Paket tidak bisa dihapus karena masih dipakai oleh siswa.');
        }

        $paket->delete();

        return redirect()->route('guru.paket.index')->with('status', 'Paket berhasil dihapus.');
    }

    protected function validated(Request $request, ?int $ignoreId = null): array
    {
        $rules = [
            'key' => ['required', 'string', 'max:255', 'unique:paket,key' . ($ignoreId ? ',' . $ignoreId : '')],
            'nama' => ['required', 'string', 'max:255'],
            'tag' => ['nullable', 'string', 'max:255'],
            'harga' => ['required', 'integer', 'min:0'],
            'harga_lama' => ['nullable', 'integer', 'min:0'],
            'kuota' => ['nullable', 'integer', 'min:1'],
            'fitur' => ['nullable', 'string', 'max:5000'],
        ];

        $data = $request->validate($rules);
        $data['fitur'] = array_values(array_filter(array_map('trim', explode("\n", (string) $request->input('fitur', '')))));
        $data['aktif'] = $request->boolean('aktif');

        return $data;
    }
}