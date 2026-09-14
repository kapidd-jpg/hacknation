@extends('layout.dashboard')

@section('title', 'Pengaturan — PintarKuy')

@section('pageContent')
    <div class="flex flex-col gap-6">
        <div class="dash-head dash-reveal">
            <div>
                <h1>Pengaturan</h1>
                <p class="dash-head-sub">Kelola akun, keamanan, dan preferensi notifikasi kamu.</p>
            </div>
        </div>

        <div class="setting-tabs dash-reveal" id="settingTabs">
            <button type="button" data-tab="profil" class="active">Profil</button>
            <button type="button" data-tab="keamanan">Keamanan</button>
            <button type="button" data-tab="notifikasi">Notifikasi</button>
        </div>

        {{-- Profil --}}
        <div class="dash-card setting-pane active" id="pane-profil">
            <div class="setting-row">
                <div class="setting-avatar">
                    <img id="sFoto" src="{{ \App\Support\UserFoto::src(auth()->user()->foto) }}" alt="Foto profil">
                    <div>
                        <b style="font-size:15px;color:var(--navy-900);">Foto Profil</b>
                        <p class="setting-hint" style="margin:4px 0 10px;">JPG atau PNG, maks 2 MB.</p>
                        <button type="button" id="sFotoBtn" class="dash-btn dash-btn--ghost" style="padding:9px 18px;">Ganti Foto</button>
                    </div>
                </div>

                <div class="dash-grid dash-grid--2" style="grid-template-columns:1fr 1fr;">
                    <div class="setting-field">
                        <label for="sNama">Nama Lengkap</label>
                        <input id="sNama" type="text" value="{{ auth()->user()->name }}">
                    </div>
                    <div class="setting-field">
                        <label for="sEmail">Email</label>
                        <input id="sEmail" type="email" value="{{ auth()->user()->email }}">
                    </div>
                </div>

                <div class="dash-grid dash-grid--2" style="grid-template-columns:1fr 1fr;">
                    <div class="setting-field">
                        <label for="sSekolah">Sekolah</label>
                        <input id="sSekolah" type="text" value="{{ auth()->user()->sekolah }}">
                    </div>
                    <div class="setting-field">
                        <label for="sKelas">Kelas & Jurusan</label>
                        <input id="sKelas" type="text" value="{{ auth()->user()->kelas_jurusan }}">
                    </div>
                </div>

                <div class="setting-field">
                    <label for="sBio">Bio</label>
                    <textarea id="sBio">{{ auth()->user()->bio }}</textarea>
                    <span class="setting-hint">Tampil di profil komunitas forum diskusi.</span>
                </div>
            </div>
            <div class="dash-head-actions" style="justify-content:flex-end;">
                <button type="button" class="dash-btn dash-btn--primary setting-save" data-msg="Profil berhasil disimpan.">Simpan Perubahan</button>
            </div>
        </div>

        {{-- Keamanan --}}
        <div class="dash-card setting-pane" id="pane-keamanan">
            <div class="setting-row">
                <div class="setting-field">
                    <label for="sPassLama">Password Lama</label>
                    <input id="sPassLama" type="password" placeholder="••••••••••">
                </div>
                <div class="dash-grid dash-grid--2" style="grid-template-columns:1fr 1fr;">
                    <div class="setting-field">
                        <label for="sPassBaru">Password Baru</label>
                        <input id="sPassBaru" type="password" placeholder="Minimal 8 karakter">
                    </div>
                    <div class="setting-field">
                        <label for="sPassKonf">Konfirmasi Password</label>
                        <input id="sPassKonf" type="password" placeholder="Ulangi password baru">
                    </div>
                </div>

                <div class="toggle-row" style="padding-top:6px;border-top:1px solid var(--navy-50);">
                    <div>
                        <b>Autentikasi Dua Lapis (2FA)</b>
                        <p>Amankan akun dengan kode OTP setiap masuk dari perangkat baru.</p>
                    </div>
                    <button type="button" class="toggle" aria-pressed="false"></button>
                </div>
                <div class="toggle-row">
                    <div>
                        <b>Aktivitas Login</b>
                        <p>Dapatkan notifikasi email saat ada login dari perangkat tak dikenal.</p>
                    </div>
                    <button type="button" class="toggle on" aria-pressed="true"></button>
                </div>
            </div>
            <div class="dash-head-actions" style="justify-content:flex-end;">
                <button type="button" class="dash-btn dash-btn--primary setting-save" data-msg="Pengaturan keamanan disimpan.">Simpan Perubahan</button>
            </div>
        </div>

        {{-- Notifikasi --}}
        <div class="dash-card setting-pane" id="pane-notifikasi">
            <div class="setting-row">
                <div class="toggle-row">
                    <div>
                        <b>Notifikasi Live Class</b>
                        <p>Pengingat 30 menit sebelum kelas live dimulai.</p>
                    </div>
                    <button type="button" class="toggle on" aria-pressed="true"></button>
                </div>
                <div class="toggle-row">
                    <div>
                        <b>Pengingat Tugas</b>
                        <p>Notifikasi tenggat pengumpulan tugas H-2 dan H-1.</p>
                    </div>
                    <button type="button" class="toggle on" aria-pressed="true"></button>
                </div>
                <div class="toggle-row">
                    <div>
                        <b>Email Newsletter</b>
                        <p>Tips belajar mingguan dan info promo paket.</p>
                    </div>
                    <button type="button" class="toggle" aria-pressed="false"></button>
                </div>
                <div class="toggle-row">
                    <div>
                        <b>Peringkat & Pencapaian</b>
                        <p>Laporan peringkat dan badge yang baru diraih.</p>
                    </div>
                    <button type="button" class="toggle on" aria-pressed="true"></button>
                </div>
            </div>
        </div>
    </div>

    <div id="settingToast" class="setting-toast" role="status">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span class="setting-toast-msg"></span>
    </div>
@endsection

@push('styles')
    @vite(['resources/css/dashboard/site.css', 'resources/css/dashboard/pengaturan.css'])
@endpush

@push('scripts')
    <script>
        window.pintarKuyPengaturanUrl = @json(route('dashboard.pengaturan.update'));
        window.pintarKuyCsrf = @json(csrf_token());
    </script>
    @vite(['resources/js/dashboard/pengaturan.js'])
@endpush