@extends('layout.guru')

@section('title', ($kelas ? 'Edit ' . $kelas->name : 'Tambah Kelas') . ' — PintarKuy')

@section('pageContent')
    <div class="flex flex-col gap-6 max-w-3xl">
        <div class="dash-head dash-reveal">
            <div>
                <h1>{{ $kelas ? 'Edit Kelas' : 'Tambah Kelas Baru' }}</h1>
                <p class="dash-head-sub">Isi detail kelas agar tampil rapi di katalog siswa.</p>
            </div>
        </div>

        <form method="POST" action="{{ $kelas ? route('guru.kelas.update', $kelas->id) : route('guru.kelas.store') }}" class="dash-card dash-reveal flex flex-col gap-5">
            @csrf
            @if ($kelas)
                @method('PUT')
            @endif

            @if ($errors->any())
                <div class="rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="dash-grid dash-grid--2">>
                <div class="setting-field">
                    <label for="name">Nama Kelas</label>
                    <input id="name" name="name" type="text" required value="{{ old('name', $kelas?->name) }}" placeholder="Coding Python Dasar">
                </div>
                <div class="setting-field">
                    <label for="slug">Slug (URL)</label>
                    <input id="slug" name="slug" type="text" required value="{{ old('slug', $kelas?->slug) }}" placeholder="python">
                </div>
            </div>

            <div class="dash-grid dash-grid--2">>
                <div class="setting-field">
                    <label for="cat">Kategori</label>
                    <select id="cat" name="cat" class="guru-select">
                        @foreach (['UTBK-SNBT', 'SMA', 'Bahasa', 'Ekstra'] as $opt)
                            <option value="{{ $opt }}" @selected(old('cat', $kelas?->cat) === $opt)>{{ $opt }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="setting-field">
                    <label for="ico">Ikon (2 huruf)</label>
                    <input id="ico" name="ico" type="text" maxlength="4" value="{{ old('ico', $kelas?->ico) }}" placeholder="PY">
                </div>
            </div>

            <div class="setting-field">
                <label for="meta">Meta / Subjudul</label>
                <input id="meta" name="meta" type="text" value="{{ old('meta', $kelas?->meta) }}" placeholder="Ekstra · Maks 25 siswa">
            </div>

            <div class="setting-field">
                <label for="desc">Deskripsi</label>
                <textarea id="desc" name="desc" rows="3">{{ old('desc', $kelas?->desc) }}</textarea>
            </div>

            <div class="dash-grid dash-grid--2">>
                <div class="setting-field">
                    <label for="modul">Jumlah Modul</label>
                    <input id="modul" name="modul" type="number" min="1" required value="{{ old('modul', $kelas?->modul) }}">
                </div>
                <div class="setting-field">
                    <label for="durasi">Durasi</label>
                    <input id="durasi" name="durasi" type="text" value="{{ old('durasi', $kelas?->durasi) }}" placeholder="8 Minggu">
                </div>
            </div>

            <div class="dash-grid dash-grid--2">>
                <div class="setting-field">
                    <label for="price">Harga</label>
                    <input id="price" name="price" type="text" value="{{ old('price', $kelas?->price) }}" placeholder="Rp 399K">
                </div>
                <div class="setting-field">
                    <label for="old">Harga Coret (Old)</label>
                    <input id="old" name="old" type="text" value="{{ old('old', $kelas?->old) }}" placeholder="Rp 499K">
                </div>
            </div>

            <div class="dash-grid dash-grid--2">>
                <div class="setting-field">
                    <label for="bg">Warna Latar Ikon (CSS)</label>
                    <input id="bg" name="bg" type="text" value="{{ old('bg', $kelas?->bg) }}" placeholder="rgba(223,228,251,1)">
                </div>
                <div class="setting-field">
                    <label for="color">Warna Ikon (CSS)</label>
                    <input id="color" name="color" type="text" value="{{ old('color', $kelas?->color) }}" placeholder="#1E3ABA">
                </div>
            </div>

            <div class="toggle-row" style="border-top:1px solid var(--navy-50);padding-top:6px;">
                <div>
                    <b>Aktif / Tampil di Katalog</b>
                    <p>Matikan untuk menyembunyikan kelas dari siswa.</p>
                </div>
                <label class="guru-check">
                    <input type="checkbox" name="aktif" value="1" @checked(old('aktif', $kelas?->aktif ?? true))>
                    <span class="toggle"></span>
                </label>
            </div>

            <div class="dash-head-actions" style="justify-content:flex-end;">
                <a href="{{ route('guru.kelas.index') }}" class="dash-btn dash-btn--ghost">Batal</a>
                <button type="submit" class="dash-btn dash-btn--primary">{{ $kelas ? 'Simpan Perubahan' : 'Simpan Kelas' }}</button>
            </div>
        </form>
    </div>
@endsection

@push('styles')
    @vite(['resources/css/dashboard/site.css', 'resources/css/dashboard/guru.css'])
@endpush