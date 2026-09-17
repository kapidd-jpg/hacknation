<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengerjaan', function (Blueprint $table) {
            $table->unsignedInteger('salah')->default(0)->after('benar');
            $table->unsignedInteger('kosong')->default(0)->after('salah');
            $table->foreignId('materi_id')->nullable()->after('kelas_id')->constrained('materi')->nullOnDelete();
            $table->timestamp('waktu_mulai')->nullable();
            $table->timestamp('waktu_selesai')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('pengerjaan', function (Blueprint $table) {
            $table->dropConstrainedForeignId('materi_id');
            $table->dropColumn(['salah', 'kosong', 'waktu_mulai', 'waktu_selesai']);
        });
    }
};