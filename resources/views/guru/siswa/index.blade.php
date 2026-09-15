@extends('layout.guru')

@section('title', 'Siswa — PintarKuy')

@section('pageContent')
    <div class="flex flex-col gap-6">
        <div class="dash-head dash-reveal">
            <div>
                <h1>Daftar Siswa</h1>
                <p class="dash-head-sub">{{ $siswa->count() }} akun siswa terdaftar di PintarKuy.</p>
            </div>
            <div class="dash-head-actions">
                <form method="GET" action="{{ route('guru.siswa') }}" class="flex items-center gap-2">
                    <input name="cari" type="search" value="{{ request('cari') }}" placeholder="Cari nama / email / sekolah"
                        class="dash-input" style="min-width:260px;">
                    <button type="submit" class="dash-btn dash-btn--primary">Cari</button>
                </form>
            </div>
        </div>

        <div class="dash-card dash-reveal">
            <div class="guru-table-wrap">
                <table class="guru-table">
                    <thead>
                        <tr>
                            <th>Siswa</th>
                            <th>Email</th>
                            <th>Sekolah</th>
                            <th>Kelas</th>
                            <th>Paket</th>
                            <th>Kelas Diikuti</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($siswa as $s)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <span class="guru-student-avatar">{{ strtoupper(substr($s->name, 0, 1)) }}</span>
                                        <p class="guru-cell-title">{{ $s->name }}</p>
                                    </div>
                                </td>
                                <td>{{ $s->email }}</td>
                                <td>{{ $s->sekolah ?: '—' }}</td>
                                <td>{{ $s->kelas_jurusan ?: '—' }}</td>
                                <td>
                                    @php $owned = $s->pakets->pluck('nama')->all(); @endphp
                                    @if ($owned)
                                        <span class="dash-pill" title="{{ implode(', ', $owned) }}">{{ implode(' + ', $owned) }}</span>
                                    @else
                                        <span class="dash-pill">—</span>
                                    @endif
                                </td>
                                <td>{{ $s->kelasTerdaftar->pluck('name')->implode(', ') ?: '—' }}</td>
                                <td>
                                    <div class="flex items-center justify-end gap-2">
                                        <form method="POST" action="{{ route('guru.siswa.destroy', $s->id) }}" onsubmit='return confirm(@json("Hapus akun siswa {$s->name} beserta pendaftaran kelasnya?"));'>
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dash-btn dash-btn--danger dash-btn--sm">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="guru-empty">Tidak ada siswa ditemukan.</td></tr>
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