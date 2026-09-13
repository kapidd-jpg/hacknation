<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('cat');
            $table->string('ico', 4)->default('KL');
            $table->string('meta')->nullable();
            $table->text('desc')->nullable();
            $table->unsignedInteger('modul')->default(1);
            $table->string('durasi')->nullable();
            $table->unsignedInteger('siswa')->default(0);
            $table->string('price')->nullable();
            $table->string('old')->nullable();
            $table->string('bg')->nullable();
            $table->string('color')->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};