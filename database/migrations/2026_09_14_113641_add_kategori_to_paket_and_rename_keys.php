<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('paket', function (Blueprint $table) {
            $table->json('kategori')->nullable()->after('kuota');
        });

        $mapKey = ['starter' => 'utbk', 'utbk-pro' => 'sma-ekstra', 'golden' => 'bahasa'];

        $rows = [
            'utbk' => [
                'nama' => 'Paket UTBK',
                'tag' => 'Paling Laris',
                'harga' => 599000,
                'harga_lama' => 799000,
                'kuota' => null,
                'kategori' => ['UTBK-SNBT'],
                'fitur' => [
                    'Akses semua kelas kategori UTBK-SNBT',
                    'TPS lengkap: PU, PK, PBM',
                    'Literasi & Penalaran Matematika',
                    '6.000+ soal HOTS + pembahasan',
                    'Tryout mingguan + analitik IRT',
                    'Live class tanpa batas',
                ],
            ],
            'sma-ekstra' => [
                'nama' => 'Paket SMA + Ekstra',
                'tag' => null,
                'harga' => 499000,
                'harga_lama' => 699000,
                'kuota' => null,
                'kategori' => ['SMA', 'Ekstra'],
                'fitur' => [
                    'Akses semua mapel SMA',
                    'Kelas ekstrakurikuler & skill',
                    'Bank soal per mapel SMA',
                    'Pendampingan kurikulum sekolah',
                    'Live class tanpa batas',
                    'Laporan progres bulanan',
                ],
            ],
            'bahasa' => [
                'nama' => 'Paket Bahasa',
                'tag' => null,
                'harga' => 399000,
                'harga_lama' => 599000,
                'kuota' => null,
                'kategori' => ['Bahasa'],
                'fitur' => [
                    'Akses semua kelas Bahasa',
                    'Bahasa populer: Inggris, Jerman, Korea, Jepang, Mandarin',
                    'Persiapan TOEFL, TOEIC, IELTS',
                    'Latihan listening & speaking',
                    'Live class tutor native',
                ],
            ],
        ];

        foreach ($rows as $newKey => $data) {
            $data['kategori'] = json_encode($data['kategori']);
            $data['fitur'] = json_encode($data['fitur']);
            $oldKey = collect($mapKey)->search($newKey, true);
            if ($oldKey && DB::table('paket')->where('key', $oldKey)->exists()) {
                DB::table('paket')->where('key', $oldKey)->update([...$data, 'key' => $newKey]);
            } else {
                DB::table('paket')->updateOrInsert(['key' => $newKey], $data);
            }
        }

        foreach ($mapKey as $old => $new) {
            DB::table('users')->where('paket', $old)->update(['paket' => $new]);
        }
    }

    public function down(): void
    {
        $mapKey = ['utbk' => 'starter', 'sma-ekstra' => 'utbk-pro', 'bahasa' => 'golden'];
        foreach ($mapKey as $new => $old) {
            DB::table('users')->where('paket', $new)->update(['paket' => $old]);
        }

        Schema::table('paket', function (Blueprint $table) {
            $table->dropColumn('kategori');
        });
    }
};