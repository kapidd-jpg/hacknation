@extends('layout.guru')

@section('title', 'Pengampu Mapel - PintarKuy')

@section('pageContent')
    <div class="flex flex-col gap-6">
        <div class="dash-head dash-reveal">
            <div>
                <h1>Pengampu Mapel</h1>
                <p class="dash-head-sub">Atur kelas/mata pelajaran yang diampu setiap guru. Guru hanya bisa mengelola konten mapel yang diampu.</p>
            </div>
        </div>

        @foreach ($gurus as $guru)
            <form method="POST" action="{{ route('guru.pengampu.update') }}" class="dash-card dash-reveal">
                @csrf
                <input type="hidden" name="user_id" value="{{ $guru->id }}">

<div class="flex items-center justify-between gap-3 mb-5">
                    <div class="flex items-center gap-3 min-w-0">
                        <span class="grid size-9 shrink-0 place-items-center rounded-lg bg-brick text-whitewarm font-bold">{{ \App\Support\UserFoto::initials($guru->name) }}</span>
                        <div class="min-w-0">
                            <p class="font-bold text-ink truncate">{{ $guru->name }}</p>
                            <p class="text-xs font-semibold text-ink-muted truncate">{{ $guru->email }}</p>
                        </div>
                    </div>
                    <span class="shrink-0 rounded-full bg-paper-100 px-2.5 py-1 text-[11px] font-bold uppercase tracking-wide text-ink-muted">{{ $guru->kelasDiampu->count() }} diampu</span>
                </div>

                <div class="grid gap-2" style="grid-template-columns:repeat(auto-fill,minmax(190px,1fr));">
                    @forelse ($kelasSemua as $k)
                        <label class="flex items-center gap-3 rounded-xl border px-4 py-3 cursor-pointer transition
                            @if ($guru->kelasDiampu->contains($k->id)) border-brick/60 bg-brick-100 shadow-sm @else border-ink/15 bg-white hover:border-ink/40 hover:bg-paper-100 @endif">
                            <input type="checkbox" name="kelas_ids[]" value="{{ $k->id }}" class="size-4 shrink-0 accent-brick"
                                @checked($guru->kelasDiampu->contains($k->id))>
                            <span class="min-w-0 flex-1 truncate text-sm font-semibold text-ink">{{ $k->name }}</span>
                            <span class="shrink-0 rounded-full px-2.5 py-0.5 text-[10.5px] font-bold uppercase tracking-wide {{ $guru->kelasDiampu->contains($k->id) ? 'bg-brick text-whitewarm' : 'bg-paper-100 text-ink-muted' }}">{{ $k->cat ?: 'Umum' }}</span>
                        </label>
                    @empty
                        <p class="col-span-full rounded-xl border border-dashed border-ink/20 bg-white px-4 py-8 text-center text-sm text-ink-muted">Data mapel belum tersedia. Tambahkan kelas di menu <strong>Kelola Kelas</strong> terlebih dahulu.</p>
                    @endforelse
                </div>

                <div class="dash-head-actions" style="justify-content:flex-end;margin-top:16px;">
                    <button type="submit" class="dash-btn dash-btn--primary">Simpan Mapel Diampu</button>
                </div>
            </form>
        @endforeach

        @if ($gurus->isEmpty())
            <div class="dash-card dash-reveal">
                <p class="guru-empty">Belum ada akun guru.</p>
            </div>
        @endif
    </div>
@endsection

@push('styles')
    @vite(['resources/css/dashboard/site.css', 'resources/css/dashboard/guru.css'])
@endpush