@extends('layout.guru')

@section('title', 'Kelola Soal - PintarKuy')

@section('pageContent')
    <div class="flex flex-col gap-6">
        <div class="dash-head dash-reveal">
            <div>
                <h1>Kelola Soal</h1>
                <p class="dash-head-sub">Bank soal latihan yang ditampilkan di halaman latsol siswa.</p>
            </div>
            <div class="dash-head-actions">
                <a href="{{ route('guru.soal.create') }}" class="dash-btn dash-btn--primary">+ Tambah Soal</a>
            </div>
        </div>

        <div class="dash-card dash-reveal" style="display:flex;flex-wrap:wrap;gap:12px;align-items:center;justify-content:space-between;">
            <form method="GET" action="{{ route('guru.soal.index') }}" class="flex items-center gap-3 flex-wrap">
                <select name="kelas_id" class="guru-select" onchange="this.form.submit()">
                    <option value="">Semua Kelas</option>
                    @foreach ($kelasSemua as $k)
                        <option value="{{ $k->id }}" @selected((string) $filterKelas === (string) $k->id)>{{ $k->name }}</option>
                    @endforeach
                </select>
                @if ($filterKelas)
                    <a href="{{ route('guru.soal.index') }}" class="dash-btn dash-btn--ghost dash-btn--sm">Reset</a>
                @endif
            </form>
        </div>

        <div class="dash-card dash-reveal">
            <div class="guru-table-wrap">
                <table class="guru-table">
                    <thead>
                        <tr>
                            <th>Soal</th>
                            <th>Kelas</th>
                            <th>Set</th>
                            <th>Kunci</th>
                            <th>Urutan</th>
                            <th>Status</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($soal as $s)
                            <tr>
                                <td class="guru-cell-title" style="max-width:340px;">{{ \Illuminate\Support\Str::limit($s->pertanyaan, 80) }}</td>
                                <td><span class="dash-pill">{{ $s->kelas?->name }}</span></td>
                                <td>{{ $s->set_label }}</td>
                                <td>
                                    <span class="dash-pill">{{ $s->opsi ? array_keys($s->opsi)[$s->kunci] : 'A' }}</span>
                                </td>
                                <td>{{ $s->urutan }}</td>
                                <td>{!! $s->aktif
                                    ? '<span class="dash-pill" style="background:rgba(94,234,212,0.3);color:var(--brand-green,#0D9488);">Aktif</span>'
                                    : '<span class="dash-pill" style="background:rgba(255,120,120,0.15);color:rgba(255,120,120,1);">Nonaktif</span>' !!}</td>
                                <td>
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('guru.soal.edit', $s->id) }}" class="dash-btn dash-btn--ghost dash-btn--sm">Edit</a>
                                        <form method="POST" action="{{ route('guru.soal.destroy', $s->id) }}" onsubmit="return confirm('Hapus soal ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dash-btn dash-btn--danger dash-btn--sm">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="guru-empty">Belum ada soal. Tambahkan soal terlebih dahulu.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    @vite(['resources/css/dashboard/site.css', 'resources/css/dashboard/guru.css'])
@endpush