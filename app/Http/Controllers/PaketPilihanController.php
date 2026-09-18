<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use App\Support\UserNotif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaketPilihanController extends Controller
{
    public function index()
    {
        $pakets = Paket::query()->where('aktif', true)->orderBy('harga')->get();
        $ownedKeys = Auth::user()->paketKeys();

        return view('paket.pilih', [
            'pakets' => $pakets,
            'ownedKeys' => $ownedKeys,
            'hasAnyPaket' => Auth::user()->hasAnyPaket(),
        ]);
    }

    public function checkout(string $key)
    {
        $paket = Paket::query()->where('key', $key)->where('aktif', true)->firstOrFail();

        return view('paket.checkout', [
            'paket' => $paket,
            'ownedKeys' => Auth::user()->paketKeys(),
            'hasAnyPaket' => Auth::user()->hasAnyPaket(),
        ]);
    }

    /**
     * // NOTE: dummy payment flow — sengaja TANPA gateway pembayaran nyata
     * (mock untuk demo/showcase hackathon). Akses paket langsung diberikan
     * demi pengalaman demo; duplicate-purchase dicegah di bawah.
     * Bila nanti dibuka untuk publik berbayar, GANTI dengan flow order +
     * verifikasi webhook gateway nyata (lihat LAPORAN_CODE_REVIEW H-2).
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function bayar(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'paket' => ['required', 'string', 'max:255'],
            'metode' => ['required', 'string', 'in:va,qris,transfer'],
        ]);

        $paket = Paket::query()->where('key', $data['paket'])->where('aktif', true)->firstOrFail();

        if ($user->pakets()->whereKey($paket->id)->exists()) {
            return back()->withErrors(['paket' => 'Kamu sudah memiliki paket ' . $paket->nama . '.']);
        }

        $user->pakets()->syncWithoutDetaching([$paket->id]);

        UserNotif::push(
            $user,
            'Paket ' . $paket->nama . ' aktif',
            'Selamat! Akses paket sudah terbuka. Mulai belajar kapan pun kamu siap.',
            'paket',
            ['url' => route('dashboard'), 'icon' => 'paket']
        );

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
        return view('paket.sukses', [
            'paketNama' => session('paketNama'),
            'metodeNama' => session('metodeNama'),
            'nomorPembayaran' => session('nomorPembayaran'),
        ]);
    }
}