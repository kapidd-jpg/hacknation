@extends('layout.guru')

@section('title', 'Kelola Paket — PintarKuy')

@section('pageContent')
    <div class="flex flex-col gap-6">
        <div class="dash-head dash-reveal">
            <div>
                <h1>Kelola Paket</h1>
                <p class="dash-head-sub">Paket langganan yang mengatur kuota & fitur untuk akun siswa.</p>
            </div>
            <div class="dash-head-actions">
                <a href="{{ route('guru.paket.create') }}" class="dash-btn dash-btn--primary">+ Tambah Paket</a>
            </div>
        </div>

        <div class="dash-card dash-reveal">
            <div class="guru-table-wrap">
                <table class="guru-table">
                    <thead>
                        <tr>
                            <th>Key</th>
                            <th>Nama</th>
                            <th>Tag</th>
                            <th>Harga</th>
                            <th>Harga Lama</th>
                            <th>Kuota</th>
                            <th>Status</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($paket as $p)
                            <tr>
                                <td><code class="guru-code">{{ $p->key }}</code></td>
                                <td class="guru-cell-title">{{ $p->nama }}</td>
                                <td><span class="dash-pill">{{ $p->tag }}</span></td>
                                <td>Rp {{ number_format($p->harga, 0, ',', '.') }}</td>
                                <td><small class="guru-cell-old">Rp {{ number_format($p->harga_lama ?? 0, 0, ',', '.') }}</small></td>
                                <td>{{ $p->kuota ?? '∞' }}</td>
                                <td>
                                    @if ($p->aktif)
                                        <span class="guru-badge guru-badge--ok">Aktif</span>
                                    @else
                                        <span class="guru-badge guru-badge--muted">Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('guru.paket.edit', $p->id) }}" class="dash-btn dash-btn--ghost dash-btn--sm">Edit</a>
                                        <form method="POST" action="{{ route('guru.paket.destroy', $p->id) }}" onsubmit='return confirm(@json("Hapus paket {$p->nama}? Siswa dengan paket ini akan revert ke default."));'>
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dash-btn dash-btn--danger dash-btn--sm">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="guru-empty">Belum ada paket.</td></tr>
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