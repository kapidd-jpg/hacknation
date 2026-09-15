<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengampuController extends Controller
{
    public function index()
    {
        return view('guru.pengampu.index', [
            'gurus' => User::where('role', 'guru')->with('kelasDiampu')->orderBy('name')->get(),
            'kelasSemua' => Kelas::query()->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'kelas_ids' => ['array'],
            'kelas_ids.*' => ['integer', 'exists:kelas,id'],
        ]);

        $guru = User::findOrFail($data['user_id']);
        if (!$guru->isGuru()) {
            abort(422, 'Akun yang dipilih bukan guru.');
        }

        $guru->kelasDiampu()->sync($data['kelas_ids'] ?? []);

        return redirect()->route('guru.pengampu.index')->with('status', 'Mapel diampu berhasil diperbarui.');
    }
}