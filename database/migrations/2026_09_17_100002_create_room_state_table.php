<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('room_state', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained('rooms')->cascadeOnDelete();
            $table->foreignId('kelas_id')->nullable()->constrained('kelas')->cascadeOnDelete();
            $table->foreignId('materi_id')->nullable()->constrained('materi')->cascadeOnDelete();
            $table->unsignedInteger('halaman')->nullable();
            $table->string('pengirim')->nullable();
            $table->timestamps();
            $table->unique('room_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_state');
    }
};