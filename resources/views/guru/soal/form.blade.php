@extends('layout.guru')

@section('title', ($soal ? 'Edit Soal' : 'Tambah Soal') . ' — PintarKuy')

@section('pageContent')
    <div class="flex flex-col gap-6 max-w-3xl">
        <div class="dash-head dash-reveal">
            <div>
                <h1>{{ $soal ? 'Edit Soal' : 'Tambah Soal Baru' }}</h1>
                <p class="dash-head-sub">Soal dengan set label yang sama menjadi satu paket latihan siswa.</p>
            </div>
        </div>

        <form method="POST" action="{{ $soal ? route('guru.soal.update', $soal->id) : route('guru.soal.store') }}" class="dash-card dash-reveal flex flex-col gap-5">
            @csrf
            @if ($soal)
                @method('PUT')
            @endif

            @if ($errors->any())
                <div class="rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="dash-grid dash-grid--2" style="grid-template-columns:1fr 1fr;">
                <div class="setting-field">
                    <label for="kelas_id">Kelas</label>
                    <select id="kelas_id" name="kelas_id" class="guru-select" required>
                        @foreach ($kelasSemua as $k)
                            <option value="{{ $k->id }}" @selected(old('kelas_id', $soal?->kelas_id) == $k->id)>{{ $k->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="setting-field">
                    <label for="materi_id">Materi (opsional)</label>
                    <select id="materi_id" name="materi_id" class="guru-select"
                        data-materi-url="{{ route('guru.soal.materi.bykelas', 'KELAS') }}"
                        data-materi-value="{{ old('materi_id', $soal?->materi_id) }}">
                        <option value="">— Tanpa materi —</option>
                        @foreach ($materiSemua as $m)
                            <option value="{{ $m->id }}" @selected(old('materi_id', $soal?->materi_id) == $m->id)>{{ $m->judul }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="dash-grid dash-grid--2" style="grid-template-columns:1fr 1fr;">
                <div class="setting-field">
                    <label for="set_label">Set Label</label>
                    <input id="set_label" name="set_label" type="text" required value="{{ old('set_label', $soal?->set_label) }}" placeholder="Latihan Bab 1">
                </div>
                <div class="setting-field">
                    <label for="urutan">Urutan</label>
                    <input id="urutan" name="urutan" type="number" min="1" value="{{ old('urutan', $soal?->urutan ?? 1) }}">
                </div>
            </div>

            <div class="setting-field">
                <label for="pertanyaan">Pertanyaan</label>
                <textarea id="pertanyaan" name="pertanyaan" rows="3" required placeholder="Tulis soal di sini...">{{ old('pertanyaan', $soal?->pertanyaan) }}</textarea>
            </div>

            <div class="setting-field">
                <label>Pilihan Jawaban & Kunci</label>
                @foreach (['A', 'B', 'C', 'D'] as $i => $huruf)
                    <div class="flex items-center gap-3 mt-2">
                        <span class="dash-pill shrink-0" style="min-width:34px;justify-content:center;">{{ $huruf }}</span>
                        <input type="text" name="opsi_{{ strtolower($huruf) }}" value="{{ old('opsi_' . strtolower($huruf), $opsi[$huruf] ?? '') }}" placeholder="Pilihan {{ $huruf }}" class="w-full">
                        <label class="flex items-center gap-2 text-xs font-semibold text-navy-200 shrink-0">
                            <input type="radio" name="kunci" value="{{ $i }}" @checked((string) old('kunci', $soal?->kunci ?? 0) === (string) $i)> Kunci
                        </label>
                    </div>
                @endforeach
            </div>

            <div class="setting-field">
                <label for="pembahasan">Pembahasan (opsional)</label>
                <textarea id="pembahasan" name="pembahasan" rows="3" placeholder="Penjelasan kunci jawaban...">{{ old('pembahasan', $soal?->pembahasan) }}</textarea>
            </div>

            <div class="setting-field">
                <label class="flex items-center gap-2 text-sm font-semibold">
                    <input type="checkbox" name="aktif" value="1" @checked((bool) old('aktif', $soal?->aktif ?? true)) class="w-4 h-4">
                    Aktif (tampil di latihan siswa)
                </label>
            </div>

            <div class="dash-head-actions" style="justify-content:flex-end;">
                <a href="{{ route('guru.soal.index') }}" class="dash-btn dash-btn--ghost">Batal</a>
                <button type="submit" class="dash-btn dash-btn--primary">{{ $soal ? 'Simpan Perubahan' : 'Simpan Soal' }}</button>
            </div>
        </form>
    </div>
@endsection

@push('styles')
    @vite(['resources/css/dashboard/site.css', 'resources/css/dashboard/guru.css'])
@endpush

@push('scripts')
    @vite(['resources/js/guru/soal.js'])
@endpush