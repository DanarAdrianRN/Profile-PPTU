<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE gurus MODIFY kategori ENUM('SMP', 'SMK', 'Madrasah Diniyah', 'Madrasah Al-Quran', 'TPQ') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE gurus MODIFY kategori ENUM('SMP', 'SMK', 'Madrasah Diniyah') NOT NULL");
    }
};
