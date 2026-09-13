<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paket', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('nama');
            $table->string('tag')->nullable();
            $table->unsignedInteger('harga')->default(0);
            $table->unsignedInteger('harga_lama')->default(0);
            $table->unsignedInteger('kuota')->nullable()->comment('null = tanpa batas');
            $table->json('fitur')->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paket');
    }
};