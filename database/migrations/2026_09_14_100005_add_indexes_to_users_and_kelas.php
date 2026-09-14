<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->index('role');
            $table->index('paket');
        });

        Schema::table('kelas', function (Blueprint $table) {
            $table->index('cat');
            $table->index('aktif');
        });
    }

    public function down(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->dropIndex(['aktif']);
            $table->dropIndex(['cat']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['paket']);
            $table->dropIndex(['role']);
        });
    }
};