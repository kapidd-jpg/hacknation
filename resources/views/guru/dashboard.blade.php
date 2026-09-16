@extends('layout.guru')

@section('title', 'Dashboard Guru - PintarKuy')

@section('pageContent')
    <div class="flex flex-col gap-6">
        <div class="dash-head dash-reveal">
            <div>
                <h1>Halo, {{ explode(' ', Auth::user()->name)[0] }} 👋</h1>
                <p class="dash-head-sub">Kelola kelas, materi, paket, dan pantau siswa PintarKuy dari sini.</p>
            </div>
            <div class="dash-head-actions">
                <a href="{{ route('guru.kelas.create') }}" class="dash-btn dash-btn--primary">+ Tambah Kelas</a>
            </div>
        </div>

        <div class="guru-stats dash-reveal">
            <div class="guru-stat">
                <span class="guru-stat-ico">📚</span>
                <div><p class="guru-stat-num">{{ $jumlahKelas }}</p><p class="guru-stat-label">Kelas</p></div>
            </div>
            <div class="guru-stat">
                <span class="guru-stat-ico">🎬</span>
                <div><p class="guru-stat-num">{{ $jumlahMateri }}</p><p class="guru-stat-label">Modul Materi</p></div>
            </div>
            <div class="guru-stat">
                <span class="guru-stat-ico">💳</span>
                <div><p class="guru-stat-num">{{ $jumlahPaket }}</p><p class="guru-stat-label">Paket</p></div>
            </div>
            <div class="guru-stat">
                <span class="guru-stat-ico">🎓</span>
                <div><p class="guru-stat-num">{{ $jumlahSiswa }}</p><p class="guru-stat-label">Siswa</p></div>
            </div>
            <div class="guru-stat guru-stat--accent">
                <span class="guru-stat-ico">✅</span>
                <div><p class="guru-stat-num">{{ $jumlahPendaftaran }}</p><p class="guru-stat-label">Pendaftaran Kelas</p></div>
            </div>
        </div>

        <div class="dash-grid dash-grid--2 dash-reveal">
            <div class="dash-card">
                <p class="dash-card-title"><span class="dash-dot"></span>Kelas Terbaru</p>
                <div class="flex flex-col">
                    @forelse ($kelasTerbaru as $k)
                        <div class="guru-row">
                            <span class="kelas-icon" style="background:{{ $k->bg ?? 'rgba(94,234,212,0.35)' }};color:{{ $k->color ?? '#0F766E' }}">{{ substr($k->ico ?? 'KL', 0, 2) }}</span>
                            <div class="flex-1 min-w-0">
                                <p class="guru-row-title">{{ $k->name }}</p>
                                <p class="guru-row-sub">{{ $k->cat }} • {{ $k->modul }} modul • {{ $k->price ? 'Rp ' . number_format($k->price, 0, ',', '.') : 'Gratis' }}</p>
                            </div>
                            <a href="{{ route('guru.kelas.edit', $k->id) }}" class="dash-btn dash-btn--ghost dash-btn--sm">Edit</a>
                        </div>
                    @empty
                        <p class="guru-empty">Belum ada kelas.</p>
                    @endforelse
                </div>
            </div>

            <div class="dash-card">
                <p class="dash-card-title"><span class="dash-dot"></span>Siswa Terbaru</p>
                <div class="flex flex-col">
                    @forelse ($siswaTerbaru as $s)
                        <div class="guru-row">
                            <span class="guru-student-avatar">{{ strtoupper(substr($s->name, 0, 1)) }}</span>
                            <div class="flex-1 min-w-0">
                                <p class="guru-row-title">{{ $s->name }}</p>
                                <p class="guru-row-sub">{{ $s->sekolah ?: '-' }} • {{ $s->kelasTerdaftar->count() }} kelas</p>
                            </div>
                        </div>
                    @empty
                        <p class="guru-empty">Belum ada siswa.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    @vite(['resources/css/dashboard/site.css', 'resources/css/dashboard/guru.css'])
@endpush