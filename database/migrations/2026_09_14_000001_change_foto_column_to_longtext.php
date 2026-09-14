<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE users MODIFY COLUMN foto LONGTEXT NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE users MODIFY COLUMN foto VARCHAR(255) NULL');
    }
};