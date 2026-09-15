<?php

namespace App\Http\Controllers;

use App\Models\Kontak;
use Illuminate\Http\Request;

class KontakController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'not_regex:/[\r\n]/'],
            'subjek' => ['required', 'string', 'max:255'],
            'kategori' => ['nullable', 'string', 'max:255'],
            'pesan' => ['required', 'string', 'min:10', 'max:5000'],
        ]);

        try {
            Kontak::query()->create($data);
        } catch (\Throwable $e) {
            if ($request->wantsJson()) {
                return response()->json(['ok' => false, 'message' => 'Terjadi kesalahan saat mengirim pesan. Coba lagi.'], 422);
            }

            return back()->with('error', 'Terjadi kesalahan saat mengirim pesan. Coba lagi.');
        }

        $pesan = 'Pesanmu berhasil terkirim!';
        if ($request->wantsJson()) {
            return response()->json(['ok' => true, 'message' => $pesan]);
        }

        return back()->with('status', $pesan);
    }
}