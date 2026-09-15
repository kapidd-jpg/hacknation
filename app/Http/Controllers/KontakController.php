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

        Kontak::query()->create($data);

        return response()->json(['ok' => true, 'message' => 'Pesanmu berhasil terkirim!']);
    }
}