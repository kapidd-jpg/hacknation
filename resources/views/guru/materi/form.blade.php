@extends('layout.guru')

@section('title', ($materi ? 'Edit Materi' : 'Tambah Materi') . ' - PintarKuy')

@section('pageContent')
    <div class="flex flex-col gap-6 max-w-3xl">
        <div class="dash-head dash-reveal">
            <div>
                <h1>{{ $materi ? 'Edit Materi' : 'Tambah Materi Baru' }}</h1>
                <p class="dash-head-sub">Satu materi = satu konten/bab belajar di dalam kelas.</p>
            </div>
        </div>

        <form method="POST" action="{{ $materi ? route('guru.materi.update', $materi->id) : route('guru.materi.store') }}" class="dash-card dash-reveal flex flex-col gap-5">
            @csrf
            @if ($materi)
                @method('PUT')
            @endif

            @if ($errors->any())
                <div class="rounded-xl bg-danger/10 border border-danger/20 text-danger text-sm px-4 py-3">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="setting-field">
                <label for="kelas_id">Kelas</label>
                <select id="kelas_id" name="kelas_id" class="guru-select" required>
                    @foreach ($kelasSemua as $k)
                        <option value="{{ $k->id }}" @selected(old('kelas_id', $materi?->kelas_id) == $k->id)>{{ $k->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="setting-field">
                <label for="judul">Judul Materi</label>
                <input id="judul" name="judul" type="text" required value="{{ old('judul', $materi?->judul) }}" placeholder="Pembahasan Materi Inti">
            </div>

            <div class="setting-field">
                <label for="tutor">Tutor</label>
                <input id="tutor" name="tutor" type="text" value="{{ old('tutor', $materi?->tutor) }}" placeholder="Dr. Hendra Saputra, M.Sc.">
            </div>

            <div class="dash-grid dash-grid--2" style="grid-template-columns:1fr 1fr;">
                <div class="setting-field">
                    <label for="pertemuan">Pertemuan ke-</label>
                    <input id="pertemuan" name="pertemuan" type="number" min="1" value="{{ old('pertemuan', $materi?->pertemuan ?? 1) }}">
                </div>
                <div class="setting-field">
                    <label for="urutan">Urutan</label>
                    <input id="urutan" name="urutan" type="number" min="1" value="{{ old('urutan', $materi?->urutan ?? 1) }}">
                </div>
            </div>

            <div class="dash-grid dash-grid--2" style="grid-template-columns:1fr 1fr;">
                <div class="setting-field">
                    <label for="bab">Bab</label>
                    <input id="bab" name="bab" type="text" value="{{ old('bab', $materi?->bab) }}" placeholder="Bab 1">
                </div>
                <div class="setting-field">
                    <label for="durasi">Durasi</label>
                    <input id="durasi" name="durasi" type="text" value="{{ old('durasi', $materi?->durasi) }}" placeholder="90 Menit">
                </div>
            </div>

            <div class="setting-field">
                <label for="tipe">Tipe Konten</label>
                <select id="tipe" name="tipe" class="guru-select">
                    <option value="video" @selected(old('tipe', $materi?->tipe ?? 'video') === 'video')>Video Pembelajaran</option>
                    <option value="teks" @selected(old('tipe', $materi?->tipe) === 'teks')>Ringkasan Teks</option>
                    <option value="video_teks" @selected(old('tipe', $materi?->tipe) === 'video_teks')>Video + Ringkasan</option>
                </select>
            </div>

            <div class="setting-field">
                <label for="video_url">URL Video (YouTube)</label>
                <input id="video_url" name="video_url" type="text" value="{{ old('video_url', $materi?->video_url) }}" placeholder="https://www.youtube.com/watch?v=..." >
            </div>

            <div class="setting-field">
                <label for="konten">Ringkasan Teks (opsional)</label>
                <textarea id="konten" name="konten" rows="5" placeholder="Tulis ringkasan materi dalam beberapa paragraf...">{{ old('konten', $materi?->konten) }}</textarea>
            </div>

            <div class="dash-head-actions" style="justify-content:flex-end;">
                <a href="{{ route('guru.materi.index') }}" class="dash-btn dash-btn--ghost">Batal</a>
                <button type="submit" class="dash-btn dash-btn--primary">{{ $materi ? 'Simpan Perubahan' : 'Simpan Materi' }}</button>
            </div>
        </form>
    </div>
@endsection

@push('styles')
    @vite(['resources/css/dashboard/site.css', 'resources/css/dashboard/guru.css'])
@endpush