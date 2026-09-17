<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()->where('role', 'siswa')->with(['kelasTerdaftar', 'pakets']);

        if (!Auth::user()->isAdmin()) {
            $query->whereHas('kelasTerdaftar', fn ($q) => $q->whereIn('kelas.id', Auth::user()->kelasDiampu()->pluck('kelas.id')));
        }

        if ($request->filled('cari')) {
            $q = addcslashes($request->string('cari')->trim()->toString(), '\\%_');
            $query->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('sekolah', 'like', "%{$q}%");
            });
        }

        return view('guru.siswa.index', [
            'siswa' => $query->orderBy('id', 'desc')->get(),
        ]);
    }

    public function destroy(Request $request, User $siswa)
    {
        abort_if(! Auth::user()->isAdmin(), 403, 'Hanya admin yang dapat menghapus akun siswa.');
        abort_if($siswa->role !== 'siswa', 403, 'Hanya akun siswa yang bisa dihapus.');

        $nama = $siswa->name;
        $siswa->delete();

        return redirect()->route('guru.siswa')->with('status', 'Akun siswa "' . $nama . '" berhasil dihapus.');
    }
}