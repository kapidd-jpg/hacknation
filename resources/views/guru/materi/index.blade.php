@extends('layout.guru')

@section('title', 'Kelola Materi - PintarKuy')

@section('pageContent')
    <div class="flex flex-col gap-6">
        <div class="dash-head dash-reveal">
            <div>
                <h1>Kelola Materi</h1>
                <p class="dash-head-sub">Modul & silabus yang ditampilkan di halaman materi siswa.</p>
            </div>
            <div class="dash-head-actions">
                <a href="{{ route('guru.materi.create') }}" class="dash-btn dash-btn--primary">+ Tambah Materi</a>
            </div>
        </div>

        <div class="dash-card dash-reveal" style="display:flex;flex-wrap:wrap;gap:12px;align-items:center;justify-content:space-between;">
            <form method="GET" action="{{ route('guru.materi.index') }}" class="flex items-center gap-3 flex-wrap">
                <select name="kelas_id" class="guru-select" onchange="this.form.submit()">
                    <option value="">Semua Kelas</option>
                    @foreach ($kelasSemua as $k)
                        <option value="{{ $k->id }}" @selected((string) $filterKelas === (string) $k->id)>{{ $k->name }}</option>
                    @endforeach
                </select>
                @if ($filterKelas)
                    <a href="{{ route('guru.materi.index') }}" class="dash-btn dash-btn--ghost dash-btn--sm">Reset</a>
                @endif
            </form>
        </div>

        <div class="dash-card dash-reveal">
            <div class="guru-table-wrap">
                <table class="guru-table">
                    <thead>
                        <tr>
                            <th>Materi</th>
                            <th>Kelas</th>
                            <th>Tutor</th>
                            <th>Pertemuan</th>
                            <th>Tipe</th>
                            <th>Bab</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($materi as $m)
                            <tr>
                                <td class="guru-cell-title">{{ $m->judul }}</td>
                                <td><span class="dash-pill">{{ $m->kelas?->name }}</span></td>
                                <td>{{ $m->tutor }}</td>
                                <td>Pertemuan {{ $m->pertemuan }}</td>
                                <td><span class="dash-pill">{{ $m->tipe }}</span></td>
                                <td>{{ $m->bab }}</td>
                                <td>
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('guru.materi.edit', $m->id) }}" class="dash-btn dash-btn--ghost dash-btn--sm">Edit</a>
                                        <form method="POST" action="{{ route('guru.materi.destroy', $m->id) }}" onsubmit="return confirm('Hapus materi ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dash-btn dash-btn--danger dash-btn--sm">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="guru-empty">Belum ada materi.</td></tr>
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