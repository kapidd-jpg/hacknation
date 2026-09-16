@extends('layout.app')

@section('title', 'Hubungi Kami - PintarKuy')

@section('content')
@include('komponen.header')

<div class="page-hero">
    <span class="pk-eyebrow">Kontak</span>
    <h1 class="page-hero-title">Kami Siap Membantu Kamu</h1>
    <p class="page-hero-sub">Ada pertanyaan soal program, pembayaran, atau kendala teknis? Tim dukungan kami merespons cepat, rata-rata di bawah 2 jam kerja.</p>
</div>

<!-- ============ Info + Form ============ -->
<section class="pk-section pk-section--muted" style="padding-top:56px;">
    <div class="pk-container">
        <div class="kontak-grid">

            {{-- Info cards --}}
            <div class="kontak-info">
                @php
                    $infos = [
                        ['ico' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z', 'title' => 'Alamat Kantor', 'body' => 'Jl. Cipete Raya No. 88, Kebayoran Baru, Jakarta Selatan, DKI Jakarta 12150'],
                        ['ico' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'title' => 'Email Support', 'body' => 'pintarkuyid@gmail.com  ·  kerjasama@pintarkuy.id', 'link' => 'https://mail.google.com/mail/?view=cm&fs=1&to=pintarkuyid@gmail.com', 'linkText' => 'pintarkuyid@gmail.com'],
                        ['ico' => 'M2 3h5l2 5-3 2a14 14 0 007 7l2-3 5 2v5a1 1 0 01-1 1C9 23 1 14 1 4a1 1 0 011-1z', 'title' => 'Telepon / WhatsApp', 'body' => '<a href="tel:+62895424011288">+62 895-4240-11288</a><br><a href="https://wa.me/6281225124408" target="_blank" rel="noopener">+62 812-2512-4408</a>'],
                        ['ico' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Jam Operasional', 'body' => 'Senin–Jumat: 08.00–21.00 WIB<br>Sabtu–Minggu: 09.00–17.00 WIB'],
                    ];
                @endphp
                @foreach ($infos as $info)
                <div class="kontak-card pk-reveal">
                    <span class="kontak-card-ico">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $info['ico'] }}"/></svg>
                    </span>
                    <div>
                        <h3>{{ $info['title'] }}</h3>
                        <p>{!! $info['body'] !!}
                            @isset($info['link'])
                                <br><a href="{{ $info['link'] }}">{{ $info['linkText'] }}</a>
                            @endisset
                        </p>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Form --}}
            <div class="kontak-panel pk-reveal">
                <div class="kontak-panel-head">
                    <h2>Kirim Pesan</h2>
                    <p>Isi formulir di bawah ini dan tim kami akan membalasmu melalui email yang terdaftar.</p>
                </div>

                <form id="kontakForm" novalidate>
                    <div class="kontak-form-row kontak-form-row--2">
                        <div class="kontak-field">
                            <label for="fNama">Nama <span>*</span></label>
                            <input id="fNama" name="nama" type="text" placeholder="Nama lengkap kamu" autocomplete="name">
                            <p class="kontak-error"></p>
                        </div>
                        <div class="kontak-field">
                            <label for="fEmail">Email <span>*</span></label>
                            <input id="fEmail" name="email" type="email" placeholder="nama@email.com" autocomplete="email">
                            <p class="kontak-error"></p>
                        </div>
                    </div>

                    <div class="kontak-field">
                        <label for="fSubjek">Subjek <span>*</span></label>
                        <input id="fSubjek" name="subjek" type="text" placeholder="Misal: info paket UTBK Pro">
                        <p class="kontak-error"></p>
                    </div>

                    <div class="kontak-field">
                        <label for="fKategori">Kategori Pertanyaan</label>
                        <select id="fKategori" name="kategori">
                            <option>Informasi Program & Paket</option>
                            <option>Pembayaran & Tagihan</option>
                            <option>Kendala Teknis</option>
                            <option>Kerjasama / Sekolah</option>
                            <option>Lainnya</option>
                        </select>
                    </div>

                    <div class="kontak-field">
                        <label for="fPesan">Pesan <span>*</span></label>
                        <textarea id="fPesan" name="pesan" placeholder="Tuliskan pertanyaan atau kendalamu di sini..."></textarea>
                        <p class="kontak-error"></p>
                    </div>

                    <button type="submit" class="pk-btn pk-btn--dark kontak-submit" style="width:100%;padding:15px 26px;">
                        Kirim Pesan
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </button>

                    <div id="statusOk" class="kontak-status kontak-status--ok" role="status">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Terima kasih! Pesanmu berhasil terkirim. Kami akan membalas dalam 1x24 jam.
                    </div>
                    <div id="statusErr" class="kontak-status kontak-status--err" role="alert">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Mohon periksa kembali isian formulirmu.
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- ============ Mini FAQ ============ -->
<section class="pk-section pk-section--white">
    <div class="pk-container">
        <div class="pk-section-head pk-reveal">
            <span class="pk-eyebrow">Sebelum Menghubungi Kami</span>
            <h2>Pertanyaan Umum Seputar Dukungan</h2>
        </div>

        <div class="kontak-faq" style="max-width:760px;margin:0 auto;">
            @php
                $faqs = [
                    ['q' => 'Berapa lama waktu respons support?', 'a' => 'Rata-rata kami menjawab di bawah 2 jam kerja pada jam operasional. Pertanyaan teknis UTBK yang masuk via fitur Tanya Tutor umumnya dijawab kurang dari 30 menit.'],
                    ['q' => 'Bagaimana cara refund jika tidak cocok?', 'a' => 'Kamu bisa mengajukan refund hingga 14 hari sejak pembelian paket bulanan. Dana akan dikembalikan maksimal 2x24 jam ke nominal terawal.'],
                    ['q' => 'Apakah tersedia paket khusus sekolah?', 'a' => 'Ya. Kami punya paket institusi dengan dashboard khusus untuk guru dan admin sekolah. Hubungi tim kerjasama di pintarkuyid@gmail.com.'],
                    ['q' => 'Apakah bisa belajar lewat HP dengan kuota terbatas?', 'a' => 'Bisa. Semua video kami dikompresi untuk hemat kuota, dan tersedia mode putar audio saat koneksi sedang buruk.'],
                ];
            @endphp
            @foreach ($faqs as $i => $faq)
            <div class="kontak-faq-item pk-reveal {{ $i === 0 ? 'open' : '' }}">
                <button type="button" class="kontak-faq-q" aria-expanded="{{ $i === 0 ? 'true' : 'false' }}">
                    {{ $faq['q'] }}
                    <span class="kf-ico">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    </span>
                </button>
                <div class="kontak-faq-a">{{ $faq['a'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

@include('komponen.footer')
@endsection

@push('styles')
    @vite(['resources/css/halaman/site.css', 'resources/css/halaman/kontak.css'])
@endpush

@push('scripts')
    @vite(['resources/js/halaman/kontak.js'])
    <script>
        window.pintarKuyContactUrl = @json(route('contact.send'));
        window.pintarKuyCsrf = @json(csrf_token());
    </script>
@endpush