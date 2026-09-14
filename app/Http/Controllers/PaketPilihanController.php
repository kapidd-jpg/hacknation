<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaketPilihanController extends Controller
{
    public function index()
    {
        if (Auth::user()->isStaff()) {
            return redirect()->route('guru.dashboard');
        }

        $pakets = Paket::query()->where('aktif', true)->orderBy('harga')->get();
        $ownedKeys = Auth::user()->paketKeys();

        return view('paket.pilih', [
            'pakets' => $pakets,
            'ownedKeys' => $ownedKeys,
        ]);
    }

    public function checkout(string $key)
    {
        if (Auth::user()->isStaff()) {
            return redirect()->route('guru.dashboard');
        }

        $paket = Paket::query()->where('key', $key)->where('aktif', true)->firstOrFail();

        return view('paket.checkout', [
            'paket' => $paket,
            'ownedKeys' => Auth::user()->paketKeys(),
        ]);
    }

    public function bayar(Request $request)
    {
        $user = Auth::user();
        if ($user->isStaff()) {
            return redirect()->route('guru.dashboard');
        }

        $data = $request->validate([
            'paket' => ['required', 'string', 'in:utbk,sma-ekstra,bahasa'],
            'metode' => ['required', 'string', 'in:va,qris,transfer'],
        ]);

        $paket = Paket::query()->where('key', $data['paket'])->where('aktif', true)->firstOrFail();

        if ($user->pakets()->whereKey($paket->id)->exists()) {
            return back()->withErrors(['paket' => 'Kamu sudah memiliki paket ' . $paket->nama . '.']);
        }

        $user->pakets()->syncWithoutDetaching([$paket->id]);

        return redirect()->route('paket.berhasil')->with([
            'paketNama' => $paket->nama,
            'metodeNama' => match ($data['metode']) {
                'va' => 'Virtual Account',
                'qris' => 'QRIS',
                'transfer' => 'Transfer Bank',
            },
            'nomorPembayaran' => 'PK-' . date('Ymd') . '-' . strtoupper(substr(md5($user->id . $paket->key . microtime()), 0, 8)),
        ]);
    }

    public function berhasil()
    {
        if (Auth::user()->isStaff()) {
            return redirect()->route('guru.dashboard');
        }

        return view('paket.sukses', [
            'paketNama' => session('paketNama'),
            'metodeNama' => session('metodeNama'),
            'nomorPembayaran' => session('nomorPembayaran'),
        ]);
    }
}