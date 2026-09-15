@extends('layout.guru')

@section('title', 'Pengampu Mapel — PintarKuy')

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

                <div class="flex items-center gap-3 mb-4">
                    <span class="flex items-center justify-center size-10 rounded-xl bg-brand-green/20 text-lg">👩‍🏫</span>
                    <div>
                        <p class="text-white font-bold">{{ $guru->name }}</p>
                        <p class="text-xs font-semibold text-navy-300">{{ $guru->email }}</p>
                    </div>
                </div>

                <div class="grid gap-2" style="grid-template-columns:repeat(auto-fill,minmax(190px,1fr));">
                    @forelse ($kelasSemua as $k)
                        <label class="flex items-center gap-3 rounded-xl border px-4 py-3 cursor-pointer transition
                            @if ($guru->kelasDiampu->contains($k->id)) border-brand-green/50 bg-brand-green/10 @else border-white/10 hover:border-white/20 hover:bg-white/5 @endif">
                            <input type="checkbox" name="kelas_ids[]" value="{{ $k->id }}" class="size-4"
                                @checked($guru->kelasDiampu->contains($k->id))>
                            <span class="text-sm font-semibold text-white">{{ $k->name }}</span>
                            <span class="text-[11px] font-semibold text-navy-400">{{ $k->cat }}</span>
                        </label>
                    @empty
                        <p class="text-sm text-navy-300">Belum ada kelas terdaftar.</p>
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