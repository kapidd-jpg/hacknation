<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    protected function parseRupiah(?string $value): int
    {
        if ($value === null || trim($value) === '') {
            return 0;
        }

        $clean = preg_replace('/[^0-9Kk]/', '', $value) ?? '';
        $hasK = preg_match('/K/i', $value) === 1;
        $angka = (int) $clean;

        return $hasK ? $angka * 1000 : $angka;
    }

    public function up(): void
    {
        $rows = DB::table('kelas')->get(['id', 'price', 'old']);

        foreach ($rows as $row) {
            DB::table('kelas')->where('id', $row->id)->update([
                'price' => $this->parseRupiah($row->price),
                'old' => $this->parseRupiah($row->old),
            ]);
        }

        $cols = DB::select('SHOW COLUMNS FROM kelas');
        foreach ($cols as $col) {
            if (! in_array($col->Field, ['price', 'old'], true)) {
                continue;
            }

            DB::statement("ALTER TABLE kelas MODIFY {$col->Field} INT UNSIGNED NULL DEFAULT NULL");
        }
    }

    public function down(): void
    {
        $cols = DB::select('SHOW COLUMNS FROM kelas');
        foreach ($cols as $col) {
            if (! in_array($col->Field, ['price', 'old'], true)) {
                continue;
            }

            DB::statement("ALTER TABLE kelas MODIFY {$col->Field} VARCHAR(255) NULL");
        }
    }
};