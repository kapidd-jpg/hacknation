<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users')) {
            DB::statement('ALTER TABLE users MODIFY foto TEXT NULL');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('users')) {
            DB::statement('ALTER TABLE users MODIFY foto VARCHAR(255) NULL');
        }
    }
};