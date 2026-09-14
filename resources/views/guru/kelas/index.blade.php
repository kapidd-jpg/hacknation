@extends('layout.guru')

@section('title', 'Kelola Kelas — PintarKuy')

@section('pageContent')
    <div class="flex flex-col gap-6">
        <div class="dash-head dash-reveal">
            <div>
                <h1>Kelola Kelas</h1>
                <p class="dash-head-sub">Katalog kelas ini muncul di halaman Katalog siswa.</p>
            </div>
            <div class="dash-head-actions">
                <a href="{{ route('guru.kelas.create') }}" class="dash-btn dash-btn--primary">+ Tambah Kelas</a>
            </div>
        </div>

        <div class="dash-card dash-reveal">
            <div class="guru-table-wrap">
                <table class="guru-table">
                    <thead>
                        <tr>
                            <th>Kelas</th>
                            <th>Kategori</th>
                            <th>Modul</th>
                            <th>Durasi</th>
                            <th>Harga</th>
                            <th>Siswa</th>
                            <th>Status</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kelas as $k)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <span class="kelas-icon" style="background:{{ $k->bg ?? 'rgba(126,252,154,0.35)' }};color:{{ $k->color ?? '#007433' }}">{{ substr($k->ico ?? 'KL', 0, 2) }}</span>
                                        <div>
                                            <p class="guru-cell-title">{{ $k->name }}</p>
                                            <p class="guru-cell-sub">{{ $k->meta }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="dash-pill">{{ $k->cat }}</span></td>
                                <td>{{ $k->modul }}</td>
                                <td>{{ $k->durasi }}</td>
                                <td>
                                    <p class="guru-cell-title">Rp {{ number_format($k->price ?? 0, 0, ',', '.') }}</p>
                                    @if ($k->old)
                                        <small class="guru-cell-old">Rp {{ number_format($k->old, 0, ',', '.') }}</small>
                                    @endif
                                </td>
                                <td>{{ $k->pendaftaran_count }}</td>
                                <td>
                                    @if ($k->aktif)
                                        <span class="guru-badge guru-badge--ok">Aktif</span>
                                    @else
                                        <span class="guru-badge guru-badge--muted">Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('guru.kelas.edit', $k->id) }}" class="dash-btn dash-btn--ghost dash-btn--sm">Edit</a>
                                        <form method="POST" action="{{ route('guru.kelas.destroy', $k->id) }}" onsubmit='return confirm(@json("Hapus kelas {$k->name}?"));'>
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dash-btn dash-btn--danger dash-btn--sm">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="guru-empty">Belum ada kelas. Klik "+ Tambah Kelas".</td></tr>
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