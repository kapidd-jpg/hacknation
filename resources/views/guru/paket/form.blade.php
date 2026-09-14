@extends('layout.guru')

@section('title', ($paket ? 'Edit Paket' : 'Tambah Paket') . ' — PintarKuy')

@section('pageContent')
    <div class="flex flex-col gap-6 max-w-3xl">
        <div class="dash-head dash-reveal">
            <div>
                <h1>{{ $paket ? 'Edit Paket' : 'Tambah Paket Baru' }}</h1>
                <p class="dash-head-sub">Pilih kategori kelas yang bisa diakses pemilik paket ini.</p>
            </div>
        </div>

        <form method="POST" action="{{ $paket ? route('guru.paket.update', $paket->id) : route('guru.paket.store') }}" class="dash-card dash-reveal flex flex-col gap-5">
            @csrf
            @if ($paket)
                @method('PUT')
            @endif

            @if ($errors->any())
                <div class="rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="dash-grid dash-grid--2">>
                <div class="setting-field">
                    <label for="key">Key</label>
                    <input id="key" name="key" type="text" required value="{{ old('key', $paket?->key) }}" placeholder="utbk">
                </div>
                <div class="setting-field">
                    <label for="nama">Nama Paket</label>
                    <input id="nama" name="nama" type="text" required value="{{ old('nama', $paket?->nama) }}" placeholder="UTBK Pro">
                </div>
            </div>

            <div class="dash-grid dash-grid--2">>
                <div class="setting-field">
                    <label for="tag">Tag</label>
                    <input id="tag" name="tag" type="text" value="{{ old('tag', $paket?->tag) }}" placeholder="Best Seller">
                </div>
                <div class="setting-field">
                    <label>Akses Kategori</label>
                    <div class="flex flex-wrap gap-2 pt-1" style="display:flex;flex-wrap:wrap;gap:8px;">
                        @foreach (['UTBK-SNBT', 'SMA', 'Bahasa', 'Ekstra'] as $cat)
                            <label class="guru-check" style="display:flex;align-items:center;gap:6px;">
                                <input type="checkbox" name="kategori[]" value="{{ $cat }}" @checked(in_array($cat, old('kategori', $paket?->kategori ?? []), true))>
                                <span style="font-size:13px;font-weight:600;">{{ $cat }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="dash-grid dash-grid--2">>
                <div class="setting-field">
                    <label for="harga">Harga (angka)</label>
                    <input id="harga" name="harga" type="number" min="0" required value="{{ old('harga', $paket?->harga) }}" placeholder="399000">
                </div>
                <div class="setting-field">
                    <label for="harga_lama">Harga Lama (angka)</label>
                    <input id="harga_lama" name="harga_lama" type="number" min="0" value="{{ old('harga_lama', $paket?->harga_lama) }}" placeholder="499000">
                </div>
            </div>

            <div class="setting-field">
                <label for="fitur">Fitur (satu per baris)</label>
                <textarea id="fitur" name="fitur" rows="6" placeholder="Tryout UTBK tiap minggu&#10;Bank soal 10.000+ (SNBT 2026)&#10;Konsultasi privat 3x/bulan">{{ old('fitur', $paket ? implode(PHP_EOL, $paket->fitur ?? []) : '') }}</textarea>
            </div>

            <div class="toggle-row" style="border-top:1px solid var(--navy-50);padding-top:6px;">
                <div>
                    <b>Aktif / Tersedia</b>
                    <p>Paket nonaktif tidak dipakai sebagai opsi default.</p>
                </div>
                <label class="guru-check">
                    <input type="checkbox" name="aktif" value="1" @checked(old('aktif', $paket?->aktif ?? true))>
                    <span class="toggle"></span>
                </label>
            </div>

            <div class="dash-head-actions" style="justify-content:flex-end;">
                <a href="{{ route('guru.paket.index') }}" class="dash-btn dash-btn--ghost">Batal</a>
                <button type="submit" class="dash-btn dash-btn--primary">{{ $paket ? 'Simpan Perubahan' : 'Simpan Paket' }}</button>
            </div>
        </form>
    </div>
@endsection

@push('styles')
    @vite(['resources/css/dashboard/site.css', 'resources/css/dashboard/guru.css'])
@endpush