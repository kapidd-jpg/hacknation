<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_paket', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('paket_id')->constrained('paket')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'paket_id']);
        });

        $users = DB::table('users')->whereNotNull('paket')->where('paket', '!=', '')->get(['id', 'paket']);
        foreach ($users as $user) {
            $paket = DB::table('paket')->where('key', $user->paket)->first();
            if (! $paket) {
                continue;
            }
            DB::table('user_paket')->updateOrInsert(
                ['user_id' => $user->id, 'paket_id' => $paket->id],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('paket');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('paket')->nullable()->default('utbk')->after('bio');
        });

        $rows = DB::table('user_paket')
            ->join('paket', 'paket.id', '=', 'user_paket.paket_id')
            ->select('user_paket.user_id', 'paket.key')
            ->get();

        foreach ($rows->groupBy('user_id') as $userId => $items) {
            DB::table('users')->where('id', $userId)->update(['paket' => $items->first()->key]);
        }

        Schema::dropIfExists('user_paket');
    }
};