<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('siswa');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->string('foto')->nullable();
        });
        Schema::table('users', function (Blueprint $table) {
            $table->string('sekolah')->nullable();
        });
        Schema::table('users', function (Blueprint $table) {
            $table->string('kelas_jurusan')->nullable();
        });
        Schema::table('users', function (Blueprint $table) {
            $table->text('bio')->nullable();
        });
        Schema::table('users', function (Blueprint $table) {
            $table->string('paket')->nullable()->default('utbk-pro');
        });
    }

    public function down(): void
    {
        foreach (['role', 'foto', 'sekolah', 'kelas_jurusan', 'bio', 'paket'] as $column) {
            Schema::table('users', function (Blueprint $table) use ($column) {
                $table->dropColumn($column);
            });
        }
    }
};