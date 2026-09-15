<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('kelas', 'siswa')) {
            Schema::table('kelas', function ($table) {
                $table->unsignedInteger('siswa')->default(0)->after('durasi');
            });
        }

        $cols = DB::select('SHOW COLUMNS FROM kelas');
        foreach ($cols as $col) {
            if (! in_array($col->Field, ['price', 'old'], true)) {
                continue;
            }

            if (stripos($col->Type, 'varchar') !== false || stripos($col->Type, 'text') !== false) {
                continue;
            }

            DB::statement("ALTER TABLE kelas MODIFY {$col->Field} VARCHAR(255) NULL");
        }
    }

    public function down(): void
    {
    }
};