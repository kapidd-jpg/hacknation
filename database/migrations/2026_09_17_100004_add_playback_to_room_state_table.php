<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('room_state', function (Blueprint $table) {
            $table->boolean('play')->default(false)->after('halaman');
        });
        Schema::table('room_state', function (Blueprint $table) {
            $table->double('waktu', 10, 3)->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('room_state', function (Blueprint $table) {
            $table->dropColumn(['play', 'waktu']);
        });
    }
};