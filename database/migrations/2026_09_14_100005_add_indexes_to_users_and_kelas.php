<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasIndex('users', 'users_role_index')) {
                $table->index('role');
            }
        });

        Schema::table('kelas', function (Blueprint $table) {
            if (! Schema::hasIndex('kelas', 'kelas_cat_index')) {
                $table->index('cat');
            }
            if (! Schema::hasIndex('kelas', 'kelas_aktif_index')) {
                $table->index('aktif');
            }
        });
    }

    public function down(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->dropIndex(['aktif']);
            $table->dropIndex(['cat']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
        });
    }
};