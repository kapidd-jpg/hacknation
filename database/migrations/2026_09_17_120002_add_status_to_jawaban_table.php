<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jawaban', function (Blueprint $table) {
            $table->string('status', 10)->default('kosong')->after('benar')->index();
        });
    }

    public function down(): void
    {
        Schema::table('jawaban', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};