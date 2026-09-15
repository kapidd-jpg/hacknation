<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Materi;
use App\Models\Soal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SoalController extends Controller
{
    public function index(Request $request)
    {
        $query = Soal::query()->with(['kelas', 'materi']);
        if (!Auth::user()->isAdmin()) {
            $query->whereIn('kelas_id', Auth::user()->kelasDiampu()->pluck('kelas.id'));
        }
        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->integer('kelas_id'));
        }

        return view('guru.soal.index', [
            'soal' => $query->orderBy('kelas_id')->orderBy('set_label')->orderBy('urutan')->get(),
            'kelasSemua' => $this->kelasSemua(),
            'filterKelas' => $request->integer('kelas_id'),
        ]);
    }

    public function create()
    {
        return view('guru.soal.form', [
            'soal' => null,
            'opsi' => ['A' => '', 'B' => '', 'C' => '', 'D' => ''],
            'kelasSemua' => $this->kelasSemua(),
            'materiSemua' => [],
        ]);
    }

    public function materiByKelas($kelas)
    {
        $this->authorizeKelas((string) $kelas);

        return response()->json(
            Materi::where('kelas_id', $kelas)->orderBy('urutan')->get(['id', 'judul'])
        );
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $this->authorizeKelas($data['kelas_id']);
        $data['opsi'] = [
            'A' => $data['opsi_a'],
            'B' => $data['opsi_b'],
            'C' => $data['opsi_c'],
            'D' => $data['opsi_d'],
        ];
        unset($data['opsi_a'], $data['opsi_b'], $data['opsi_c'], $data['opsi_d']);
        Soal::query()->create($data);

        return redirect()->route('guru.soal.index')->with('status', 'Soal berhasil ditambahkan.');
    }

    public function edit(Soal $soal)
    {
        $this->authorizeKelas((string) $soal->kelas_id);

        return view('guru.soal.form', [
            'soal' => $soal,
            'opsi' => $soal->opsi,
            'kelasSemua' => $this->kelasSemua(),
            'materiSemua' => $soal->kelas_id
                ? Materi::where('kelas_id', $soal->kelas_id)->orderBy('urutan')->get()
                : [],
        ]);
    }

    public function update(Request $request, Soal $soal)
    {
        $data = $this->validated($request);
        $this->authorizeKelas($data['kelas_id']);
        $data['opsi'] = [
            'A' => $data['opsi_a'],
            'B' => $data['opsi_b'],
            'C' => $data['opsi_c'],
            'D' => $data['opsi_d'],
        ];
        unset($data['opsi_a'], $data['opsi_b'], $data['opsi_c'], $data['opsi_d']);
        $soal->update($data);

        return redirect()->route('guru.soal.index')->with('status', 'Soal berhasil diperbarui.');
    }

    public function destroy(Soal $soal)
    {
        $this->authorizeKelas((string) $soal->kelas_id);
        $soal->delete();

        return redirect()->route('guru.soal.index')->with('status', 'Soal berhasil dihapus.');
    }

    protected function kelasSemua()
    {
        if (Auth::user()->isAdmin()) {
            return Kelas::query()->orderBy('name')->get();
        }

        return Auth::user()->kelasDiampu()->orderBy('kelas.name')->get();
    }

    protected function authorizeKelas(string $kelasId): void
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->kelasDiampu()->whereKey($kelasId)->exists()) {
            abort(403, 'Anda hanya bisa mengelola mapel yang diampu.');
        }
    }

    protected function validated(Request $request): array
    {
        return $request->validate([
            'kelas_id' => ['required', 'exists:kelas,id'],
            'materi_id' => ['nullable', 'exists:materi,id'],
            'set_label' => ['required', 'string', 'max:255'],
            'pertanyaan' => ['required', 'string'],
            'opsi_a' => ['required', 'string', 'max:255'],
            'opsi_b' => ['required', 'string', 'max:255'],
            'opsi_c' => ['required', 'string', 'max:255'],
            'opsi_d' => ['required', 'string', 'max:255'],
            'kunci' => ['required', 'integer', 'between:0,3'],
            'pembahasan' => ['nullable', 'string'],
            'urutan' => ['nullable', 'integer', 'min:1'],
            'aktif' => ['nullable', 'boolean'],
        ]);
    }
}