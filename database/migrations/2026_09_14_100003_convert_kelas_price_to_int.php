<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (DB::table('kelas')->select('id', 'price', 'old')->get() as $row) {
            $update = [];

            if ($row->price !== null) {
                $update['price'] = $this->parseRupiah((string) $row->price);
            }

            if ($row->old !== null) {
                $update['old'] = $this->parseRupiah((string) $row->old);
            }

            if ($update !== []) {
                DB::table('kelas')->where('id', $row->id)->update($update);
            }
        }

        Schema::table('kelas', function (Blueprint $table) {
            $table->unsignedBigInteger('price')->nullable()->change();
            $table->unsignedBigInteger('old')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->string('price')->nullable()->change();
            $table->string('old')->nullable()->change();
        });
    }

    protected function parseRupiah(string $raw): int
    {
        $digits = preg_replace('/[^0-9.]/', '', $raw);

        if (($digits ?? '') === '') {
            return 0;
        }

        if (stripos($raw, 'K') !== false) {
            return (int) round(((float) $digits) * 1000);
        }

        return (int) round((float) str_replace('.', '', $digits));
    }
};