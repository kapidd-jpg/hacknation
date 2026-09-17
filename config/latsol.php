<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Ambang Tuntas Latihan (Set Latsol)
    |--------------------------------------------------------------------------
    | Materi (modul) hanya bisa ditandai tuntas / lanjut ke bab berikutnya
    | jika skor attempt latihan soal pada set bab tersebut >= nilai ini.
    | Ubah di sini tanpa perlu menyentuh kode.
    */
    'passing_threshold' => 70,

    /*
    |--------------------------------------------------------------------------
    | TTL cache statistik global (landing page)
    |--------------------------------------------------------------------------
    | AVG(skor) & total attempt seluruh siswa di-cache selama durasi ini
    | (detik) agar halaman landing tidak menghitung real-time tiap request.
    */
    'statistik_ttl' => 3600,

];