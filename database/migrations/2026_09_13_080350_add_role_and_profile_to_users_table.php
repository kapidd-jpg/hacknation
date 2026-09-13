<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('siswa')->after('password');
            $table->string('foto')->nullable()->after('role');
            $table->string('sekolah')->nullable()->after('foto');
            $table->string('kelas_jurusan')->nullable()->after('sekolah');
            $table->text('bio')->nullable()->after('kelas_jurusan');
            $table->string('paket')->default('utbk-pro')->nullable()->after('bio');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'foto', 'sekolah', 'kelas_jurusan', 'bio', 'paket']);
        });
    }
};