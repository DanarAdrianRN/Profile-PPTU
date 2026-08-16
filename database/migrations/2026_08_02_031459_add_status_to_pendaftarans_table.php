<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // SQLite represents enum columns as text and cannot execute MySQL's
        // MODIFY syntax. Application validation remains the source of truth
        // during SQLite-based automated tests.
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        DB::statement("
            ALTER TABLE pendaftarans
            MODIFY COLUMN status ENUM('belum_bayar', 'menunggu_verifikasi', 'diterima', 'ditolak')
            DEFAULT 'belum_bayar'
        ");
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        // Pindahkan dulu data belum_bayar supaya tidak terpotong saat enum dikecilkan lagi.
        DB::table('pendaftarans')
            ->where('status', 'belum_bayar')
            ->update(['status' => 'menunggu_verifikasi']);

        DB::statement("
            ALTER TABLE pendaftarans
            MODIFY COLUMN status ENUM('menunggu_verifikasi', 'diterima', 'ditolak')
            DEFAULT 'menunggu_verifikasi'
        ");
    }
};
