<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // SQLite does not support MySQL's ALTER TABLE ... MODIFY syntax. The
        // original create migration already declares this same set of values.
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        DB::statement("ALTER TABLE gurus MODIFY kategori ENUM('SMP', 'SMK', 'Madrasah Diniyah', 'Madrasah Al-Quran', 'TPQ') NOT NULL");
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        DB::statement("ALTER TABLE gurus MODIFY kategori ENUM('SMP', 'SMK', 'Madrasah Diniyah') NOT NULL");
    }
};
