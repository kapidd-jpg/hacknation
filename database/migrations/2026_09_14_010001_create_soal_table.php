<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('soal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->foreignId('materi_id')->nullable()->constrained('materi')->nullOnDelete();
            $table->string('set_label');
            $table->text('pertanyaan');
            $table->json('opsi');
            $table->unsignedTinyInteger('kunci')->default(0);
            $table->text('pembahasan')->nullable();
            $table->unsignedInteger('urutan')->default(1);
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('soal');
    }
};