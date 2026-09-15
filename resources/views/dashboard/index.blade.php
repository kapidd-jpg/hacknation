@extends('layout.dashboard')

@section('title', 'Dashboard Siswa — PintarKuy')

@php
    $stats = [
        ['label' => 'Kelas Aktif', 'value' => $kelasCount . ' Kelas', 'note' => $latihanCount . ' latihan soal dikerjakan', 'bg' => 'bg-brand-greenlight/40', 'color' => 'text-brand-green', 'icon' => 'M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25'],
        ['label' => 'Rata-rata Nilai', 'value' => $avgNilai ?? '—', 'note' => $nilaiCount ? 'Dari ' . $nilaiCount . ' latihan soal' : 'Belum ada latihan soal', 'bg' => 'bg-purple-100', 'color' => 'text-purple-700', 'icon' => 'M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z'],
        ['label' => 'Latihan Soal', 'value' => $latihanCount, 'note' => 'Total pengerjaan latihan soal', 'bg' => 'bg-navy-100', 'color' => 'text-navy-800', 'icon' => 'M9 12.75 11.25 15 15 7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z'],
        ['label' => 'Progres Modul', 'value' => $pctProgres . '%', 'note' => $doneModul . ' dari ' . $totalModul . ' modul tuntas', 'bg' => 'bg-navy-100', 'color' => 'text-navy-800', 'icon' => 'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5'],
    ];

    $classes = [];
    foreach ($classes ?? [] as $idx => $class) {
        $classes[] = [
            'name' => $class['name'],
            'tag' => strtoupper($class['tag']),
            'desc' => $class['desc'],
            'pct' => $class['pct'],
            'progressText' => $class['progressText'],
            'note' => 'Progres ' . $class['pct'] . '% semester ini',
            'color' => 'brand-green',
            'iconBg' => 'bg-brand-greenlight/40',
            'ico' => $class['icon'],
        ];
    }
@endphp

@section('pageContent')
    {{-- Greeting banner --}}
    <div class="relative bg-white rounded-2xl shadow-[0px_4px_24px_-2px_rgba(15,27,76,0.06)] p-8 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6 overflow-hidden">
        <div class="absolute -right-16 -top-16 size-64 rounded-full bg-navy-50 blur-2xl"></div>
        <div class="relative flex flex-col gap-2 max-w-xl">
            <div class="flex items-center gap-2">
                <span class="flex items-center gap-1.5 bg-navy-100 text-ink-soft text-[11px] font-bold px-3 py-1 rounded-full">
                    <span class="size-1.5 rounded-full bg-brand-green"></span> Akun Siswa Aktif
                </span>
                <span class="text-ink-muted text-[11px] font-bold">ID: {{ auth()->user()->id }}</span>
            </div>
            <h1 class="text-navy-950 text-2xl font-extrabold flex items-center gap-2">Selamat datang kembali, {{ auth()->user()->name }}! 👋</h1>
            <p class="text-ink-soft text-sm">
                Lanjutkan progres belajarmu hari ini. Kamu memiliki <span class="text-navy-950">{{ $totalModul - $doneModul }} modul</span> yang bisa diselesaikan dan
                <span class="text-red-700">{{ $latihanCount }}</span> latihan soal yang sudah dikerjakan.
            </p>
        </div>
        <div class="relative flex gap-3">
            <div class="bg-navy-100 rounded-full shadow-sm px-4 py-2.5 flex items-center gap-2.5">
                <span class="flex items-center justify-center size-6 rounded-full bg-navy-800">
                    <svg class="size-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
                <div>
                    <p class="text-ink-muted text-[10px] font-semibold tracking-wide uppercase leading-none">Target Utama</p>
                    <p class="text-navy-950 text-xs font-bold leading-tight">{{ $kelasCount ? $classes[0]['name'] : 'Pilih Target' }}</p>
                </div>
            </div>
            <div class="bg-navy-100 rounded-full shadow-sm px-4 py-2.5 flex items-center gap-2.5">
                <span class="flex items-center justify-center size-6 rounded-full bg-brand-greenlight">
                    <svg class="size-3 text-brand-greentext" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/></svg>
                </span>
                <div>
                    <p class="text-ink-muted text-[10px] font-semibold tracking-wide uppercase leading-none">Progres Belajar</p>
                    <p class="text-brand-greentext text-xs font-bold leading-tight">{{ $pctProgres }}% Modul Tuntas</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Stat cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach ($stats as $stat)
            <div class="bg-white rounded-xl shadow-[0px_4px_20px_-2px_rgba(15,27,76,0.05)] p-5 flex flex-col justify-between">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-ink-soft text-xs font-medium">{{ $stat['label'] }}</p>
                        <p class="text-navy-800 text-2xl font-bold mt-1">
                            {{ $stat['value'] }}
                            @isset($stat['suffix'])<span class="text-ink-muted text-lg font-normal">{{ $stat['suffix'] }}</span>@endisset
                            @isset($stat['badge'])<span class="ml-1 align-middle bg-brand-greenlight/40 text-brand-green text-[11px] font-semibold px-1.5 py-0.5 rounded">{{ $stat['badge'] }}</span>@endisset
                        </p>
                    </div>
                    <span class="flex items-center justify-center size-11 rounded-xl {{ $stat['bg'] }} shrink-0">
                        <svg class="size-5 {{ $stat['color'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $stat['icon'] }}"/></svg>
                    </span>
                </div>
                <div class="bg-navy-50 rounded-lg px-2.5 py-2 mt-4">
                    <p class="text-ink-soft text-[11px] font-bold">{{ $stat['note'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Continue learning + weekly target --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        {{-- Lanjutkan Belajar --}}
        <div class="lg:col-span-8 bg-white rounded-2xl shadow-[0px_4px_20px_-2px_rgba(15,27,76,0.05)] p-7 flex flex-col justify-between">
            <div class="flex flex-col gap-3">
                <div class="flex items-center justify-between pb-4">
                    <div class="flex items-center gap-2.5">
                        <span class="size-2.5 rounded-full bg-brand-green"></span>
                        <h2 class="text-navy-950 text-lg font-bold">Lanjutkan Belajar</h2>
                    </div>
                    <span class="bg-navy-50 text-ink-soft text-[11px] font-bold px-3 py-1 rounded-full">Progres Semester Ini</span>
                </div>

                <div class="bg-navy-50 rounded-xl p-4 flex gap-5 items-center">
                    <div class="relative w-36 h-28 rounded-lg bg-navy-800 overflow-hidden shrink-0">
                        <div class="absolute inset-0 bg-gradient-to-t from-navy-800/80 to-transparent"></div>
                        <span class="absolute left-2 bottom-2 bg-white/90 text-navy-950 text-[11px] font-bold px-2 py-0.5 rounded">MATERI</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="bg-brand-greenlight/40 text-brand-green text-[11px] font-bold px-2.5 py-0.5 rounded-full">{{ count($classes) ? $classes[0]['name'] : '—' }}</span>
                            <span class="text-ink-muted text-[11px] font-bold">• {{ count($classes) ? ucwords(strtolower($classes[0]['tag'])) : '—' }}</span>
                        </div>
                        <h3 class="text-navy-950 font-bold text-lg mt-1.5 truncate">{{ count($classes) ? $classes[0]['name'] : 'Belum ada kelas terdaftar' }}</h3>
                        <p class="text-ink-soft text-xs mt-1 truncate">{{ count($classes) ? $classes[0]['desc'] : 'Daftar kelas melalui Katalog untuk mulai belajar.' }}</p>
                    </div>
                </div>

                <div class="flex flex-col gap-2 pt-2">
                    <div class="flex items-center justify-between text-[11px] font-bold">
                        <span class="text-brand-green">{{ count($classes) ? $classes[0]['pct'] : 0 }}% Selesai</span>
                        <span class="text-ink-soft">{{ count($classes) ? $classes[0]['progressText'] : '—' }}</span>
                    </div>
                    <div class="bg-navy-100 h-2.5 rounded-full overflow-hidden">
                        <div class="bg-brand-green h-full rounded-full" style="width:{{ count($classes) ? $classes[0]['pct'] : 0 }}%"></div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between pt-6">
                <a href="{{ route('dashboard.materi') }}" class="bg-navy-800 text-white text-sm font-semibold px-6 py-3 rounded-full shadow-sm hover:bg-navy-950 transition flex items-center gap-2">
                    Lanjutkan Belajar
                    <svg class="size-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
                <a href="{{ route('dashboard.materi') }}" class="bg-navy-100 text-navy-950 text-sm font-semibold px-5 py-3 rounded-full hover:bg-navy-100/70 transition flex items-center gap-2">
                    <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/></svg>
                    Lihat Silabus Lengkap
                </a>
            </div>
        </div>

        {{-- Weekly target ring --}}
        <div class="lg:col-span-4 bg-white rounded-2xl shadow-[0px_4px_20px_-2px_rgba(15,27,76,0.05)] p-7 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3">
                    <h2 class="text-navy-950 text-lg font-bold leading-tight">Target<br>Mingguan</h2>
                    <span class="bg-navy-50 text-ink-soft text-[11px] font-bold px-2.5 py-1 rounded-full text-center leading-tight">Semester<br>Aktif</span>
                </div>

                <div class="relative flex items-center justify-center py-4">
                    <svg class="size-36 -rotate-90" viewBox="0 0 120 120">
                        <circle cx="60" cy="60" r="52" fill="none" stroke="#EEF1FD" stroke-width="12"/>
                        <circle cx="60" cy="60" r="52" fill="none" stroke="#0D9488" stroke-width="12"
                                stroke-dasharray="326.7" stroke-dashoffset="{{ round(326.7 * (1 - $pctProgres / 100)) }}" stroke-linecap="round"/>
                    </svg>
                    <div class="absolute flex flex-col items-center">
                        <span class="text-navy-950 text-2xl font-black">{{ $pctProgres }}%</span>
                        <span class="text-ink-soft text-[11px] font-medium">Tercapai</span>
                    </div>
                </div>

                <div class="flex flex-col gap-2.5 pt-2">
                    <div class="bg-navy-50 rounded-lg px-3 py-2 flex items-center justify-between">
                        <span class="text-ink-soft text-[11px] font-bold">Modul Tuntas</span>
                        <span class="text-navy-950 text-[11px] font-bold">{{ $doneModul }} <span class="text-ink-muted font-normal">/ {{ $totalModul }} Modul</span></span>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-center pt-4">
                <span class="bg-brand-greenlight/40 text-brand-greentext text-xs font-bold px-4 py-1.5 rounded-full">Status: {{ $pctProgres >= 50 ? 'On Track' : 'Mulai Belajar' }} 🎯</span>
            </div>
        </div>
    </div>

    {{-- Kelas Saya --}}
    <div class="flex flex-col gap-4">
        <div class="flex items-end justify-between">
            <div>
                <h2 class="text-navy-950 text-xl font-bold">Kelas Saya</h2>
                <p class="text-ink-soft text-xs mt-0.5">Akses cepat ke materi dan ruang belajar aktif kamu semester ini</p>
            </div>
            <a href="{{ route('dashboard.kelas') }}" class="text-navy-950 text-xs font-bold flex items-center gap-1">
                Lihat Semua ({{ $kelasCount }})
                <svg class="size-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach ($classes as $class)
                {{-- pastikan kelas utilitas warna dinamis ikut ter-compile Tailwind --}}
                <span class="hidden text-brand-green bg-brand-green text-purple-700 bg-purple-700 text-navy-950 bg-navy-950 text-navy-800 bg-navy-800"></span>
                <div class="bg-white rounded-xl shadow-[0px_4px_20px_-2px_rgba(15,27,76,0.05)] p-5 flex flex-col justify-between">
                    <div class="flex flex-col gap-0.5">
                        <div class="flex items-start justify-between">
                            <span class="flex items-center justify-center size-10 rounded-lg {{ $class['iconBg'] }}">
                                <span class="size-5 text-{{ $class['color'] }} font-bold">{{ $class['ico'] }}</span>
                            </span>
                            <span class="bg-navy-50 text-ink-muted text-[11px] font-semibold px-2 py-0.5 rounded">{{ $class['tag'] }}</span>
                        </div>
                        <h3 class="text-navy-950 font-bold text-lg mt-2.5">{{ $class['name'] }}</h3>
                        <p class="text-ink-soft text-[11px] font-bold">{{ $class['desc'] }}</p>

                        <div class="flex flex-col gap-1.5 pt-3.5">
                            <div class="flex items-center justify-between">
                                <span class="text-{{ $class['color'] }} text-[11px] font-semibold">{{ $class['pct'] }}%</span>
                                <span class="text-ink-muted text-[11px] font-bold">{{ $class['progressText'] }}</span>
                            </div>
                            <div class="bg-navy-100 h-1.5 rounded-full overflow-hidden">
                                <div class="bg-{{ $class['color'] }} h-full rounded-full" style="width:{{ $class['pct'] }}%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-navy-50 rounded-lg px-3 py-2 mt-5">
                        <p class="text-navy-900 text-[11px] font-medium">{{ $class['note'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@push('styles')
    @vite(['resources/css/dashboard/site.css'])
@endpush